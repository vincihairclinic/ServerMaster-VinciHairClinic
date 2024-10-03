<?php

namespace App\Http\Requests\Base;

use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;

class MultiplayerCreateRequest extends FormRequest
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
            'scheduled_at' => 'required|date_format:"Y-m-d H:i:s"',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'nullable|integer|distinct|exists:users,id',
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

