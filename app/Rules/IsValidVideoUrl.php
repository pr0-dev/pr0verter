<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use YoutubeDl\Options;
use YoutubeDownload;
use Exception;

class IsValidVideoUrl implements Rule
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
            $collection = YoutubeDownload::download(
                Options::create()->skipDownload(true)
                    ->url($value)
                    ->downloadPath(storage_path())
                    ->maxDownloads(1)
            );

            if(!$collection->count())
                return false;

            foreach($collection->getVideos() as $video) {
                if($video->getFormatId() != '0')
                    return true;
                else
                    return false;
            }
            return false;
        } catch (Exception) {
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
        return 'Unter dieser URL kann kein gültiges Video gefunden werden.';
    }
}
