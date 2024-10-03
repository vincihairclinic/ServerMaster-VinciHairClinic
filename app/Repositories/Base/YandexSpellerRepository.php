<?php

namespace App\Repositories\Base;

class YandexSpellerRepository
{
    //dd(YandexSpellerRepository::check(['столd cnjbn', 'вороті', 'desk'], AppConf::$originLang, AppConf::$translateLang));

    static function check($text, $lang = '', $checkTranslateLang = false, $getAllVariants = false)
    {
        if(empty($text)){
            return $text;
        }

        if($checkTranslateLang){
            if(is_array($text)){
                $text = TranslateRepository::translateMulti($text, $lang, $checkTranslateLang);
                foreach ($text as $i => $v){
                    $text[$i] = StringRepository::clearSpace($text[$i]);
                }
            }else{
                $text = TranslateRepository::translate($text, $lang, $checkTranslateLang);
                $text = StringRepository::clearSpace($text);
            }
        }

        if(is_array($text)){
            $response = self::sendCurlMulti($text, $lang);
            if(!empty($response)){
                foreach ($text as $i => $v){
                    $text[$i] = self::processingCurlResponse($text[$i], $response[$i], $getAllVariants);
                }
            }
        }else{
            $response = self::sendCurl($text, $lang);
            $text = self::processingCurlResponse($text, $response, $getAllVariants);
        }
        return $text;
    }
    
    static function processingCurlResponse($text, $response, $getAllVariants = false)
    {
        $allVariants = [];
        if(!empty($response)){
            foreach ($response as $item){
                if(!empty($item->word) && !empty($item->s) && is_array($item->s) && !empty($item->s[0])){
                    if($getAllVariants){
                        foreach ($item->s as $itemVariant){
                            $allVariants[] = str_replace($item->word, $itemVariant, $text);
                        }
                    }
                    $text = str_replace($item->word, $item->s[0], $text);
                }
            }
        }
        if($getAllVariants){
            return !empty($allVariants) ? $allVariants : [$text];
        }
        return $text;
    }

    static function sendCurl($text = '', $lang = '', $recall = 0)
    {
        $response = [];

        if(empty($text)){
            return $response;
        }

        if(!empty($lang) && !$recall){
            $lang = 'lang='.$lang.'&';
        }

        try{
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://speller.yandex.net/services/spellservice.json/checkText?'.$lang.'text='.urlencode($text));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);

            curl_close($ch);

            $response = json_decode($response);
        } catch (\Exception $e) {
            $response = null;
        }

        if($response === null && $recall <= 10 && app()->runningInConsole()){
            sleep(1);
            $response = self::sendCurl($text, $lang, $recall+1);
        }

        if(empty($response)){
            $response = [];
        }

        return $response;
    }

    static function sendCurlMulti($texts = [], $lang = '', $recall = 0)
    {
        if(empty($texts)){
            return [];
        }

        if(!empty($lang) && !$recall){
            $lang = 'lang='.$lang.'&';
        }

        $results = [];
        $mh = curl_multi_init();
        $conn = [];
        foreach ($texts as $i => $text) {
            $conn[$i] = curl_init();
            $results[$i] = null;

            curl_setopt($conn[$i], CURLOPT_URL, 'https://speller.yandex.net/services/spellservice.json/checkText?'.$lang.'text='.urlencode($text));
            curl_setopt($conn[$i], CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($conn[$i], CURLOPT_FOLLOWLOCATION, true);

            curl_multi_add_handle($mh, $conn[$i]);
        }

        $error = false;

        do {
            try{
                $status = curl_multi_exec($mh, $active);
            } catch (\Exception $e) {
                $error = true;
                break;
            }
        } while ($active && $status == CURLM_OK);

        if(!$error){
            foreach ($texts as $i => $text) {
                $response = curl_multi_getcontent($conn[$i]);
                curl_close($conn[$i]);
                $conn[$i] = null;
                if(!empty($response)){
                    try{
                        $response = json_decode($response);
                        $results[$i] = $response;
                    } catch (\Exception $e) {
                        $error = true;
                        $results[$i] = null;
                    }
                }
            }
        }else{
            foreach ($conn as $i => $item){
                if(!empty($conn[$i])){
                    try{
                        curl_close($conn[$i]);
                        $conn[$i] = null;
                    } catch (\Exception $e) {}
                }
            }
        }

        if($error){
            if($recall <= 10 && app()->runningInConsole()){
                sleep(1);
                $results = self::sendCurlMulti($texts, $lang, $recall+1);
            }else{
                $results = [];
            }
        }


        return $results;
    }
}