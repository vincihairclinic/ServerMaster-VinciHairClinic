<?php

namespace App\Http\Requests\Dashboard\UserSimulationRequest;

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
            'hair_front_image'  => 'required_with:image_deleted_hair_front_image',
            'hair_side_image'  => 'required_with:image_deleted_hair_side_image',
            'hair_back_image'  => 'required_with:image_deleted_hair_back_image',
            'hair_top_image'  => 'required_with:image_deleted_hair_top_image',
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
