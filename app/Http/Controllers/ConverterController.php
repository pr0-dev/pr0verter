<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertFileRequest;
use App\Http\Requests\GetYoutubeDataRequest;
use App\Jobs\ConvertVideoJob;
use App\Models\Upload;
use App\Models\VideoList;
use App\Utilities\Converter\Converter;
use DateInterval;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Locale;
use Youtube;

class ConverterController extends Controller
{
    /**
     * @param string $id
     * @return Factory|View|Application
     */
    public function show(string $id): Factory|View|Application
    {
        return view('converter.show');
    }

    /**
     * @param Request $request
     * @param $guid
     * @return View|Factory|Application
     */
    public function progress(Request $request, string $guid): View|Factory|Application
    {
        return view('converter.progress', ['id' => $guid]);
    }

    /**
     * @return Factory|View|Application
     */
    public function home(): Factory|View|Application
    {
        return view('converter.home');
    }

    /**
     * @param string $id
     * @return void
     */
    public function download(string $id)
    {
        return;
    }

    /**
     * @param ConvertFileRequest $request
     * @return RedirectResponse
     */
    public function convertUpload(ConvertFileRequest $request): RedirectResponse
    {
        $guid = uniqid();
        while (true) {
            if (VideoList::find($guid))
                $guid = uniqid();
            else
                break;
        }

        VideoList::create(['guid' => $guid, 'load_type' => Upload::class, 'uploaderIP' => $request->ip()]);
        $upload = Upload::create([
            'guid' => $guid, 'mime_type' => $request->file('video')->getClientMimeType(),
            'extension' => $request->file('video')->getClientOriginalExtension(),
            'filename' => $guid.'.'.$request->file('video')->getClientOriginalExtension(),
            'input_folder' => null, 'result_folder' => null
        ]);


        $request->file('video')->move($upload->input_folder, $upload->filename);
        $converter = new Converter($request, $upload->input_folder.'/'.$upload->filename, $guid);
        $this->dispatch((new ConvertVideoJob($converter->getFFMpegConfig()))->onQueue('convert'));
        return redirect()->route('progress');
    }

    public function convertDownload(Request $request): RedirectResponse
    {
        return redirect()->route('progress');
    }

    public function convertYoutube(Request $request): RedirectResponse
    {
        return redirect()->route('progress');
    }

    /**
     * @param GetYoutubeDataRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function ytInfo(GetYoutubeDataRequest $request): JsonResponse
    {
        if (!$request->ajax()) {
            abort('404');
        }
        try {
            $videoId = Youtube::parseVidFromURL($request->input('url'));
            $videoData = Youtube::getVideoInfo($videoId);
            $languages = array();
            if ($videoData->contentDetails->caption) {
                $captionData = Youtube::getCaptionInfo($videoId);
                foreach ($captionData as $captionLang) {
                    $languages[$captionLang->snippet->language] = Locale::getDisplayName($captionLang->snippet->language);
                }
            }

            return response()->json([
                'title' => $videoData->snippet->title,
                'availableSubtitles' => $languages,
                'duration' => new DateInterval($videoData->contentDetails->duration)
            ]);
        } catch (Exception) {
            return response()->json(null, 500);
        }
    }

    public function view(Request $request, string $guid)
    {
        //
    }

    public function progressInfo(Request $request, string $guid)
    {
        VideoList::whereGuid($guid);
    }
}
