<?php

namespace App\Repositories\Base;

use App\AppConf;

class TranslateRepository
{
    static function translateHtmlText($str, $langTo = null, $langFrom = null)
    {
        $str = self::clearHtmlTextTotranslate($str);
        if(empty($str)){
            return null;
        }

        $langTo = !empty($langTo) ? $langTo : AppConf::$lang;
        $langFrom = !empty($langFrom) ? $langFrom : 'ru';
        if(empty($str)){
            return $str;
        }
        $translatedTexts = [];
        $j = 0;

        $str = str_replace('<br>', '[2239832661]', $str);
        $str = preg_replace_callback('#<(li|p|h2|h3|h4|h5|h6)>(.+)</(li|p|h1|h2|h3|h4|h5|h6)>#iU', function ($matches) use (&$translatedTexts, &$j) {
            $translatedTexts[$j] = $matches[2];
            $j++;
            return '<'.$matches[1].'>' . $matches[2] . '</'.$matches[3].'>';
        }, $str);

        $translatedTexts = TranslateRepository::translateMulti($translatedTexts, $langTo, $langFrom);

        $j = 0;
        $str = preg_replace_callback('#<(li|p|h2|h3|h4|h5|h6)>(.+)</(li|p|h1|h2|h3|h4|h5|h6)>#iU', function ($matches) use (&$translatedTexts, &$j) {
            $matches[2] = $translatedTexts[$j];
            $j++;

            $matches[2] = StringClearRepository::clearToAds($matches[2], false, '?,:/)/(/&', false, false);
            if(!empty($matches[2])){
                return '<'.$matches[1].'>' . $matches[2] . '</'.$matches[3].'>';
            }else{
                return '';
            }
        }, $str);

        $str = str_replace('[2239832661]', '<br>', $str);
        $str = str_replace('2239832661', '<br>', $str);

        return $str;
    }

    static function clearHtmlTextTotranslate($str)
    {
        $str = preg_replace('#<h[1-6]>(.+)</h[1-6]>#iU', '', $str);
        $str = str_replace('<ol>', '<ul>', $str);
        $str = str_replace('</ol>', '</ul>', $str);

        $str = StringClearRepository::clearSpace($str);

        if(!empty($str)){
            $str = StringClearRepository::clearStrWithBaseHtml($str, true, true);
            $texts = [];
            $j = 0;
            preg_replace_callback('#<(ul|p)>(.+)</(ul|p)>#iU', function ($matches) use (&$texts, &$j) {
                if(mb_strlen(trim($matches[2])) > 2){
                    $texts[$j] = '<'.$matches[1].'>' . $matches[2] . '</'.$matches[3].'>';
                    $j++;
                }
                return '';
            }, $str);
            $str = implode(' ', $texts);
            $str = StringClearRepository::clearSpace($str);
            $str = str_replace('<ul></ul>', '', $str);
            $str = str_replace('<ul> </ul>', '', $str);
            $str = StringClearRepository::clearSpace($str);

            foreach (['.', ',', '?', '/', ':', '&', '-'] as $v){
                $str = str_ireplace('>'.$v, '>', $str);
                $str = str_ireplace('> '.$v, '>', $str);
                $str = str_ireplace('/'.$v, '/', $str);
                $str = str_ireplace('/ '.$v, '/', $str);
            }
            $str = ParseRepository::clearEmptyTags($str);
            $str = StringClearRepository::clearSpace($str);
        }
        return $str;
    }

    //------------------------------------------------------------------------------

    static function translate($text = '', $translateTo = 'uk', $translateFrom = 'pl', $withHtml = false)
    {
        $text = StringRepository::clearSpace($text);
        if(empty($text)){
            return $text;
        }
        if(is_numeric($text)){
            return $text;
        }

        $text = preg_replace('/(\b[а-яА-яіIїЇёЁ]+\b\s)(?=.?\1)/siu', '', html_entity_decode($text));

        $result = self::send($text, $translateTo, $translateFrom);

        if($withHtml){
            $result = self::clearResponseString($result, $text);
        }else if(empty($result)){
            $result = $text;
        }

        return $result;
    }

    static function translateMulti($textes = [], $translateTo = 'uk', $translateFrom = 'pl', $withHtml = false)
    {
        if(empty($textes) && !is_array($textes)){
            return $textes;
        }

        foreach ($textes as $i => $text) {
            $textes[$i] = preg_replace('/(\b[а-яА-яіIїЇёЁ]+\b\s)(?=.?\1)/siu', '', html_entity_decode($text));
        }

        $originTextes = $textes;
        $textes = self::sendMulti($textes, $translateTo, $translateFrom);

        foreach ($originTextes as $i => $originText) {
            if(!empty($textes[$i])){
                if($withHtml){
                    $textes[$i] = self::clearResponseString($textes[$i], $originText);
                }
            }else{
                $textes[$i] = $originText;
            }
        }

        return $textes;
    }

    static function clearResponseString($str = '', $alternativeText = '')
    {
        if(empty($str)){
            return $alternativeText;
        }

        $str = html_entity_decode($str);
        $str = preg_replace('/\s\s+/', ' ', $str);

        foreach ([['?', ''], ['    ', ' '], ['   ', ' '], ['  ', ' '], ['  /  ', '/'], [' / ', '/'], [' /', '/'], ['/ ', '/'], ['...', '.'], ['..', '.'], [' .', '.'], [' .', '.'], [' .', '.'], [' -', '-'], ['<литий>', '<li>'], ['</литий>', '</li>']] as $item){
            $str = str_replace($item[0], $item[1], $str);
        }
        foreach (['ь', 'ол', 'ул', 'р', 'в', 'б', 'п', 'н1', 'н2', 'н3', 'н4', 'н5', 'н6', 'p', 'b', 'br', 'i', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'ul', 'ol'] as $item){
            $str = self::decodeHtml($str, $item);
        }
        foreach (['Li', 'Ul', 'Ol', 'lI', 'uL', 'oL'] as $item){
            $str = self::decodeHtml($str, $item, true);
        }
        foreach ([['ол', 'ol'], ['ул', 'ul'], ['Ь', 'b'], ['в', 'b'], ['р', 'p'], ['б', 'b'], ['п', 'p'], ['н1', 'h1'], ['н2', 'h2'], ['н3', 'h3'], ['н4', 'h4'], ['н5', 'h5'], ['н6', 'h6']] as $item){
            $str = self::clearTransliterationHtml($str, $item);
        }

        $str = ParseRepository::tagToLower($str);

        return $str;
    }

    static function clearTransliterationHtml($str, $tag = [], $flag = false)
    {
        if(!$flag) {
            $str = self::clearTransliterationHtml($str, [mb_strtoupper($tag[0]), mb_strtoupper($tag[1])], true);
        }
        return strtr($str, [
            '<'.$tag[0].'>' => '<'.$tag[1].'>',
            '<'.$tag[0].'>' => '</'.$tag[1].'>',
        ]);
    }

    static function sendMulti($textes = [], $translateTo = 'uk', $translateFrom = 'pl', $recall = 0)
    {
        if(empty($textes)){
            return [];
        }

        $originTextes = $textes;

        $mh = curl_multi_init();
        $conn = [];
        foreach ($textes as $i => $text) {
            if (!empty($text)) {

                $text = mb_strimwidth($text, 0, 3000);

                $conn[$i] = curl_init();
                curl_setopt($conn[$i], CURLOPT_URL, 'https://translate.yandex.net/api/v1/tr.json/translate?srv=tr-url-widget&lang=' . (is_array($translateFrom) ? $translateFrom[$i] : $translateFrom) . '-' . (is_array($translateTo) ? $translateTo[$i] : $translateTo) . '&text=' . urlencode($text));
                /*if($proxy = Application::getProxy()){
                    curl_setopt($conn[$i], CURLOPT_PROXY, $proxy->ip);
                    curl_setopt($conn[$i], CURLOPT_PROXYPORT, $proxy->port);
                    curl_setopt($conn[$i], CURLOPT_PROXYUSERPWD, $proxy->pwd);
                }*/

                /*curl_setopt($conn[$i], CURLOPT_CONNECTTIMEOUT, 15);
                curl_setopt($conn[$i], CURLOPT_TIMEOUT, 15);*/
                curl_setopt($conn[$i], CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                ]);
                curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, true);
                curl_setopt($conn[$i], CURLOPT_FOLLOWLOCATION, true);
                curl_multi_add_handle($mh, $conn[$i]);
            }
        }

        $error = false;
        do {
            try{
                $status = curl_multi_exec($mh, $active);
            } catch (\Exception $e) {
                $error = true;
                break;
            }
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active);


        if(!$error){
            foreach ($textes as $i => $text) {
                if(!empty($text)) {
                    $response = curl_multi_getcontent($conn[$i]);
                    curl_close($conn[$i]);
                    $conn[$i] = null;

                    if(!empty($response)){
                        try{
                            $textes[$i] = json_decode($response);
                        } catch (\Exception $e) {
                            $error = true;
                            break;
                        }
                    }
                }
            }
        }else{
            foreach ($conn as $i => $item){
                if(!empty($conn[$i])){
                    try{
                        curl_close($conn[$i]);
                    } catch (\Exception $e) {}
                }
            }
        }

        if($error){
            if($recall <= 10 && app()->runningInConsole()) {
                sleep(1);
                $textes = self::sendMulti($originTextes, $translateTo, $translateFrom, $recall+1);
            }else{
                $textes = $originTextes;
            }
        }

        foreach ($textes as $i => $text) {
            if(!empty($text) && !is_string($text)) {
                if(!empty($text->text) && !empty($text->text[0])) {
                    $text->text[0] = urldecode($text->text[0]);
                    $encoding = mb_detect_encoding($text->text[0], mb_detect_order(), false);
                    if($encoding == "UTF-8"){
                        $text->text[0] = mb_convert_encoding($text->text[0], 'UTF-8', 'UTF-8');
                    }
                    $textes[$i] = iconv(mb_detect_encoding($text->text[0], mb_detect_order(), false), "UTF-8//IGNORE", $text->text[0]);

                    //$textes[$i] = iconv(mb_detect_encoding(urldecode($text->text[0])), "UTF-8//IGNORE", urldecode($text->text[0]));
                }else{
                    $textes[$i] = $originTextes[$i];
                }
            }
        }

        return $textes;
    }

    static function send($text = '', $translateTo = 'uk', $translateFrom = 'pl', $recall = 0)
    {
        if(empty($text)){
            return '';
        }

        $response = false;

        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://translate.yandex.net/api/v1/tr.json/translate?srv=tr-url-widget&lang='.$translateFrom.'-'.$translateTo.'&text='.urlencode($text));
            /*if($proxy = Application::getProxy()){
                curl_setopt($ch, CURLOPT_PROXY, $proxy->ip);
                curl_setopt($ch, CURLOPT_PROXYPORT, $proxy->port);
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy->pwd);
            }*/

            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($response);
        } catch (\Exception $e) {
            $response = false;
        }

        if(!$response){
            if(!$recall || $recall < 3) {
                $response = self::send($text, $translateTo, $translateFrom, $recall+1);
            }else{
                $response = $text;
            }
        }

        if(!empty($response)){
            try{
                $str = iconv(mb_detect_encoding(urldecode($response->text[0])), "UTF-8//IGNORE", urldecode($response->text[0]));
            } catch (\Exception $e) {
                $str = $text;
            }
        }else{
            $str = $text;
        }

        return $str;
    }

    static function decodeHtml($str, $tag, $flag = false)
    {
        if(!$flag){
            $str = self::decodeHtml($str, mb_strtoupper($tag), true);
        }
        return strtr($str, [
            '< '.$tag.'>' => '<'.$tag.'>',
            '<'.$tag.' >' => '<'.$tag.'>',
            '< '.$tag.' >' => '<'.$tag.'>',
            '< /'.$tag.'>' => '</'.$tag.'>',
            '</'.$tag.' >' => '</'.$tag.'>',
            '< /'.$tag.' >' => '</'.$tag.'>',
            '</ '.$tag.'>' => '</'.$tag.'>',
            '</ '.$tag.' >' => '</'.$tag.'>',
            '< / '.$tag.'>' => '</'.$tag.'>',
            '< / '.$tag.' >' => '</'.$tag.'>',
            '< / '.$tag.'>' => '</'.$tag.'>',
        ]);
    }


}