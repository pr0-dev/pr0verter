<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Youtube;

class MaxVideoTime implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $videoId = Youtube::parseVidFromURL($value);
            $video = Youtube::getVideoInfo($videoId);
            return strtotime($video->contentDetails->duration) < 60 * 60 * 8;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The provided video is too long.';
    }
}
