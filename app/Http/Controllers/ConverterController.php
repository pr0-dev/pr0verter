<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertFileRequest;
use App\Http\Requests\GetYoutubeDataRequest;
use App\Jobs\ConvertVideoJob;
use App\Models\Upload;
use App\Models\VideoList;
use App\Utilities\Converter\Converter;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Locale;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe;
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
     * @param string $id
     * @return Factory|View|Application
     */
    public function progress()
    {
        $probe = FFProbe::create(config('laravel-ffmpeg'));

        var_dump($probe->format(storage_path('raw/upload/1.mp4'))->all());
        var_dump($probe->streams(storage_path('raw/upload/1.mp4'))->all());
        var_dump($probe->streams(storage_path('raw/upload/1.mp4'))->audios()->count());
        var_dump($probe->streams(storage_path('raw/upload/1.mp4'))->videos()->first()->getDimensions()->getRatio());
        var_dump($probe->streams(storage_path('raw/upload/1.mp4'))->videos()->first()->getDimensions()->getHeight());
        var_dump($probe->streams(storage_path('raw/upload/1.mp4'))->videos()->first()->getDimensions()->getWidth());
        var_dump($probe->format(storage_path('raw/upload/2.gif'))->all());
        var_dump($probe->streams(storage_path('raw/upload/2.gif'))->all());
        var_dump($probe->streams(storage_path('raw/upload/2.gif'))->audios()->count());
        var_dump($probe->format(storage_path('raw/upload/3.mp4'))->all());
        var_dump($probe->streams(storage_path('raw/upload/3.mp4'))->all());
        var_dump($probe->streams(storage_path('raw/upload/3.mp4'))->audios()->count());
        return;
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
        $guid = uniqid('Upload');
        while(true) {
            if(VideoList::whereGuid($guid)->count() > 0)
                $guid = uniqid('Upload');
            else
                break;
        }

        VideoList::create(['guid' => $guid, 'type' => 'Upload', 'uploaderIP' => $request->ip()]);
        Upload::create(['guid' => $guid, 'mime_type' => $request->file('video')->getClientMimeType()]);

        $request->file('video')->move(storage_path('raw/Upload'), $guid.$request->file('video')->getClientOriginalExtension());
        $converter = new Converter($request, storage_path('raw/Upload/'.$guid.$request->file('video')->getClientOriginalExtension()), $guid);
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
        if(!$request->ajax()) {
            abort('404');
        }
        try {
            $videoId = Youtube::parseVidFromURL($request->input('url'));
            $videoData = Youtube::getVideoInfo($videoId);
            $languages = array();
            if($videoData->contentDetails->caption) {
                $captionData = Youtube::getCaptionInfo($videoId);
                foreach ($captionData as $captionLang) {
                    $languages[$captionLang->snippet->language] = Locale::getDisplayName($captionLang->snippet->language);
                }
            }

            return response()->json([
                'title' => $videoData->snippet->title,
                'availableSubtitles' => $languages,
                'duration' => new \DateInterval($videoData->contentDetails->duration)
            ]);
        } catch (Exception $exception) {
            return response()->json(null, 500);
        }
    }

    public function view(Request $request, string $id) {
        //
    }
}
