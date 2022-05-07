<?php

namespace App\Http\Requests;

use App\Rules\AudioRule;
use App\Rules\ClipEndRule;
use App\Rules\ClipStartRule;
use App\Rules\IsVideo;
use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        /** Always authorize */
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'size' => 'required|bail|integer|min:' . config('pr0verter.minResultSize') * 8192 . '|max:' . config('pr0verter.maxResultSize') * 8192,
            'sound' => ['required', 'bail', 'integer', new AudioRule],
            'start' => ['filled', 'bail', 'integer', new ClipStartRule],
            'end' => ['filled', 'bail', 'integer', new ClipEndRule],
            'ratio' => 'required|bail|boolean',
            config('pr0verter.disabled.inputs.interpolation') ? : 'interpolation' => 'required|bail|boolean',
            'video' => ['required', 'bail', 'file', 'min:' . config('pr0verter.minUploadSize'), 'max:' . config('pr0verter.maxUploadSize'), 'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi,video/x-matroska', new IsVideo]
        ];
    }
}
