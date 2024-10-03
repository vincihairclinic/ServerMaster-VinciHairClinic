<?php

namespace App\Repositories\Base;

use App\Models\Log;

class OneSignalRepository
{
    static function send($onesignal_tokens = [], $title = null, $message = null, $dataToSend = [], $pusher_enable = true)
    {
        if (empty($onesignal_tokens)) {
            return false;
        }

        $onesignal_tokens_origin = $onesignal_tokens;
        $onesignal_tokens = [];
        foreach ($onesignal_tokens_origin as $v){
            if(preg_match('/^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$/', $v)){
                $onesignal_tokens[] = $v;
            }
        }

        if($pusher_enable){
            //pusher_dump([$dataToSend, $onesignal_tokens]);
        }

        if (empty($onesignal_tokens)) {
            return false;
        }

        $onesignal_app_id = config('services.onesignal.app_id');
        $onesignal_api_key = config('services.onesignal.api_key');

        if(empty($onesignal_app_id) || empty($onesignal_api_key)){
            return false;
        }

        $data = [
            'app_id'             => $onesignal_app_id,
            'include_player_ids' => $onesignal_tokens,
            'data'               => $dataToSend,
            'contents' => ["en" => $message],
            'headings' => ["en" => $title],
        ];

        /*if(!empty($ios_attachment)){
            $data['ios_attachments'] = (object)[
                'id' => $ios_attachment,
            ];
        }*/

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $onesignal_api_key,
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $r = curl_exec($ch);

        try {
            if(!empty($r)){
                if(!is_object($r)){
                    $r = json_decode($r);
                }

                if(is_object($r) && !empty($r->errors)){
                    $r->title = $title;
                    $r->message = $message;
                    $r->dataToSend = $dataToSend;
                    
                    Log::add([
                        'description' => json_encode($r)
                    ], 'OneSignal');
                }
            }
        }catch (\Exception $e){
            Log::add([
                'description' => $e->getMessage()
            ], 'OneSignal_catch');
        }
        curl_close($ch);


        return true;
    }
}