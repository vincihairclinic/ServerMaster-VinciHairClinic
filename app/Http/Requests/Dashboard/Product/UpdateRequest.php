<?php

namespace App\Http\Requests\Dashboard\Product;

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
            'name_en'  => 'required|string|min:2|max:150',
            'name_pt'  => 'required|string|min:2|max:150',
            'about_en'  => 'required|string|min:2',
            'about_pt'  => 'required|string|min:2',
            'video_name_en'  => 'nullable|string|max:150',
            'video_name_pt'  => 'nullable|string|max:150',
//            'video_en' => 'required_with:urls_deleted_video_en',
//            'video_pt' => 'required_with:urls_deleted_video_pt',
            'image' => 'required_with:images_deleted_image',
            'product_category_id'  => 'required|int|exists:product_categories,id',
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
