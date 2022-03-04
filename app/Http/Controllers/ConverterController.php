<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetYoutubeDataRequest;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @param string $id
     * @return Factory|View|Application
     */
    public function progress(string $id): Factory|View|Application
    {
        return view('converter.progress');
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function load(Request $request): RedirectResponse
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
}
