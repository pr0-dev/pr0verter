<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Youtube;

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

        return $video != false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The supplied URL does not look like a Youtube URL.';
    }
}
