<?php

namespace App\Http\Requests\Api\User;

use App\Models\Datasets\Gender;
use App\Repositories\Base\RequestRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditRequest extends FormRequest
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
            'onesignal_token'  => 'nullable|string|min:2|max:100',
            'version_app'  => 'nullable|string|min:2|max:50',
            'app_state'  => 'nullable|string|min:2|max:100',
            //user_personal_details
            'gender_id' => 'nullable|integer|min:0|in:'.Gender::implodeIds(','),
            'url_hair_front_image'  => 'nullable|string|min:2|max:255',
            'url_hair_side_image'  => 'nullable|string|min:2|max:255',
            'url_hair_back_image'  => 'nullable|string|min:2|max:255',
            'url_hair_top_image'  => 'nullable|string|min:2|max:255',
            'full_name'  => 'nullable|string|min:2|max:100',
            //'age'  => 'nullable|integer|min:1|max:150',
            'date_of_birth' => 'nullable|date_format:"Y-m-d"',
            'phone_number'  => 'nullable|string|min:10|max:16',
            'procedure_ids' => 'nullable|array',
            'procedure_ids.*' => 'required|integer|exists:procedures,id',
            'clinic_id'  => 'nullable|integer|exists:clinics,id',
            'hair_loss_type_id'  => 'nullable|integer|exists:hair_loss_types,id',
            'hair_type_id'  => 'nullable|integer|exists:hair_types,id',
            'country_id'  => 'nullable|integer|exists:countries,id',
            'language_key'  => 'nullable|string|exists:languages,key',
            'does_your_family_suffer_from_hereditary_hair_loss'  => 'nullable|bool',
            'how_long_have_you_experienced_hair_loss_for'  => 'nullable|integer|min:0',
            'would_you_like_to_get_in_touch_with_you'  => 'nullable|bool',
            //user_settings
            'is_show_news_and_updates_notification'  => 'nullable|bool',
            'is_show_promotions_and_offers_notification'  => 'nullable|bool',
            'is_show_insights_and_tips_notification'  => 'nullable|bool',
            'is_show_new_articles_notification'  => 'nullable|bool',
            'is_show_requests_and_tickets_notification'  => 'nullable|bool',

            'password'    => 'nullable|string|min:6|max:100',
            'email' => ['nullable', 'email:rfc,dns', 'min:6', 'max:100', Rule::unique('users')->ignore(\Auth::id())],
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

