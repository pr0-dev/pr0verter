<?php

namespace App\Http\Requests;

use App\Rules\AudioRule;
use App\Rules\ClipEndRule;
use App\Rules\ClipStartRule;
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
            'size' => 'required|bail|integer|min:' . config('pr0verter.minResultSize') . '|max:' . config('pr0verter.maxResultSize'),
            'sound' => ['required', 'bail', 'integer', new AudioRule],
            'start' => ['required', 'bail', 'integer', new ClipStartRule],
            'end' => ['required', 'bail', 'integer', new ClipEndRule],
            'ratio' => 'required|bail|boolean',
            config('pr0verter.disabled.inputs.interpolation') ? : 'interpolation' => 'required|bail|boolean',
            'url' => ['required', 'bail', new IsValidVideoUrl]
        ];
    }
}
