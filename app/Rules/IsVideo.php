<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe;

class IsVideo implements Rule
{
    private \FFMpeg\FFProbe $probe;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->probe = FFProbe::create(config('laravel-ffmpeg'));
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
            $this->probe->format($value)->all();
            return true;
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
        return 'Das Video kann nicht gelesen werden.';
    }
}
