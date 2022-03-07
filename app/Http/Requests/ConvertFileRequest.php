<?php

namespace App\Http\Requests;

use App\Rules\ResultBetweenMinAndMaxSize;
use App\Rules\UploadBetweenMinAndMaxSize;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class ConvertFileRequest extends FormRequest
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
    #[ArrayShape(['size' => "string", 'video' => "string", 'audio' => "string", 'resolution' => "string", 'start' => "string", 'end' => "string"])] public function rules(): array
    {
        return [
            'size' => 'required|integer|min:' . config('pr0verter.minResultSize') . '|max:' . config('pr0verter.maxResultSize'),
            'video' => 'required|file|min:' . config('pr0verter.minUploadSize') . '|max:' . config('pr0verter.maxUploadSize'),
            'audio' => 'required|integer|max:255',
            'start' => 'filled|integer|lte:end',
            'end' => 'filled|integer|gte:start'
        ];
    }
}
