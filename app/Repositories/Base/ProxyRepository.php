<?php

namespace App\Repositories\Base;


use App\Models\Arraysets\ProxyFreeDefault;

class ProxyRepository
{

    static $proxyList = null;
    static $proxyConnectTimeout = 0;

    static $useOnlyDefaultIps = true;

    static function updateProxyList($id, $key, $value)
    {
        if (!self::$proxyList || empty(self::$proxyList) || self::$proxyList->where('is_true', 1)->isEmpty()) {
            self::$proxyList = ProxyRepository::parseIpList();
        }else{
            self::$proxyList = self::$proxyList->map(function ($item) use ($id, $key, $value){
                if($item['id'] == $id){
                    $item[$key] = $value;
                }
                return $item;
            });
        }
    }

    static function getNextProxy()
    {
        if (!self::$proxyList || empty(self::$proxyList) || self::$proxyList->where('is_true', 1)->isEmpty()) {
            self::$proxyList = ProxyRepository::parseIpList();
        }

        $proxyList = self::$proxyList->where('is_true', 1);
        if ($proxyList->where('is_used', 0)->isEmpty()) {
            $proxyList = $proxyList->map(function ($item, $key) {
                $item['is_used'] = 0;
                return $item;
            });
            self::$proxyList = $proxyList;
        }

        $proxy = $proxyList->where('is_used', 0)->random();

        self::updateProxyList($proxy['id'], 'is_used', 1);

        return !empty($proxy) ? $proxy : false;
    }

    static function parseElement($text, $start, $end)
    {
        $text = explode($start, $text);
        if(isset($text[0]) && isset($text[1])) {
            unset($text[0]);
            array_filter($text);
            foreach ($text as $i => $item) {
                $text[$i] = explode($end, $text[$i])[0];
            }
        }

        return is_array($text) ? $text : [];
    }

    static function getIpList($response, $isSocks = false)
    {
        $response = self::parseElement($response, '<tbody>', '</tbody>')[1];
        $response = self::parseElement($response, '<tr', '</tr>');

        $ip_matches = [];
        foreach ($response as $tr){
            $td = self::parseElement($tr, '<td', '</td>');

            if(count($td) == 8){
                if($isSocks){
                    $ip_match = str_replace(">", "", $td[5]).'://';
                }else{
                    $ip_match = (str_replace(" class='hx'>", "", $td[7]) == 'yes') ? 'https://' : 'http://';
                }
                $ip_match .= str_replace('>', '', $td[1]);
                $ip_match .= ':'.str_replace('>', '', $td[2]);
                $ip_matches[] = $ip_match;
            }
        }

        return $ip_matches;
    }

    static function parseIpList($recall = 0)
    {
        $response = null;

        if(!$recall){
            self::$proxyConnectTimeout = self::$proxyConnectTimeout + 1;
        }

        try{
            $ip_matches = ProxyFreeDefault::$array;

            if(!self::$useOnlyDefaultIps){
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://free-proxy-list.net/');
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $response = curl_exec($ch);
                $ip_matches = array_merge($ip_matches, self::getIpList($response));

                //----------------------

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.us-proxy.org/');
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $response = curl_exec($ch);
                $ip_matches = array_merge($ip_matches, self::getIpList($response));

                //----------------------

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.sslproxies.org/');
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $response = curl_exec($ch);
                $ip_matches = array_merge($ip_matches, self::getIpList($response));


                //----------------------

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.socks-proxy.net/');
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $response = curl_exec($ch);
                $ip_matches = array_merge($ip_matches, self::getIpList($response, true));
            }


            $response = array_values(array_unique(array_filter($ip_matches)));
        } catch (\Exception $e) {
            sleep(2);
            if(!$recall || $recall < 10) {
                $response = self::parseIpList($recall+1);
            }
        }

        if(empty($response) || !is_array($response)){
            sleep(2);
            if(!$recall || $recall < 10) {
                $response = self::parseIpList($recall+1);
            }
        }else{
            foreach ($response as $i => $url){
                $response[$i] = [
                    'id' => $i,
                    'url' => $url,
                    'is_true' => 1,
                    'is_used' => 0,
                ];
            }
        }

        return collect($response);
    }
}