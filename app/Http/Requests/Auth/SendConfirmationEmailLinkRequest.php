<?php

namespace App\Http\Requests\Auth;

use App\Models\Datasets\UserStatus;
use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;

class SendConfirmationEmailLinkRequest extends FormRequest
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
            'email' => 'required|email:rfc,dns|max:80|exists:users,email,status_id,'.UserStatus::NEW,
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

