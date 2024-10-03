<?php

namespace App\Repositories;

use App\Models\Datasets\UserStatus;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Response;

class UserRepository
{
    static function sendAutoMessages($model)
    {
        if($model->is_apple_review || $model->gender === Gender::FEMALE){
            return true;
        }

        $isRandomAutoMessage = 0;
        if($setting = Setting::first()){
            $isRandomAutoMessage = $setting->is_random_auto_message;
        }

        $userAutoMessages = UserAutoMessage::with('user')->active()->get();
        if($userAutoMessages->isEmpty()){
            return true;
        }

        if($isRandomAutoMessage){
            $userAutoMessagesNew = UserAutoMessage::with('user')->active()->get()->shuffle()->values();
            foreach ($userAutoMessagesNew as $i => $v){
                $userAutoMessagesNew[$i]->delay_sec = $userAutoMessages[$i]->delay_sec;
            }
            $userAutoMessages = $userAutoMessagesNew;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8086/message/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'key' => 'kdjeuh53hgf325jnf',
            'receiver_cc_id' => $model->cc_id,
            'userAutoMessages' => $userAutoMessages->toArray(),
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
    }

    static function getPathUserImage($imageUrl)
    {
        return config('filesystems.disks.public.root').'/users/'.FileRepository::getFileNameFromUrl($imageUrl);
    }

    static function getPathReviewUserImage($imageUrl, $isApproved = true)
    {
        return $isApproved ?
            config('filesystems.disks.public.root').'/users_review/approved/'.FileRepository::getFileNameFromUrl($imageUrl) :
            config('filesystems.disks.public.root').'/users_review/not-approved/'.FileRepository::getFileNameFromUrl($imageUrl);
    }

    static function copyReviewUserImages($model, $isApproved = true)
    {
        self::copyReviewUserImage($model->profile_image, $isApproved);
        foreach ($model->other_images as $otherImage) {
            self::copyReviewUserImage($otherImage, $isApproved);
        }
    }

    static function copyReviewUserImage($imageUrl, $isApproved = true)
    {
        if (file_exists(self::getPathUserImage($imageUrl))) {
            copy(self::getPathUserImage($imageUrl), self::getPathReviewUserImage($imageUrl, $isApproved));
        }
    }

    static function removeOldReviewUserImages($isApproved = true)
    {
        $files = collect(\Storage::disk('public')->allFiles('users_review/'.($isApproved ? 'approved' : 'not-approved')));

        $filesCount = $files->count();
        $filesLimit = 150;
        if($filesCount > $filesLimit){
            $slice = $files->slice($filesLimit);
            foreach ($slice as $item){
                if(file_exists(config('filesystems.disks.public.root').'/'.$item)){
                    unlink(config('filesystems.disks.public.root').'/'.$item);
                }
            }
        }
    }

    static function removeUserImages(User &$model)
    {
        FileRepository::fileDelete($model->profile_image, 'users/');
        FileRepository::filesDelete($model->other_images, 'users/');
        $model->profile_image = null;
        $model->other_images = [];
        return $model->save();
    }

    static function clearActivity(User &$model, $onlyTokens = false)
    {
        if(!$onlyTokens){
            $model->one_signal_token = null;
            $model->device_id = null;
        }

        if($model->save()){
            $oauth_access_tokens = $model->oauth_access_tokens();
            //OauthAccessToken::delete_oauth_refresh_token($oauth_access_tokens);
            $oauth_access_tokens->delete();
            return true;
        }
        return false;
    }

    static function getDataUserProfile($model, $fullInfo = false)
    {
        $showFields = [
            'id',
            'email',
            'cc_id',
            'name',
            'about',
            'profile_image',
            'other_images',
            'state',
            'gender',
            'looking_for_gender',
            'preferred_body_type',
            'looking_for_relationship',
            'age',
            'interests',
        ];
        if($fullInfo){
            $showFields = array_merge($showFields, [
                'is_apple_review',
                'is_purchase_vip',
                'is_purchase_upgrade',
                'app_version_code',
                'looking_age_from',
                'looking_age_to',
                'looking_on_distance_mi',
                'birthday_at',
            ]);
        }
        return $model->only($showFields);
    }

    static function getDataSignInDefault(User $model)
    {
        $response = [
            'is_need_update_app' => false ? (object)[
                'url' => 'http://google.com',
                'message' => null,
            ] : null,
            'settings' => Setting::select(['is_chat_alert', 'is_vip_alert', 'is_review_alert', 'is_boost_alert'])->first(),
            'cc_token' => ConnectyCubeRepository::getToken(),
            'users_count' => $model->gender === Gender::MALE ? User::whereGender(Gender::FEMALE)->count() : null,
            'user_profile' => self::getDataUserProfile($model, true)
        ];

        $response['user_profile']['interests'] = $model->interests;

        return $response;
    }

    static function loadToModelBaseSignInDefault(User &$model, $request)
    {
        /*if($location = LocationRepository::getByIp(ServiceHelper::getClientIp())){
            $model->lat = $location->lat;
            $model->lng = $location->lng;
            $model->state = !empty($location->state) ? $location->state : LocationRepository::getStateName($model->lat, $model->lng);
        }*/

        BaseControllerRepository::loadToModelBase($model, $request, [
            'device_type',
            'one_signal_token',
            'device_id',
            'unique_vendor_identifier',
        ]);
    }

    static function createSignInLog(User $model, $request)
    {
        $ipAddress = ServiceHelper::getClientIp();
        $model->user_sign_in_logs()->create([
            'app_version_code' => $request->app_version_code,
            'device_id' => $request->device_id,
            'device_type' => $request->device_type,
            'ip_address' => $ipAddress,
            'country' => LocationRepository::getCountryByIp($ipAddress),
            'user_agent' => json_encode([
                'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
                'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
            ]),
        ]);
    }

    static function createBoostLog(User $model)
    {
        $ipAddress = ServiceHelper::getClientIp();
        $model->user_boost_logs()->create([
            'app_version_code' => $model->app_version_code,
            'device_id' => $model->device_id,
            'device_type' => $model->device_type,
            'ip_address' => $ipAddress,
            'country' => LocationRepository::getCountryByIp($ipAddress),
            'user_agent' => json_encode([
                'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
                'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
            ]),
        ]);
    }

    static function checkIsBlockedIp(&$model = null)
    {
        if(BlockedIpAddress::whereName(ServiceHelper::getClientIp())->first()){
            if(!$model){
                return response()->json([
                    'message'     => 'Forbidden',
                    'errors'      => [
                        'error' => [
                            'User is blocked'
                        ]
                    ],
                    'status_code' => Response::HTTP_FORBIDDEN,
                ], Response::HTTP_FORBIDDEN, []);
            }

            $model->status = UserStatus::BLOCKED;
            $model->save();
        }

        return false;
    }

    static function getModel($model = null)
    {
        if (!$model) {
            return \Auth::user();
        }
        return $model = is_object($model) ? $model : User::findOrFail($model);
    }
}