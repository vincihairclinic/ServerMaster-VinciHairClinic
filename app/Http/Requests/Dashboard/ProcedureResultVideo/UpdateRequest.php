<?php

namespace App\Http\Requests\Dashboard\ProcedureResultVideo;

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
            'language_key'  => 'required|string|exists:languages,key',
            'procedure_result_id'  => 'required|string|exists:procedure_results,id',
            'name'  => 'required|string|min:2|max:150',
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
