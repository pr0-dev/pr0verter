<?php

namespace App\Http\Requests;

use App\Rules\IsValidVideoUrl;
use App\Rules\SubtitleLangExists;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class StoreDownloadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['size' => "string", 'url' => "array", 'sound' => "string", 'start' => "string", 'end' => "string", 'ratio' => "string", 'interpolation' => "string", 'subtitle' => "array"])]
    public function rules(): array
    {
        return [
            'size' => 'required|bail|integer|min:' . config('pr0verter.minResultSize') * 8192 . '|max:' . config('pr0verter.maxResultSize') * 8192,
            'url' => ['required', 'bail', new IsValidVideoUrl],
            'sound' => 'required|bail|integer|max:' . config('pr0verter.maxResultAudioBitrate'),
            'start' => 'required|bail|integer',
            'end' => 'required|bail|integer',
            'ratio' => 'required|bail|boolean',
            config('pr0verter.disabled.inputs.interpolation') ? : 'interpolation' => 'required|bail|boolean'
        ];
    }
}
