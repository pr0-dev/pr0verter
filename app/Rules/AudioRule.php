<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AudioRule implements Rule
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
        return !($value >= config('pr0verter.maxResultAudioBitrate') ||
            ($value <= config('pr0verter.minResultAudioBitrate') &&
                $value != 0)
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
