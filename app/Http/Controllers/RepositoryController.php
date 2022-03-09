<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetYoutubeDataRequest;
use DateInterval;
use Exception;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\JsonResponse;
use Locale;
use Youtube;

class RepositoryController extends Controller
{
    public function youtubeInfo(GetYoutubeDataRequest $request): JsonResponse
    {
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

    /**
     * @return JsonResponse
     */
    public function githubInfo(): JsonResponse
    {
        return response()->json(GitHub::repo()->commits()->all('pr0-dev', 'pr0verter', ['sha' => 'master']));
    }
}
