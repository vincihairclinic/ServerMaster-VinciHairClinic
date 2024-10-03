<?php

namespace App\Http\Requests\Base;

use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;

class EditUrlImagesRequest extends FormRequest
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
            'url_images' => 'present|array|max:3',
            'url_images.*' => 'nullable|string|min:2|max:100',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return RequestRepository::messages();
    }
}

