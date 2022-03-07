<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertFileRequest;
use App\Http\Requests\GetYoutubeDataRequest;
use App\Jobs\ConvertVideoJob;
use App\Models\Conversion;
use App\Models\Upload;
use App\Utilities\Converter;
use DateInterval;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Locale;
use Storage;
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
        $upload = Upload::initialize($request);

        $conversion = Conversion::initialize($upload->id, Upload::class, 'uploadSource', 'uploadResult', $request->getClientIp());

        $request->file('video')->storeAs('/', $conversion->filename, $conversion->source_disk);

        try {
            $converter = new Converter(Storage::disk($conversion->source_disk)->path($conversion->filename), $conversion);
        } catch (Exception $exception) {
            $conversion->failed = true;
            $conversion->probe_error = $exception->getMessage();
            $conversion->save();
            return redirect()->back();
        }
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
