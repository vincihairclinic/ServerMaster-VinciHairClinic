<?php

namespace App\Repositories\Base;

class FcmRepository
{
    static function send($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('services.fcm.url'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: key=' . config('services.fcm.api_key'),
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(self::requestBody($data)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        return $response->success ? true : false;
    }

    protected static function requestBody($data)
    {
        $data['type'] = !empty($data['type']) ? $data['type'] : 'message';

        if ($data['type'] == 'message') {

            if($data['click_action']){
                $data['click_action'] = route('messenger.index') . '?dialog=' . $data['candidate_job_id'];
            }else{
                $data['click_action'] = route('messenger.index');
            }

            return [
                'to' => $data['fcm_token'],
                'notification' => [
                'title'                  => $data['from'],
                    'body'               => $data['message'],
                    'icon'               => asset('images/logo_nploy.png'),
                    'show_in_foreground' => "true",
                    'click_action'       => $data['click_action'],
                ],
                'data' => [
                    'candidate_job_id' => $data['candidate_job_id'],
                    'sender_id'        => $data['sender_id'],
                    'created_at'       => $data['created_at'],
                    'type'             => $data['type'],
                ],
            ];
        } else {
            return [
                'to' => $data['fcm_token'],
                'notification' => [
                    'title'              => $data['title'],
                    'body'               => $data['body'],
                    'icon'               => asset('images/logo_nploy.png'),
                    'show_in_foreground' => "true",
                ],
                'data' => [
                    'type' => $data['type'],
                ],
            ];
        }
    }

}