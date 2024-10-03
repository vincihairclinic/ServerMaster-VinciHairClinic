<?php

namespace App\Http\Requests\Dashboard\Localization;

use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;
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
            'value_en'  => 'required|string|min:1',
            'value_pt'  => 'required|string|min:1',
            'key'  => ['required', 'string', 'min:1', 'max:255', Rule::unique('localizations')->ignore($this->route('model')->id)],
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
