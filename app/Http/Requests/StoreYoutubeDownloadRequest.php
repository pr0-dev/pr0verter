<?php

namespace App\Http\Requests;

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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'size' => 'required|bail|integer|min:' . config('pr0verter.minResultSize') . '|max:' . config('pr0verter.maxResultSize'),
            'url' => ['required', 'bail', new ValidYoutubeVideo],
            'sound' => 'required|bail|integer|min:' . config('pr0verter.minResultAudioBitrate') .'|max:' . config('pr0verter.maxResultAudioBitrate'),
            'start' => 'required|bail|integer|lte:end',
            'end' => 'required|bail|integer|gte:start',
            'resolution' => 'required|bail|boolean',
            config('pr0verter.disabled.inputs.interpolation') ? : 'interpolation' => 'required|bail|boolean'
        ];
    }
}
