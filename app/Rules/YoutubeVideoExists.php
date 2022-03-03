<?php

namespace App\Rules;

use Alaouy\Youtube\Facades\Youtube;
use Exception;
use Illuminate\Contracts\Validation\Rule;


class YoutubeVideoExists implements Rule
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
            if(Youtube::getVideoInfo(Youtube::parseVidFromURL($value))) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
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
        return 'This video does not exist or is private.';
    }
}
