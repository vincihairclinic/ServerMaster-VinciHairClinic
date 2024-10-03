<?php

namespace App\Http\Requests\Base;

use App\Repositories\Base\RequestRepository;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class EditPhoneNumberRequest extends FormRequest
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
            //'phone_number' => ['nullable', new PhoneNumber],
            'phone_number'  => 'nullable|string|min:10|max:16',
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

