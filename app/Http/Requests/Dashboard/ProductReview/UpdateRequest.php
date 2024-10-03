<?php

namespace App\Http\Requests\Dashboard\ProductReview;

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
            'language_key'  => 'required|string|exists:languages,key',
            'product_id'  => 'required|int|exists:products,id',
            'video' => 'required_with:urls_deleted_video',
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
