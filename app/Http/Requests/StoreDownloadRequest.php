<?php

namespace App\Http\Requests;

use App\Rules\IsValidVideoUrl;
use Illuminate\Foundation\Http\FormRequest;

class StoreDownloadRequest extends FormRequest
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
            'size' => 'required|bail|integer|min:' . config('pr0verter.minResultSize') . '|max:' . config('pr0verter.maxResultSize'),
            'url' => ['required', 'bail', new IsValidVideoUrl],
            'audio' => 'required|bail|integer|max:255',
            'start' => 'required|bail|integer|lte:end',
            'end' => 'required|bail|integer|gte:start',
            'resolution' => 'required|bail|boolean',
            'interpolation' => 'required|bail|boolean'
        ];
    }
}
