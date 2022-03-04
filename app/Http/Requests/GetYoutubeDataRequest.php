<?php

namespace App\Http\Requests;

use App\Rules\ValidYoutubeVideo;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class GetYoutubeDataRequest extends FormRequest {

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
     * @return array[]
     */
    #[ArrayShape(['url' => "array"])] public function rules(): array
    {
        return [
            'url' => ['required', 'bail', 'string', new ValidYoutubeVideo]
        ];
    }
}
