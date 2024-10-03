<?php

namespace App\Http\Requests\Dashboard\BookInformation;

use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;

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
            'name'  => 'required|string|min:2|max:150',
            'content'  => 'required|string|min:2',
            'language_key'  => 'required|string|exists:languages,key',
            'book_id'  => 'required|int|exists:books,id',
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

