<?php

namespace App\Http\Requests\Auth;

use App\Models\Datasets\UserStatus;
use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name' => 'nullable|string|max:40',
            'password' => 'required|string|min:6|max:50|confirmed',
            'email' => ['required', 'email', 'max:80', Rule::unique('users', 'email')->where(function ($q) {
                $q->where('status_id', '>', UserStatus::NEW);
            })],
            //'email' => 'required|email:rfc,dns|max:80|unique:users,email,is_email_verified,1',
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

