<?php

namespace App\Http\Requests\Dashboard\Country;

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
            'name'  => 'required|string|min:2|max:150',
            'phone_code'  => 'required|string|min:2|max:10',
            'host'  => 'required|string|min:2|max:100',
            'shop_url'  => 'required|string|min:2|max:100',
            'flag_image' => 'required_with:images_deleted_flag_image',
            'territory_id'  => 'required|int|exists:territories,id',
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
