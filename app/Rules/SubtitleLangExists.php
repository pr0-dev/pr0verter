<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Youtube;

class SubtitleLangExists implements Rule, DataAwareRule
{

    private $data;
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
        $captionData = Youtube::getCaptionInfo($this->data['url']);
        foreach ($captionData as $captionLang) {
            if($captionLang === $value)
                return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'This subtitle language is not available for this video!';
    }

    /**
     * @param $data
     * @return SubtitleLangExists|$this
     */
    public function setData($data): SubtitleLangExists|static
    {
        $this->data = $data;

        return $this;
    }
}
