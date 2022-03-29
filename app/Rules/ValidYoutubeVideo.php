<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Youtube;
use YoutubeDl\Options;
use YoutubeDownload;

class ValidYoutubeVideo implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            $videoId = Youtube::parseVidFromURL($value);
            $video = Youtube::getVideoInfo($videoId, ['id']);
        } catch (Exception $exception) {
            return false;
        }

        if($video) {
            try {
                $collection = YoutubeDownload::download(
                    Options::create()->skipDownload(true)
                        ->url($value)
                        ->downloadPath(storage_path())
                        ->maxDownloads(1)
                );

                if(!$collection->count())
                    return false;

                foreach($collection->getVideos() as $video) {
                    if($video->getExtractor())
                        return true;
                    else
                        return false;
                }
                return false;
            } catch (Exception) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Die angegebene URL ist keine Youtube URL.';
    }
}
