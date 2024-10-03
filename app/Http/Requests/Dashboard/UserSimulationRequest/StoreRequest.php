<?php

namespace App\Http\Requests\Dashboard\UserSimulationRequest;

use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
//            'user_id'  => 'required|int|exists:users,id',
            'full_name'  => 'required|string|min:2|max:150',
            'email'  => 'required|string|min:2|max:150',
            'phone_number'  => 'required|string|min:2|max:150',
            'country_id'  => 'required|int|exists:countries,id',
            'hair_front_image'  => 'required',
            'hair_side_image'  => 'required',
            'hair_back_image'  => 'required',
            'hair_top_image'  => 'required',
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

