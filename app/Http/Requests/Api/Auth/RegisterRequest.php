<?php

namespace App\Http\Requests\Api\Auth;

use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'password'    => 'required|string|min:6|max:100',
            'email'       => 'required|string|email:rfc,dns|min:6|max:100|unique:users',
            'full_name'  => 'nullable|string|min:2|max:100',
            'country_id'  => 'nullable|integer|exists:countries,id',
            'language_key'  => 'nullable|string|exists:languages,key',
            'app_state'  => 'nullable|string|min:2|max:100',
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

