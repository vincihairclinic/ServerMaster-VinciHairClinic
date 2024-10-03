<?php

namespace App\Http\Requests\Dashboard\Book;

use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;

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
            'pre_name_en'  => 'required|string|min:2|max:150',
            'pre_name_pt'  => 'required|string|min:2|max:150',
            'pre_content_en'  => 'required|string|min:2',
            'pre_content_pt'  => 'required|string|min:2',
            'post_name_en'  => 'required|string|min:2|max:150',
            'post_name_pt'  => 'required|string|min:2|max:150',
            'post_content_en'  => 'required|string|min:2',
            'post_content_pt'  => 'required|string|min:2',
            'image' => 'required_with:images_deleted_image',
            'pre_image' => 'required_with:images_deleted_pre_image',
            'post_image' => 'required_with:images_deleted_post_image',
            'video_en' => 'required_with:urls_deleted_video_en',
            'video_pt' => 'required_with:urls_deleted_video_pt',
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
