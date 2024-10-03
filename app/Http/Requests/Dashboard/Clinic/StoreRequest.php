<?php

namespace App\Http\Requests\Dashboard\Clinic;

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
            'name_en'  => 'required|string|min:2|max:150',
            'name_pt'  => 'required|string|min:2|max:150',
            'about_en'  => 'required|string|min:2',
            'about_pt'  => 'required|string|min:2',
            'benefits_en'  => 'required|array',
            'benefits_pt'  => 'required|array',
            'lat'  => 'numeric',
            'lng'  => 'numeric',
            'about_clinic_location_en'  => 'required|string|min:2',
            'about_clinic_location_pt'  => 'required|string|min:2',
            'address'  => 'required|string|min:2',
            'postcode'  => 'required|string|min:2|max:20',
            'phone_number'  => 'required|string|min:2|max:16',
            'email'  => 'required|string|email|min:2|max:100',
            'whatsapp'  => 'required|string|min:2|max:100',
            'country_id'  => 'required|int|exists:countries,id',
            'images'  => 'array|required',
            'image'  => 'required',
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

