<?php

namespace App\Repositories\Base;


use App\Application;

trait CurlRepository
{
    static function sendCurl($urlPath, $urlParams = [], $method = 'get', $isJson = false, $headers = [], $cookie = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.96 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
        curl_setopt($ch, CURLOPT_URL, $urlPath. ($method == 'get' ? (!empty($urlParams) ? '?'.http_build_query($urlParams) : '') : ''));

        if($method == 'post'){
            curl_setopt($ch, CURLOPT_POST, true);
            try {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $urlParams);
            }catch (\Exception $e){
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($urlParams));
            }
        }
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if(!empty($cookie)){
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        if($proxy = Application::getProxy()){
            curl_setopt($ch, CURLOPT_PROXY, $proxy->ip);
            curl_setopt($ch, CURLOPT_PROXYPORT, $proxy->port);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy->pwd);
        }

        try{
            $response = curl_exec($ch);
            if(curl_getinfo($ch)['http_code'] != 200){
                $response = null;
            }
            curl_close($ch);
            if($isJson && !empty($response)){
                $response = json_decode($response);
            }
        } catch (\Exception $e) {
            $response = null;
        }

        return $response;
    }
}
