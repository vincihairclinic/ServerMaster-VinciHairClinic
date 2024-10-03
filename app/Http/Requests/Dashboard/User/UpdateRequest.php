<?php

namespace App\Http\Requests\Dashboard\User;

use App\Models\Datasets\Gender;
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
            'full_name' => 'required|string|min:6|max:100',
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($this->route('model')->id)],
            'password' => 'nullable|string|min:6|max:100|confirmed',
            'age' => 'required|int|min:1|max:120',
            'phone_number' => 'required|string|min:8|max:16',
            'gender_id' => 'required|int|in:' . Gender::implodeIds(),
            'hair_front_image' => 'required_with:images_deleted_hair_front_image',
            'hair_side_image' => 'required_with:images_deleted_hair_side_image',
            'hair_back_image' => 'required_with:images_deleted_hair_back_image',
            'hair_top_image' => 'required_with:images_deleted_hair_top_image',
            'clinic_id' => 'required|int|exists:clinics,id',
            'hair_loss_type_id' => 'required|int|exists:hair_loss_types,id',
            'country_id' => 'required|int|exists:countries,id',
            'language_key' => 'required|string|max:2|exists:languages,key',
            'how_long_have_you_experienced_hair_loss_for' => 'required|int|max:120',
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

