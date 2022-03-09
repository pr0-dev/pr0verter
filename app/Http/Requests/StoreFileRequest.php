<?php

namespace App\Http\Requests;

use App\Rules\IsVideo;
use App\Rules\ResultBetweenMinAndMaxSize;
use App\Rules\UploadBetweenMinAndMaxSize;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

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
            'size' => 'required|bail|integer|min:' . config('pr0verter.minResultSize') . '|max:' . config('pr0verter.maxResultSize'),
            'video' => ['required', 'bail', 'file|min:' . config('pr0verter.minUploadSize'), 'max:' . config('pr0verter.maxUploadSize'), new IsVideo],
            'audio' => 'required|bail|integer|max:255',
            'start' => 'required|bail|integer|lte:end',
            'end' => 'required|bail|integer|gte:start',
            'resolution' => 'required|bail|boolean',
            'interpolation' => 'required|bail|boolean'
        ];
    }
}
