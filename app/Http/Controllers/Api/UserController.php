<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\User\EditRequest;
use App\Models\BookConsultation;
use App\Models\Clinic;
use App\Models\Country;
use App\Models\Datasets\UserRole;
use App\Models\HairLossType;
use App\Models\Language;
use App\Models\Procedure;
use App\Models\User;
use App\Models\UserSimulationRequest;
use App\Repositories\Base\BlobRepository;
use App\Repositories\Base\FileRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends ApiController
{
    public function index()
    {
        User::setStaticVisible(User::$publicSelfColumns);
        Procedure::setStaticVisible(Procedure::$publicColumns);
        Clinic::setStaticVisible(Clinic::$publicColumns);
        HairLossType::setStaticVisible(HairLossType::$publicColumns);
        Country::setStaticVisible(Country::$publicColumns);
        Language::setStaticVisible(Language::$publicColumns);

        if ($model = User::with([
            'procedures',
            'clinic',
            'hair_loss_type',
            'hair_type',
            'country',
            'language',
        ])->where('id', Auth::id())->whereIn('role_id', [UserRole::USER])->first()) {
            return $this->respond([
                'user' => $model,
            ]);
        }

        return $this->respondNotFound();
    }

    public function simulationRequestsCreate(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email:rfc,dns|min:6|max:100',
            'full_name'  => 'nullable|string|min:2|max:100',
            'phone_number'  => 'nullable|string|min:10|max:16',
            'country_id'  => 'nullable|integer|exists:countries,id',
            'url_image'  => 'nullable|string|min:2|max:255',
            'simulation_request_type_id'  => 'nullable|integer|exists:user_simulation_requests,id',
            'url_hair_front_image'  => 'nullable|string|min:2|max:255',
            'url_hair_side_image'  => 'nullable|string|min:2|max:255',
            'url_hair_back_image'  => 'nullable|string|min:2|max:255',
            'url_hair_top_image'  => 'nullable|string|min:2|max:255',
        ]);

        $model = new UserSimulationRequest();
        $model->user_id = !empty(Auth::user()) ? Auth::id() : null;
        $model->email = $request->email;
        $model->full_name = $request->full_name;
        $model->phone_number = $request->phone_number;
        $model->country_id = $request->country_id;
        $model->url_image = $request->url_image;
        $model->url_hair_front_image = !$request->has('url_hair_front_image') ? Auth::user()->url_hair_front_image : $request->url_hair_front_image;
        $model->url_hair_side_image = !$request->has('url_hair_side_image') ? Auth::user()->url_hair_side_image : $request->url_hair_side_image;
        $model->url_hair_back_image = !$request->has('url_hair_back_image') ? Auth::user()->url_hair_back_image : $request->url_hair_back_image;
        $model->url_hair_top_image = !$request->has('url_hair_top_image') ? Auth::user()->url_hair_top_image : $request->url_hair_top_image;
        $model->simulation_request_type_id = $request->simulation_request_type_id;
        $model->save();

        return $this->respondSuccess();
    }

    public function bookningConsultationCreate()
    {
        $user = Auth::user();
        /*$user->is_book_consultation = 1;
        $user->is_book_consultation_checked = 0;
        $user->save();*/

        $book_consultation = new BookConsultation();
        $book_consultation->user_id = Auth::id();
        $book_consultation->email = $user->email;
        $book_consultation->is_email_verified = $user->is_email_verified;
        $book_consultation->created_at = $user->created_at;
        $book_consultation->updated_at = $user->updated_at;
        $book_consultation->gender_id = $user->gender_id;
        $book_consultation->hair_front_image = $user->hair_front_image;
        $book_consultation->hair_side_image = $user->hair_side_image;
        $book_consultation->hair_back_image = $user->hair_back_image;
        $book_consultation->hair_top_image = $user->hair_top_image;
        $book_consultation->full_name = $user->full_name;
        $book_consultation->age = $user->age;
        $book_consultation->date_of_birth = $user->date_of_birth;
        $book_consultation->phone_number = $user->phone_number;
        $book_consultation->clinic_id = $user->clinic_id;
        $book_consultation->hair_loss_type_id = $user->hair_loss_type_id;
        $book_consultation->hair_type_id = $user->hair_type_id;
        $book_consultation->country_id = $user->country_id;
        $book_consultation->language_key = $user->language_key;
        $book_consultation->does_your_family_suffer_from_hereditary_hair_loss = $user->does_your_family_suffer_from_hereditary_hair_loss;
        $book_consultation->how_long_have_you_experienced_hair_loss_for = $user->how_long_have_you_experienced_hair_loss_for;
        $book_consultation->would_you_like_to_get_in_touch_with_you = $user->would_you_like_to_get_in_touch_with_you;
        $book_consultation->is_book_consultation_checked = 0;
        $book_consultation->is_book_consultation = 1;
        $book_consultation->is_request_contact_from_this_clinic_checked = $user->is_request_contact_from_this_clinic_checked;
        $book_consultation->is_show_news_and_updates_notification = $user->is_show_news_and_updates_notification;
        $book_consultation->is_show_promotions_and_offers_notification = $user->is_show_promotions_and_offers_notification;
        $book_consultation->is_show_insights_and_tips_notification = $user->is_show_insights_and_tips_notification;
        $book_consultation->is_show_new_articles_notification = $user->is_show_new_articles_notification;
        $book_consultation->is_show_requests_and_tickets_notification = $user->is_show_requests_and_tickets_notification;
        $book_consultation->save();


        $book_consultation->procedures()->sync($user->procedures->pluck('id'));

        return $this->respondSuccess();
    }

    public function edit(EditRequest $request)
    {
        User::setStaticVisible(User::$publicSelfColumns);
        Procedure::setStaticVisible(Procedure::$publicColumns);
        Clinic::setStaticVisible(Clinic::$publicColumns);
        HairLossType::setStaticVisible(HairLossType::$publicColumns);
        Country::setStaticVisible(Country::$publicColumns);
        Language::setStaticVisible(Language::$publicColumns);

        $model = User::where('id', Auth::id())->first();

        $model->onesignal_token = !$request->has('onesignal_token') ? Auth::user()->onesignal_token : $request->onesignal_token;
        $model->version_app = !$request->has('version_app') ? Auth::user()->version_app : $request->version_app;
        $model->app_state = !$request->has('app_state') ? Auth::user()->app_state : $request->app_state;
        $model->updated_at = Carbon::now();
        //user_personal_details
        $model->gender_id = !$request->has('gender_id') ? Auth::user()->gender_id : $request->gender_id;
        $model->url_hair_front_image = !$request->has('url_hair_front_image') ? Auth::user()->url_hair_front_image : $request->url_hair_front_image;
        $model->url_hair_side_image = !$request->has('url_hair_side_image') ? Auth::user()->url_hair_side_image : $request->url_hair_side_image;
        $model->url_hair_back_image = !$request->has('url_hair_back_image') ? Auth::user()->url_hair_back_image : $request->url_hair_back_image;
        $model->url_hair_top_image = !$request->has('url_hair_top_image') ? Auth::user()->url_hair_top_image : $request->url_hair_top_image;
        $model->full_name = !$request->has('full_name') ? Auth::user()->full_name : $request->full_name;
        /*if($request->has('age') && !empty($request->age)){
            $model->age = $request->age;
        }*/
        if($request->has('date_of_birth') && !empty($request->date_of_birth)){
            $model->date_of_birth = Carbon::createFromFormat('Y-m-d', $request->date_of_birth)->format('Y-m-d');
        }

        $model->phone_number = !$request->has('phone_number') ? Auth::user()->phone_number : $request->phone_number;

        if($request->has('procedure_ids')){
            $model->procedures()->sync($request->procedure_ids);
        }

        $model->clinic_id = !$request->has('clinic_id') ? Auth::user()->clinic_id : $request->clinic_id;
        $model->hair_loss_type_id = !$request->has('hair_loss_type_id') ? Auth::user()->hair_loss_type_id : $request->hair_loss_type_id;
        $model->hair_type_id = !$request->has('hair_type_id') ? Auth::user()->hair_type_id : $request->hair_type_id;
        $model->country_id = !$request->has('country_id') ? Auth::user()->country_id : $request->country_id;
        $model->language_key = !$request->has('language_key') ? Auth::user()->language_key : $request->language_key;
        $model->does_your_family_suffer_from_hereditary_hair_loss = !$request->has('does_your_family_suffer_from_hereditary_hair_loss') ? Auth::user()->does_your_family_suffer_from_hereditary_hair_loss : $request->does_your_family_suffer_from_hereditary_hair_loss;
        $model->how_long_have_you_experienced_hair_loss_for = !$request->has('how_long_have_you_experienced_hair_loss_for') ? Auth::user()->how_long_have_you_experienced_hair_loss_for : $request->how_long_have_you_experienced_hair_loss_for;
        $model->would_you_like_to_get_in_touch_with_you = !$request->has('would_you_like_to_get_in_touch_with_you') ? Auth::user()->would_you_like_to_get_in_touch_with_you : $request->would_you_like_to_get_in_touch_with_you;
        //user_settings
        $model->is_show_news_and_updates_notification = !$request->has('is_show_news_and_updates_notification') ? Auth::user()->is_show_news_and_updates_notification : $request->is_show_news_and_updates_notification;
        $model->is_show_promotions_and_offers_notification = !$request->has('is_show_promotions_and_offers_notification') ? Auth::user()->is_show_promotions_and_offers_notification : $request->is_show_promotions_and_offers_notification;
        $model->is_show_insights_and_tips_notification = !$request->has('is_show_insights_and_tips_notification') ? Auth::user()->is_show_insights_and_tips_notification : $request->is_show_insights_and_tips_notification;
        $model->is_show_new_articles_notification = !$request->has('is_show_new_articles_notification') ? Auth::user()->is_show_new_articles_notification : $request->is_show_new_articles_notification;
        $model->is_show_requests_and_tickets_notification = !$request->has('is_show_requests_and_tickets_notification') ? Auth::user()->is_show_requests_and_tickets_notification : $request->is_show_requests_and_tickets_notification;


        if($request->has('email') && !empty($request->email)){
            $model->email = $request->email;
        }
        if($request->has('password') && !empty($request->password)){
            $model->password = Hash::make($request->password);
        }

        if($model->save()){
            if ($model = User::with([
                'procedures',
                'clinic',
                'hair_loss_type',
                'hair_type',
                'country',
                'language',
            ])->where('id', $model->id)->first()) {
                return $this->respond([
                    'user' => $model,
                ]);
            }
        }

        return $this->respondError();
    }

    public function deleteAccount()
    {
        if($model = User::where('id', Auth::id())->first()){
            if(!empty($model->hair_front_image)){
                BlobRepository::fileDelete($model->hair_front_image, 'image');
            }
            if(!empty($model->hair_side_image)){
                BlobRepository::fileDelete($model->hair_side_image, 'image');
            }
            if(!empty($model->hair_back_image)){
                BlobRepository::fileDelete($model->hair_back_image, 'image');
            }
            if(!empty($model->hair_top_image)){
                BlobRepository::fileDelete($model->hair_top_image, 'image');
            }

            $model->hair_front_image = null;
            $model->hair_side_image = null;
            $model->hair_back_image = null;
            $model->hair_top_image = null;
            $model->email = 'deleted_'.$model->id.'_'.$model->email;
            $model->full_name = 'deleted';
            $model->deleted_at = Carbon::now();
            return $model->save() ? $this->respondSuccess() : $this->respondError();
        }

        return $this->respondSuccess();
    }

}
