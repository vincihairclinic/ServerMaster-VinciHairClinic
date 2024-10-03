<?php

namespace App\Http\Requests\Dashboard\ProcedureResultImage;

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
            'procedure_result_id'  => 'required|string|exists:procedure_results,id',
            'befor_image' => 'required_with:urls_deleted_befor_image',
            'after_image' => 'required_with:urls_deleted_after_image',
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
