<?php

namespace App\Http\Controllers;

use DateInterval;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Locale;
use Youtube;
use Exception;

class RepositoryController extends Controller
{
    public function youtubeInfo(Request $request): JsonResponse
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
}
