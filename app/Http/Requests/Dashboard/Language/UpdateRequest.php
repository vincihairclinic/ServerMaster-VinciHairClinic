<?php

namespace App\Http\Requests\Dashboard\Language;

use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name' => ['required', 'string', Rule::unique('languages')->ignore($this->route('model')->id), 'min:2', 'max:100'],
            'key' => [ 'required', 'string', Rule::unique('languages')->ignore($this->route('model')->id), 'max:2'],
            'image' => 'required_with:images_deleted_image',
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
