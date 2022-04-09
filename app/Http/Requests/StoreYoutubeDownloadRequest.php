<?php

namespace App\Http\Requests;

use App\Rules\AudioRule;
use App\Rules\ClipEndRule;
use App\Rules\ClipStartRule;
use App\Rules\SubtitleLangExists;
use App\Rules\ValidYoutubeVideo;
use Illuminate\Foundation\Http\FormRequest;

class StoreYoutubeDownloadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'size' => 'required|bail|integer|min:' . config('pr0verter.minResultSize') * 8192 . '|max:' . config('pr0verter.maxResultSize') * 8192,
            'sound' => ['required', 'bail', 'integer', new AudioRule],
            'start' => ['filled', 'bail', 'integer', new ClipStartRule],
            'end' => ['filled', 'bail', 'integer', new ClipEndRule],
            'ratio' => 'required|bail|boolean',
            config('pr0verter.disabled.inputs.interpolation') ? : 'interpolation' => 'required|bail|boolean',
            'url' => ['required', 'bail', new ValidYoutubeVideo],
            'subtitle' => ['filled', 'bail', new SubtitleLangExists]
        ];
    }
}
