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
        $matches = array();
        preg_match('/(?:https?:\/\/)?(?:youtu\.be\/|(?:www\.|m\.)?youtube\.com\/(?:watch|v|embed)(?:\.php)?(?:\?.*v=|\/))([a-zA-Z0-9\_-]+)/', $value, $matches);
        if(!isset($matches[0]))
            return false;

        try {
            $videoId = Youtube::parseVidFromURL($value);
            $video = Youtube::getVideoInfo($videoId, ['id']);
        } catch (Exception $exception) {
            return false;
        }
        return (bool)$video;
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
