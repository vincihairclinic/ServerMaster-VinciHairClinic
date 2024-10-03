<?php

namespace App\Repositories\Base;

use App\AppConf;
use App\Repositories\BaseRepository;
use ForceUTF8\Encoding;

class StringClearRepository
{
    static function clearStrWithBaseHtml($str, $mbUcfirst = true, $strToLower = false)
    {
        $str = html_entity_decode($str);
        $str = str_replace('<h1>', '<h2>', $str);
        $str = str_replace('</h1>', '</h2>', $str);
        $str = ParseRepository::convertToSimpleHtml($str, ['<p>','</p>','<ul>','</ul>','<li>','</li>','<h2>','</h2>','<h3>','</h3>','<h5>','</h5>','<h6>','</h6>','<br>']);
        $str = html_entity_decode($str);

        $str = str_replace('(', ' (', $str);
        $str = str_replace(')', ') ', $str);
        $str = str_replace('<br>', '589364538225574', $str);

        $first = ParseRepository::explode('<', 0, $str);
        if(!empty($first)){
            $str = mb_substr($str, mb_strlen($first));
            $first =  StringClearRepository::clearToAds($first, false, '?,:/)/(/&');
        }

        $last = ParseRepository::explode('>', -1, $str);
        if(!empty($last)){
            $last = $last[count($last)-1];
            if(!empty($last)){
                $str = mb_substr($str, 0, -mb_strlen($last));
                $last = StringClearRepository::clearToAds($last, false, '?,:/)/(/&');
            }
        }

        if(!empty($first) && mb_strlen($first) > 2){
            $str = '<p>'.$first.'</p>'.$str;
        }
        if(!empty($last) && mb_strlen($last) > 2){
            $str = $str.'<p>'.$last.'</p>';
        }

        $str = preg_replace_callback('#<(li|p|h2|h3|h4|h5|h6)>(.+)</(li|p|h1|h2|h3|h4|h5|h6)>#iU', function ($matches) use ($mbUcfirst, $strToLower){
            $matches[2] = StringClearRepository::clearToAds($matches[2], false, '?,:/)/(/&');
            if(!empty($matches[2])){
                if($mbUcfirst){
                    $matches[2] = BaseRepository::mbUcfirst($matches[2], $strToLower);
                }
                return '<'.$matches[1].'>' . $matches[2] . '</'.$matches[3].'>';
            }else{
                return '';
            }
        }, $str);

        $str = str_replace('589364538225574', '<br>', $str);
        $str = str_replace('(', ' (', $str);
        $str = str_replace(')', ') ', $str);
        $str = StringRepository::addSpaceBetweenTags($str);
        $str = StringRepository::clearDblSpace($str);
        $str = ' '.$str.' ';
        $str = str_replace(' <p> <br> ', ' <p> ', $str);
        $str = str_replace(' <br> </p> ', ' </p> ', $str);
        $str = str_replace(' <br> <br> <br> ', ' <br> ', $str);
        $str = str_replace(' <br> <br> ', ' <br> ', $str);
        $str = StringRepository::clearDblSpace($str);
        $str = ' '.$str.' ';
        $str = str_replace(' <p> <br> ', ' <p> ', $str);
        $str = str_replace(' <br> </p> ', ' </p> ', $str);
        $str = str_replace(' <br> <br> <br> ', ' <br> ', $str);
        $str = str_replace(' <br> <br> ', ' <br> ', $str);
        $str = StringRepository::clearDblSpace($str);

        return $str;
    }

    static function clearToAds($str, $asKeywords = false, $withBaseTextChars = null, $strtolower = true, $mbUcfirst = true)
    {
        if($withBaseTextChars === null){
            $withBaseTextChars = !$asKeywords;
        }
        if(empty($str)){
            return '';
        }

        $str = StringRepository::replaceWithoutWord($str, '', true, $withBaseTextChars);

        $str = str_replace('-)', ')', $str);
        $str = str_replace('(-', '(', $str);
        foreach (['_'] as $v){
            $str = str_replace($v, ' ', $str);
        }
        $str = StringClearRepository::clearDblSpace($str);
        foreach (['?,', '?.', '?:', ':?', '.,', ',.', ':,', ',:', ':.', '.:'] as $v){
            $str = str_replace($v, ',', $str);
        }
        $str = StringClearRepository::clearDblSpace($str);
        foreach (['-', ',', '.', '?', ':'] as $v){
            $str = str_replace($v.$v, $v, $str);
        }
        $str = StringClearRepository::clearDblSpace($str);
        foreach (['-', ',', '.', '?', ':'] as $v){
            $str = str_replace($v.$v, $v, $str);
        }
        $str = StringClearRepository::clearDblSpace($str);
        foreach (['?,', '?.', '?:', ':?', '.,', ',.', ':,', ',:', ':.', '.:'] as $v){
            $str = str_replace($v, ',', $str);
        }
        foreach (['?', ':', ','] as $v){
            $str = str_replace($v, $v.' ', $str);
        }
        $str = StringClearRepository::clearDblSpace($str);
        $str = trim($str, '-');
        $str = trim($str, ',');
        $str = StringClearRepository::clearDblSpace($str);
        $str = trim($str, ',');
        $str = trim($str, '-');
        $str = StringClearRepository::clearDblSpace($str);


        if($asKeywords){
            if($strtolower){
                $str = mb_strtolower($str);
            }
        }else{
            if($mbUcfirst){
                $str = explode('. ', $str.' ');
                foreach ($str as $i => $v){
                    $str[$i] = BaseRepository::mbUcfirst($v, $strtolower);
                }
                $str = implode('. ', $str);
            }
        }

        return $str;
    }

    static function clearTitle($str, $withBaseTextChars = '/)/(/&', $withHtml = false, $strtolower = true, $mbUcfirst = true)
    {
        $str = StringClearRepository::clearSpace($str);
        if(!empty($str)){
            $str = html_entity_decode($str);
            if($withHtml){
                $str = ParseRepository::convertToSimpleHtml($str, false);
                $str = html_entity_decode($str);
            }
            $str = StringClearRepository::clearToAds($str, false, $withBaseTextChars, $strtolower, $mbUcfirst);
            if($mbUcfirst){
                $str = StringRepository::mbUcfirst($str, $strtolower);
            }
        }
        $str = StringRepository::strimwidth($str, 250);
        return $str;
    }

    static function clearToRedisSearch($searchQuery)
    {
        if(empty($searchQuery)){
            return $searchQuery;
        }
        $searchQuery = StringClearRepository::clearBase($searchQuery);
        $searchQuery = str_replace('-', ' ', $searchQuery);
        $searchQuery = str_replace('一', ' ', $searchQuery);
        $searchQuery = str_replace('_', ' ', $searchQuery);
        $searchQuery = str_replace('.', ' ', $searchQuery);
        $searchQuery = str_replace('"', ' ', $searchQuery);
        $searchQuery = str_replace("'", ' ', $searchQuery);
        $searchQuery = str_replace("(", ' ', $searchQuery);
        $searchQuery = str_replace(")", ' ', $searchQuery);
        $searchQuery = StringClearRepository::clearDblSpace($searchQuery);
        return $searchQuery;
    }

    static function prepareQueryToRedisSearch($searchQuery, $or = false, $floatBegin = false, $floatEnd = false, $removeLastChar = false, $full = false, $strLimit = false)
    {
        /*$searchQuery = str_replace('~', ' ', $searchQuery);
        $searchQuery = str_replace('|', ' ', $searchQuery);
        $searchQuery = str_replace('*', ' ', $searchQuery);
        $searchQuery = str_replace('-', ' ', $searchQuery);
        $searchQuery = str_replace('@', ' ', $searchQuery);
        $searchQuery = str_replace('"', ' ', $searchQuery);
        $searchQuery = str_replace("'", ' ', $searchQuery);
        $searchQuery = str_replace("!", ' ', $searchQuery);
        $searchQuery = str_replace("(", ' ', $searchQuery);
        $searchQuery = str_replace(")", ' ', $searchQuery);*/

        $searchQuery = trim($searchQuery);
        if(empty($searchQuery)){
            return $searchQuery;
        }

        $searchQuery = str_replace('-', ' ', $searchQuery);
        $searchQuery = str_replace('一', ' ', $searchQuery);
        $searchQuery = str_replace('_', ' ', $searchQuery);
        $searchQuery = str_replace('.', ' ', $searchQuery);
        $searchQuery = StringRepository::replaceWithoutWord($searchQuery, ' ', true);

        $searchQuery = StringClearRepository::clearDblSpace($searchQuery);

        if(!empty($searchQuery) && (!empty($floatBegin) || !empty($floatEnd) || !empty($or))){
            if(!empty(AppConf::$not_use_latin_and_cyrillic)){
                $len = mb_strlen($searchQuery);
                if(!empty(trim($searchQuery))){
                    $r = ['('.$searchQuery.')'];
                }else{
                    $r = [];
                }

                for ($k = 0; $k < $len; $k++){
                    $v = mb_substr($searchQuery, $k, 1);
                    $v = trim($v);
                    if(!is_numeric($v) && !empty($v)){
                        $r[] = $v.(!empty($floatEnd) ? '*' : '');
                    }
                }

                $nums = preg_replace('/[^0-9]/', ' ', $searchQuery);
                $nums = StringClearRepository::clearDblSpace($nums);
                $nums = explode(' ', $nums);
                foreach ($nums as $i => $v){
                    if(!empty(trim($v))){
                        $nums[$i] = '('.$v.')';
                    }else{
                        $nums[$i] = null;
                    }
                }

                $r = array_merge($r, $nums);
                $r = array_values(array_unique(array_filter($r)));
                $searchQuery = implode('|', $r);
            }else{
                $searchQueryArr = explode(' ', $searchQuery);
                if(!empty($floatBegin) || !empty($floatEnd)){
                    foreach ($searchQueryArr as $i => $v){
                        $v = trim($v);
                        if(!is_numeric($v) && mb_strlen($v) > ($strLimit !== false ? $strLimit : 2)){
                            $r = ['(' . $v . ')'];
                            if(!empty($full)){
                                $r[] = '(%' . $v . '%)';
                            }
                            if(!empty($floatBegin)){
                                $tmpV = mb_strlen($v) > 3 ? mb_substr($v, 1) : $v;
                                $r[] = $tmpV.(!empty($floatEnd) ? '*' : '');
                                /*if($strLimit === 0){ //TODO
                                    $r[] = '*'.$tmpV;
                                }*/
                                if(!empty($floatEnd)){
                                    $r[] =$v.'*';
                                    if($removeLastChar && mb_strlen($v) > 3){
                                        $r[] = mb_substr($v, 0, -1).'*';
                                        if(mb_strlen($tmpV) > 3){
                                            $r[] = mb_substr($tmpV, 0, -1).'*';
                                        }
                                    }
                                }
                            }else if(!empty($floatEnd)){
                                $r[] =$v.'*';
                                if($removeLastChar && mb_strlen($v) > ($strLimit !== false ? $strLimit : 3)){
                                    $r[] = mb_substr($v, 0, -1).'*';
                                }
                            }else if($removeLastChar && mb_strlen($v) > ($strLimit !== false ? $strLimit : 3)){
                                $r[] = mb_substr($v, 1);
                                $r[] = mb_substr($v, 0, -1);
                            }

                            $r = array_values(array_unique(array_filter($r)));
                            if(!empty($r)){
                                $searchQueryArr[$i] = '('.implode('|', $r).')';
                            }
                        }else if(!empty($v)){
                            $searchQueryArr[$i] = (empty($or) ? '|' : '').'('.$v.')';
                        }
                    }
                }else{
                    foreach ($searchQueryArr as $i => $v){
                        $v = trim($v);
                        if((is_numeric($v) || mb_strlen($v) <= 2) && !empty($v)){
                            $searchQueryArr[$i] = (empty($or) ? '|' : '').'('.$v.')';
                        }
                    }
                }

                if(mb_strpos($searchQuery, ' ') !== false && !empty(trim($searchQuery))){
                    array_unshift($searchQueryArr, '('.$searchQuery.')'.(empty($or) ? '|' : ''));
                }
                $searchQueryArr = array_values(array_unique(array_filter($searchQueryArr)));
                if(!empty($or)){
                    $searchQuery = implode('|', $searchQueryArr);
                }else{
                    $searchQuery = implode(' ', $searchQueryArr);
                }
            }
        }else if(!empty(AppConf::$not_use_latin_and_cyrillic)){
            $searchQueryArr = explode(' ', $searchQuery);
            $searchQueryArr = array_values(array_unique(array_filter($searchQueryArr)));

            if(mb_strpos($searchQuery, ' ') !== false && !empty(trim($searchQuery))){
                array_unshift($searchQueryArr, '('.$searchQuery.')');
            }
            $searchQueryArr = array_values(array_unique(array_filter($searchQueryArr)));
            $searchQuery = implode('|', $searchQueryArr);
        }else{
            $searchQueryArr = explode(' ', $searchQuery);
            foreach ($searchQueryArr as $i => $v){
                $v = trim($v);
                if(is_numeric($v) || mb_strlen($v) <= 2){
                    $searchQueryArr[$i] = null;
                }
            }
            $searchQueryArr = array_values(array_unique(array_filter($searchQueryArr)));
            if(!empty($searchQueryArr)){
                if(mb_strpos($searchQuery, ' ') !== false && !empty(trim($searchQuery))){
                    array_unshift($searchQueryArr, '('.$searchQuery.')'.(empty($or) ? '|' : ''));
                }
                $searchQueryArr = array_values(array_unique(array_filter($searchQueryArr)));
                if(!empty($or)){
                    $searchQuery = implode('|', $searchQueryArr);
                }else{
                    $searchQuery = implode(' ', $searchQueryArr);
                }
            }else{
                $searchQueryArr = explode(' ', $searchQuery);
                $searchQueryArr = array_values(array_unique(array_filter($searchQueryArr)));

                if(mb_strpos($searchQuery, ' ') !== false && !empty(trim($searchQuery))){
                    array_unshift($searchQueryArr, '('.$searchQuery.')');
                }
                $searchQueryArr = array_values(array_unique(array_filter($searchQueryArr)));
                $searchQuery = implode('|', $searchQueryArr);
            }
        }

        /*$searchQuery = str_replace('""', '', $searchQuery);
        $searchQuery = str_replace('"*', '"', $searchQuery);*/

        /*$stopWords = [
            'a', 'is', 'the', 'an', 'and', 'are', 'as', 'at', 'be', 'but', 'by', 'for', 'if', 'in', 'into', 'it', 'no', 'not', 'of', 'on', 'or', 'such', 'that', 'their', 'then', 'there', 'these', 'they', 'this', 'to',  'was', 'will', 'with'
        ];
        foreach ($stopWords as $stopWord){
            $searchQuery = str_replace('|"'.$stopWord.'"', '', $searchQuery);
            $searchQuery = str_replace('"'.$stopWord.'"|', '', $searchQuery);
            $searchQuery = str_replace('"'.$stopWord.'"', '', $searchQuery);
        }*/
        //$searchQuery = str_replace(' by ', '', $searchQuery);
        //$searchQuery = str_replace(' in ', '', $searchQuery);

        /*if($strLimit !== 0) { //TODO
            $searchQuery = str_replace('|*', '|', $searchQuery);
        }*/
        $searchQuery = str_replace('| |', '|', $searchQuery);

        if(config('app.debug')){
            //dd($searchQuery);
            /*return '(bmw X1)|((bmw)|bmw*) |(X1)';
           dd(trim(trim($searchQuery, '*'), '|'));*/

           //$searchQuery = '("Food"|ood*|Food*|Foo*)|(|and*)|("drink"|rink*|drink*|drin*|rin*)|("decade"|ecade*|decade*|decad*|ecad*)|("introduction"|ntroduction*|introduction*|introductio*|ntroductio*)|"Food and drink by decade of introduction"';
        }

        return trim(trim($searchQuery, '*'), '|');
    }

    static function clearFroJsonLd($str)
    {
        $str = stripslashes(stripslashes(stripslashes(stripslashes($str))));
        $str = str_replace('{', '', $str);
        $str = str_replace('}', '', $str);
        return $str;
    }

    static function encodeQuotes($str, $start = ' [', $end = '] ')
    {
        $str = str_replace('«', $start, $str);
        $str = str_replace('»', $end, $str);
        $str = str_replace('„', $start, $str);
        $str = str_replace('“', $end, $str);

        return $str;
    }

    static function decodeQuotes($str, $withSquare = false)
    {
        if($withSquare){
            $str = str_replace('[', '"', $str);
            $str = str_replace(']', '"', $str);
        }
        $str = self::encodeQuotes($str, '"', '"');

        return $str;
    }

    static function clearBaseArtifacts($str)
    {
        $str = str_replace('·', ' ', $str);
        $str = str_replace('::', ' ', $str);
        $str = str_replace(':.', '.', $str);
        $str = str_replace(':,', ',', $str);
        $str = str_replace(',.', '.', $str);
        $str = str_replace('..', '.', $str);
        $str = str_replace(',,', ',', $str);

        $str = str_replace(',-', ' ', $str);
        $str = str_replace('.-', ' ', $str);

        $str = StringClearRepository::clearDblSpace($str);

        $str = str_replace(' . ', '. ', $str);
        $str = str_replace(' .', '.', $str);
        $str = str_replace(' ,', ',', $str);

        return $str;
    }

    static function normalize($str, $withQuotes = true)
    {
        $str = Encoding::toUTF8($str);
        $str = html_entity_decode($str, ENT_QUOTES);
        $str = htmlspecialchars_decode($str, ENT_QUOTES);
        $str = StringClearRepository::clearHex($str);
        //$str = StringClearRepository::convertStrWithoutAcute($str);
        if($withQuotes){
            $str = StringClearRepository::decodeQuotes($str);
        }

        return $str;
    }

    static function clearHex($str)
    {
        $str = preg_replace('/[\x00-\x1F\x7F]/', ' ', $str);
        $str = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', ' ', $str);
        $str = preg_replace('/[[:cntrl:]]/', ' ', $str);
        $str = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $str);
        $str = str_replace('✸', ' ', $str);
        $str = str_ireplace('✓', ' ', $str);
        $str = str_ireplace('☎', ' ', $str);
        $str = str_ireplace('•', ' ', $str);
        $str = StringClearRepository::clearDblSpace($str, false, false);
        return $str;
    }

    static function clearSingleChar($str)
    {
        if(empty($str)){
            return '';
        }
        $result = [];
        foreach (explode(' ', $str) as $item){
            if(mb_strlen($item) > 1){
                $result[] = $item;
            }
        }
        return implode(' ', $result);
    }

    static function clearSpace($str, $removeTabs = true, $removeLineFeeds = true)
    {
        if(empty($str) || !is_string($str)){
            return '';
        }

        $str = str_replace('/,', ',', $str);
        $str = str_replace(',,,', ',', $str);
        $str = str_replace(',,', ',', $str);
        $str = str_replace('...', '.', $str);
        $str = str_replace('..', '.', $str);

        if(!$removeTabs || !$removeLineFeeds){
            $str = preg_replace("/\t\t+/", "\t", $str);
            $str = preg_replace("/\r\r+/", "\r", $str);
            $str = preg_replace("/\n\n+/", "\n", $str);
        }

        if($removeTabs){
            $str = str_replace("\t", ' ', $str);
        }
        if($removeLineFeeds){
            $str = str_replace("\r", ' ', $str);
            $str = str_replace("\n", ' ', $str);
        }
        $str = StringClearRepository::clearDblSpace($str, $removeLineFeeds, $removeTabs);
        $str = preg_replace('|([0-9]{1,20}),([0-9]{1,20})|isU', '$1.$2', $str);

        foreach ([" )", "( ", " ]", "[ ", " :", " ,", " .", " !", " ?"] as $s){
            $str = str_replace($s, trim($s), $str);
        }
        $str = str_replace('()', '', $str);
        $str = str_replace('( )', '', $str);

        return trim($str);
    }

    static function clearEmoji($string) {

        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $string);

        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }

    static function clearKeyword($str)
    {
        if(empty($str)){
            return $str;
        }
        $str = str_replace("'", '`', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace(',', ' ', $str);

        return StringClearRepository::clearDblSpace($str);
    }

    static function clearDblSpace($str, $removeLineFeeds = true, $removeTabs = true)
    {
        if(empty($str)){
            return $str;
        }
        $str = preg_replace('/ /', ' ', $str); // this is special space to simple space
        $str = preg_replace('/⠀/', ' ', $str); // this is special 2 space to simple space
        $str = preg_replace('/​/', ' ', $str); // this is special 3 space to simple space
        $str = preg_replace('/&thinsp;/', ' ', $str);
        $str = preg_replace('/&nbsp;/', ' ', $str);

        $str = preg_replace('/\(\s+/', ' (', $str);
        $str = preg_replace('/\s+\)/', ') ', $str);
        $str = preg_replace('/(\D)\.(\D)/', '$1. $2', $str);
        $str = preg_replace('/(\D),(\D)/', '$1, $2', $str);

        $str = str_replace(mb_convert_encoding('&nbsp;', 'UTF-8', 'HTML-ENTITIES'), ' ', $str);

        if($removeLineFeeds === 'br'){
            $str = str_replace("\n", ' <br> ', $str);
            $str = preg_replace("/(\s+)?<br>(\s+)?/", '<br>', $str);
            $str = preg_replace("/(<br>)+/", ' <br> ', $str);
        }else if($removeLineFeeds){
            $str = str_replace("\n", ' ', $str);
        }
        if($removeTabs){
            $str = str_replace("\t", ' ', $str);
            $str = str_replace("\r", ' ', $str);
            $str = preg_replace("/\n /", "\n", $str);
            $str = preg_replace("/ \n/", "\n", $str);
            $str = preg_replace("/\n\n+/", "\n", $str);
        }

        if($removeLineFeeds && $removeTabs){
            $str = preg_replace("/\s\s+/", ' ', $str);
        }else{
            $str = preg_replace("/  +/", ' ', $str);
        }

        $str = preg_replace("/\?+/", '?', $str);
        $str = preg_replace("/!+/", '!', $str);
        $str = preg_replace("/\.+/", '.', $str);
        $str = preg_replace("/,+/", ',', $str);

        //$str = str_replace('  ', ' ', $str);
        return trim($str);
    }

    static function clearBase($str)
    {
        foreach (['&nbsp;', '&#5128;', '&#10148;', '&#9989;', '&#12304;', '&#12305;', '&#5129;'] as $v){
            $str = str_replace(mb_convert_encoding($v, 'UTF-8', 'HTML-ENTITIES'), '', $str);
        }
        $str = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $str);

        foreach (['(', ')', ';', '...', '—', '»', '«', ' - ', '_', '“', '”', ' | '] as $v) {
            $str = StringRepository::replace($v, $str, ' ');
        }

        /*foreach (['по', 'на', 'и'] as $v) {
            $str = StringRepository::replaceBegin($v . ' ', $str, ' ');
            $str = StringRepository::replaceEnd(' ' . $v, $str, ' ');
        }*/

        foreach (['.', ',', '?'] as $v) {
            $str = StringRepository::replaceBegin($v, $str);
            $str = StringRepository::replace(' ' . $v, $str, $v);
        }

        foreach (['-'] as $v) {
            $str = StringRepository::replaceBegin($v, $str, ' ');
            $str = StringRepository::replaceEnd($v, $str, ' ');
        }

        foreach ([' : '] as $v) {
            $str = StringRepository::replace($v, $str, ' ');
        }
        //$str = StringRepository::replace('(http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?)', $str, ' ', true);

        return $str;
    }

    static function clearUrls($str)
    {
        //dd(urldecode($str), StringRepository::replace('(http(s)?://([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)', urldecode($str), '', true));
        //return StringRepository::replace('(http(s)?://([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)', $str, ' ', true);
        //return StringRepository::replace('((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)', $str, ' ', true);
        //return StringRepository::replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', $str, ' ', true);
        return preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $str);
    }

    static function clearToKeywords($str, $isNotKeywords = [])
    {
        $str = self::clearBase($str);

        foreach (['.', ','] as $v) {
            $str = StringRepository::replace($v, $str, ' ');
        }

        foreach ($isNotKeywords as $v) {
            $str = StringRepository::replace(' '.$v.' ', $str, '');
            $str = StringRepository::replaceBegin($v.' ', $str, '');
            $str = StringRepository::replaceEnd(' '.$v, $str, '');
        }

        $str = StringRepository::replace('>', $str, '> ');
        $str = StringRepository::replace('<', $str, ' <');

        $str = ParseRepository::replaceTagInText('cite', $str, ' ');
        $str = mb_strtolower($str);
        $str = BaseRepository::stripTags($str);

        //$str = StringRepository::replaceWithoutWord($str, ' ', '/');
        $str = StringRepository::clearSpace($str);

        foreach (['-'] as $v) {
            $str = StringRepository::replaceBegin($v, $str, ' ');
            $str = StringRepository::replaceEnd($v, $str, ' ');
        }
        foreach ([' -', '- '] as $v) {
            $str = StringRepository::replace($v, $str, ' ');
        }
        $str = StringRepository::clearBase($str);

        return $str;
    }

    //---------------------------------------------------------

    static function clearBracketsArtifacts($str)
    {
        $str = str_replace('((', '(', $str);
        $str = str_replace('))', ')', $str);
        $str = str_replace('(  ', '(', $str);
        $str = str_replace('( ', '(', $str);
        $str = str_replace('  )', ')', $str);
        $str = str_replace(' )', ')', $str);
        $str = str_replace('  ,', ',', $str);
        $str = str_replace(' ,', ',', $str);
        $str = str_replace('(,', '(', $str);
        $str = str_replace(',)', ')', $str);
        $str = str_replace('(:', '(', $str);
        $str = str_replace(':)', ')', $str);
        $str = str_replace('(.', '(', $str);
        $str = str_replace('.)', ')', $str);
        $str = str_replace('&)', ')', $str);
        $str = str_replace('(&', '(', $str);
        $str = str_replace('(/', '(', $str);
        $str = str_replace('/)', ')', $str);
        $str = str_replace(' )', ')', $str);
        $str = str_replace('( ', '(', $str);
        return $str;
    }

    static function clearRepeatsReg($str, $all = false){
        if($all){
            $str = preg_replace('/\b(\w+)\b(?=.*?\b\1\b)/ius', ' ', $str);
        }else{
            $s = '[\s(),]+';
            $str = preg_replace('/(\b\S+\b)'.$s.'\1'.$s.'/ius', '$1 ', $str);
        }
        $str = str_replace('"  ', '"', $str);
        $str = StringClearRepository::clearBracketsArtifacts($str);
        $str = StringRepository::clearSpace($str);

        return $str;
    }

    static function clearRepeats($str, $minWordLength = 2, $delimiter = ' ', $glue = ' ')
    {
        //preg_replace('#(\b\w+\b\s)(?=.?\1)#siu', '', 'abc abc 333 333')
        $result = [];
        $str = ParseRepository::explode($delimiter, -1, $str);
        foreach ($str as $i => $v){
            $v = trim($v);
            if(!empty($v) && ((mb_strlen($v) <= $minWordLength && (empty($result) || $result[count($result)-1] != $v)) || !in_array($v, $result))){
                $result[] = $v;
            }
        }
        $result = StringRepository::implode($glue, $result);
        $result = StringRepository::clearSpace($result);

        return $result;
    }

    static function clearWordRepeats($str, $minWordLength = 2, $delimiter = ' ', $glue = ' ')
    {
        $result = [];
        $str = ParseRepository::explode($delimiter, -1, $str);
        foreach ($str as $i => $v){
            $v = trim($v);
            if(!empty($v) && mb_strlen($v) > $minWordLength && !in_array($v, $result)){
                $result[] = $v;
            }
        }
        $result = StringRepository::implode($glue, $result);
        $result = StringRepository::clearSpace($result);

        return $result;
    }

    static function clearToSearch($str, $onlyChars = false, $minStrlen = 1)
    {
        //TODO not done
        return $str;
    }

    static function convertStrWithoutAcute($str)
    {
        if(!AppConf::$clear_acute){
            return $str;
        }

        if(in_array(AppConf::$lang, ['ru', 'en', 'uk'])){
            $str = str_replace('ä', 'a', $str);
            $str = str_replace('ö', 'o', $str);
            $str = str_replace('Ä', 'A', $str);
            $str = str_replace('Ö', 'O', $str);
            $str = str_replace('ć', 'c', $str);
            $str = str_replace('Ć', 'C', $str);
            $str = str_replace('ł', 'l', $str);
            $str = str_replace('Ł', 'L', $str);
            $str = str_replace('Ë', 'E', $str);
            $str = str_replace('ë', 'e', $str);
            $str = str_replace('ó', 'o', $str);
            $str = str_replace('Ó', 'O', $str);

            $str = str_replace('ü', 'u', $str);

            $str = str_replace('Ü', 'U', $str);
            $str = str_replace('ą', 'a', $str);
            $str = str_replace('Ą', 'A', $str);

            $str = str_replace('ę', 'e', $str);
            $str = str_replace('Ę', 'E', $str);
            $str = str_replace('ś', 's', $str);
            $str = str_replace('Ś', 'S', $str);
            $str = str_replace('ź', 'z', $str);
            $str = str_replace('Ź', 'Z', $str);

            $str = str_replace("í", "i", $str);
            $str = str_replace("Í", "I", $str);
            $str = str_replace("á", "a", $str);
            $str = str_replace("Á", "A", $str);
            $str = str_replace("é", "e", $str);
            $str = str_replace("É", "E", $str);
            $str = str_replace('Ò', 'O', $str);
            $str = str_replace('ò', 'o', $str);
            $str = str_replace('а̀', 'а', $str);
            $str = str_replace('Ӓ', 'А', $str);
            $str = str_replace('ӓ', 'а', $str);
            $str = str_replace('а́', 'а', $str);
            $str = str_replace('Ý', 'Y', $str);
            $str = str_replace('ý', 'y', $str);
            $str = str_replace('Í', 'I', $str);
            $str = str_replace('í', 'i', $str);
            $str = str_replace('á', 'a', $str);
            $str = str_replace('Á', 'A', $str);
            $str = str_replace('è', 'e', $str);
            $str = str_replace('é', 'e', $str);
            $str = str_replace('É', 'E', $str);
            $str = str_replace('È', 'E', $str);
            $str = str_replace('Ĺ', 'L', $str);
            $str = str_replace('ń', 'n', $str);
            $str = str_replace('Ń', 'N', $str);

            $str = str_replace('ú', 'u', $str);
            $str = str_replace('ű', 'u', $str);
            $str = str_replace('ĺ', 'L', $str);

            $str = str_replace('Ú', 'u', $str);
            $str = str_replace('Ű', 'u', $str);
            $str = str_replace('ŕ', 'r', $str);


            $str = str_replace('Ŕ', 'R', $str);
            $str = str_replace('Ÿ', 'Y', $str);

            $str = str_replace('ÿ', 'y', $str);

            $str = str_replace('ő', 'o', $str);


            $str = str_replace('ň', 'n', $str);
            $str = str_replace('Ň', 'N', $str);

            $str = str_replace('ż', 'z', $str);
            $str = str_replace('ž', 'z', $str);
            $str = str_replace('Ż', 'Z', $str);
            $str = str_replace('Ž', 'Z', $str);



            $str = str_replace('Â', 'A', $str);
            $str = str_replace('Ă', 'A', $str);


            $str = str_replace('Ě', 'E', $str);
            $str = str_replace('Î', 'I', $str);
            $str = str_replace('Ď', 'D', $str);
            $str = str_replace('Ô', 'O', $str);
            $str = str_replace('Ő', 'O', $str);

            $str = str_replace('Ř', 'e', $str);


            $str = str_replace('ě', 'e', $str);
            $str = str_replace('î', 'i', $str);
            $str = str_replace('ď', 'd', $str);
            $str = str_replace('ô', 'o', $str);
            $str = str_replace('ő', 'o', $str);

            $str = str_replace('ř', 'e', $str);

            $str = str_replace('â', 'a', $str);
            $str = str_replace('ă', 'a', $str);


            $str = str_replace('ě', 'e', $str);
            $str = str_replace('î', 'i', $str);
            $str = str_replace('ď', 'd', $str);
            $str = str_replace('ô', 'o', $str);
            $str = str_replace('ő', 'o', $str);
            $str = str_replace('ř', 'r', $str);


            $str = str_replace('č', 'c', $str);


            $str = str_replace('ӑ', 'а', $str);

            $str = str_replace('Ӑ', 'А', $str);
            $str = str_replace('Ȧ', 'A', $str);
            $str = str_replace('ȧ', 'a', $str);
            $str = str_replace('Ċ', 'C', $str);
            $str = str_replace('ċ', 'c', $str);
            $str = str_replace('Ḋ', 'D', $str);
            $str = str_replace('ḋ', 'd', $str);
            $str = str_replace('Ė', 'E', $str);
            $str = str_replace('ė', 'e', $str);
            $str = str_replace('Ḟ', 'F', $str);
            $str = str_replace('ḟ', 'f', $str);
            $str = str_replace('K̇', 'K', $str);
            $str = str_replace('k̇', 'k', $str);
            $str = str_replace('Ṁ', 'M', $str);
            $str = str_replace('ṁ', 'm', $str);
            $str = str_replace('Ṅ', 'N', $str);
            $str = str_replace('ṅ', 'n', $str);
            $str = str_replace('Ȯ', 'O', $str);
            $str = str_replace('ȯ', 'o', $str);
            $str = str_replace('Ṗ', 'P', $str);
            $str = str_replace('ṗ', 'p', $str);
            $str = str_replace('Š', 'S', $str);
            $str = str_replace('š', 's', $str);
            $str = str_replace('Ṡ', 'S', $str);
            $str = str_replace('ṡ', 's', $str);
            $str = str_replace('Ṫ', 'T', $str);
            $str = str_replace('ṫ', 't', $str);
            $str = str_replace('U̇', 'U', $str);
            $str = str_replace('u̇', 'u', $str);
            $str = str_replace('Ż', 'Z', $str);
            $str = str_replace('ż', 'z', $str);
            $str = str_replace('Ẏ', 'Y', $str);
            $str = str_replace('ẏ', 'y', $str);
            $str = str_replace('Ẋ', 'X', $str);
            $str = str_replace('ẋ', 'x', $str);
            $str = str_replace('Ẇ', 'W', $str);
            $str = str_replace('ẇ', 'w', $str);
            $str = str_replace('ß', 'S', $str);
        }


        $str = str_replace('Ð', 'D', $str);
        $str = str_replace('ð', 'd', $str);

        $str = str_replace('˙', "'", $str);
        $str = str_replace('Ţ', 'T', $str);
        $str = str_replace('ţ', 't', $str);
        $str = str_replace('Đ', 'D', $str);
        $str = str_replace('đ', 'd', $str);
        $str = str_replace('Œ', 'Oe', $str);
        $str = str_replace('œ', 'oe', $str);
        $str = str_replace('ľ', 'l', $str);

        $str = str_replace('ů', 'u', $str);
        $str = str_replace('Ů', 'u', $str);
        $str = str_replace('Ş', 'S', $str);
        $str = str_replace('ş', 's', $str);

        $str = str_replace('А́', 'А', $str);
        $str = str_replace('А̀', 'А', $str);



        $str = str_replace('А̊', 'А', $str);
        $str = str_replace('а̊', 'а', $str);
        $str = str_replace('А̃', 'А', $str);
        $str = str_replace('а̃', 'а', $str);

        $str = str_replace('В̌', 'В', $str);
        $str = str_replace('в̌', 'в', $str);

        if(in_array(AppConf::$lang, ['ru', 'uk'])) {
            $str = str_replace('Ѓ', 'Г', $str);
            $str = str_replace('ѓ', 'г', $str);
            $str = str_replace('Г̌', 'Г', $str);
            $str = str_replace('г̌', 'г', $str);
            $str = str_replace('Ғ̌', 'Г', $str);
            $str = str_replace('ғ̌', 'г', $str);
            $str = str_replace('Г̆', 'Г', $str);
            $str = str_replace('г̆', 'г', $str);
            $str = str_replace('Ҕ̆', 'Г', $str);
            $str = str_replace('ҕ̆', 'г', $str);
            $str = str_replace('Г̑', 'Г', $str);
            $str = str_replace('г̑', 'г', $str);
            $str = str_replace('Г̇', 'Г', $str);
            $str = str_replace('г̇', 'г', $str);
            $str = str_replace('Г̄', 'Г', $str);
            $str = str_replace('г̄', 'г', $str);
            $str = str_replace('Г̓', 'Г', $str);
            $str = str_replace('г̓', 'г', $str);
            $str = str_replace('Ғ', 'Г', $str);
            $str = str_replace('ғ', 'г', $str);
            $str = str_replace('Ӻ', 'Г', $str);
            $str = str_replace('ӻ', 'г', $str);
            $str = str_replace('Ҕ', 'Г', $str);
            $str = str_replace('ҕ', 'г', $str);
            $str = str_replace('Ҕ', 'Г', $str);
            $str = str_replace('ҕ', 'г', $str);
            $str = str_replace('Ӷ', 'Г', $str);
            $str = str_replace('ӷ', 'г', $str);
            $str = str_replace('Г̧', 'Г', $str);
            $str = str_replace('г̧', 'г', $str);

            $str = str_replace('Д́', 'Д', $str);
            $str = str_replace('д́', 'д', $str);
            $str = str_replace('Д̌', 'Д', $str);
            $str = str_replace('д̌', 'д', $str);
            $str = str_replace('Д̈', 'Д', $str);
            $str = str_replace('д̈', 'д', $str);

            $str = str_replace('Е́', 'Е', $str);
            $str = str_replace('е́', 'е', $str);
            $str = str_replace('Ѐ', 'Е', $str);
            $str = str_replace('ѐ', 'е', $str);
            $str = str_replace('Ӗ', 'Е', $str);
            $str = str_replace('ӗ', 'е', $str);
            $str = str_replace('Ё́', 'Е', $str);
            $str = str_replace('Ё̄', 'Е', $str);
            $str = str_replace('ё̄', 'е', $str);
            $str = str_replace('Е̄', 'Е', $str);
            $str = str_replace('е̄', 'е', $str);
            $str = str_replace('Е̃', 'Е', $str);
            $str = str_replace('е̃', 'е', $str);
            $str = str_replace('Є̈', 'Е', $str);
            $str = str_replace('є̈', 'е', $str);

            $str = str_replace('Ӂ', 'Ж', $str);
            $str = str_replace('ӂ', 'ж', $str);
            $str = str_replace('Ж̑', 'Ж', $str);
            $str = str_replace('ж̑', 'ж', $str);
            $str = str_replace('Ӝ', 'Ж', $str);
            $str = str_replace('ӝ', 'ж', $str);
            $str = str_replace('Җ', 'Ж', $str);
            $str = str_replace('җ', 'ж', $str);

            $str = str_replace('З́', 'З', $str);
            $str = str_replace('з́', 'з', $str);
            $str = str_replace('З̌', 'З', $str);
            $str = str_replace('з̌', 'з', $str);
            $str = str_replace('З̑', 'З', $str);
            $str = str_replace('з̑', 'з', $str);
            $str = str_replace('Ӟ', 'З', $str);
            $str = str_replace('ӟ', 'з', $str);
            $str = str_replace('Ҙ', 'З', $str);
            $str = str_replace('ҙ', 'з', $str);
            $str = str_replace('Ԑ̈', 'З', $str);
            $str = str_replace('ԑ̈', 'з', $str);

            $str = str_replace('И́', 'И', $str);
            $str = str_replace('и́', 'и', $str);
            $str = str_replace('Ѝ', 'И', $str);
            $str = str_replace('ѝ', 'и', $str);
            $str = str_replace('Ҋ', 'И', $str);
            $str = str_replace('ҋ', 'и', $str);
            $str = str_replace('Ӥ', 'И', $str);
            $str = str_replace('ӥ', 'и', $str);
            $str = str_replace('Ӣ', 'И', $str);
            $str = str_replace('ӣ', 'и', $str);
            $str = str_replace('И̃', 'И', $str);
            $str = str_replace('и̃', 'и', $str);
            $str = str_replace('І́', 'И', $str);
            $str = str_replace('і́', 'и', $str);
            $str = str_replace('Ї́', 'И', $str);
            $str = str_replace('ї́', 'и', $str);
            $str = str_replace('Ѷ', 'И', $str);
            $str = str_replace('ѷ', 'и', $str);

            $str = str_replace('Ќ', 'К', $str);
            $str = str_replace('ќ', 'к', $str);
            $str = str_replace('К̆', 'К', $str);
            $str = str_replace('к̆', 'к', $str);
            $str = str_replace('Ӄ̆', 'К', $str);
            $str = str_replace('ӄ̆', 'к', $str);
            $str = str_replace('К̑', 'К', $str);
            $str = str_replace('к̑', 'к', $str);
            $str = str_replace('К̇', 'К', $str);
            $str = str_replace('к̇', 'к', $str);
            $str = str_replace('К̈', 'К', $str);
            $str = str_replace('к̈', 'к', $str);
            $str = str_replace('К̓', 'К', $str);
            $str = str_replace('к̓', 'к', $str);
            $str = str_replace('Ӄ', 'К', $str);
            $str = str_replace('ӄ', 'к', $str);
            $str = str_replace('Ҟ', 'К', $str);
            $str = str_replace('ҟ', 'к', $str);
            $str = str_replace('Ҝ', 'К', $str);
            $str = str_replace('ҝ', 'к', $str);
            $str = str_replace('Қ', 'К', $str);
            $str = str_replace('қ', 'к', $str);

            $str = str_replace('Л́', 'Л', $str);
            $str = str_replace('л́', 'л', $str);
            $str = str_replace('Л̑', 'Л', $str);
            $str = str_replace('л̑', 'л', $str);
            $str = str_replace('Л̇', 'Л', $str);
            $str = str_replace('л̇', 'л', $str);
            $str = str_replace('Ԓ', 'Л', $str);
            $str = str_replace('ԓ', 'л', $str);
            $str = str_replace('Ԡ', 'Л', $str);
            $str = str_replace('ԡ', 'л', $str);
            $str = str_replace('Ԯ', 'Л', $str);
            $str = str_replace('ԯ', 'л', $str);
            $str = str_replace('Ӆ', 'Л', $str);
            $str = str_replace('ӆ', 'л', $str);

            $str = str_replace('Ӎ', 'М', $str);
            $str = str_replace('ӎ', 'м', $str);

            $str = str_replace('Ӎ', 'Н', $str);
            $str = str_replace('ӎ', 'н', $str);
            $str = str_replace('Н́', 'Н', $str);
            $str = str_replace('н́', 'н', $str);
            $str = str_replace('Ӈ', 'Н', $str);
            $str = str_replace('ӈ', 'н', $str);
            $str = str_replace('Ԣ', 'Н', $str);
            $str = str_replace('ԣ', 'н', $str);
            $str = str_replace('Ԩ', 'Н', $str);
            $str = str_replace('ԩ', 'н', $str);
            $str = str_replace('Ң', 'Н', $str);
            $str = str_replace('ң', 'н', $str);
            $str = str_replace('Ӊ', 'Н', $str);
            $str = str_replace('ӊ', 'н', $str);

            $str = str_replace('О́', 'О', $str);
            $str = str_replace('о́', 'о', $str);
            $str = str_replace('О̀', 'О', $str);
            $str = str_replace('о̀', 'о', $str);
            $str = str_replace('О̂', 'О', $str);
            $str = str_replace('о̂', 'о', $str);
            $str = str_replace('О̆', 'О', $str);
            $str = str_replace('о̆', 'о', $str);
            $str = str_replace('Ӧ', 'О', $str);
            $str = str_replace('ӧ', 'о', $str);
            $str = str_replace('Ӧ̄', 'О', $str);
            $str = str_replace('ӧ̄', 'о', $str);
            $str = str_replace('О̄', 'О', $str);
            $str = str_replace('о̄', 'о', $str);
            $str = str_replace('О̃', 'О', $str);
            $str = str_replace('о̃', 'о', $str);
            $str = str_replace('Ө̆', 'О', $str);
            $str = str_replace('ө̆', 'о', $str);
            $str = str_replace('Ӫ', 'О', $str);
            $str = str_replace('ӫ', 'о', $str);
            $str = str_replace('Ө̄', 'О', $str);
            $str = str_replace('ө̄', 'о', $str);
            $str = str_replace('Ѽ', 'О', $str);
            $str = str_replace('ѽ', 'о', $str);

            $str = str_replace('П̑', 'П', $str);
            $str = str_replace('п̑', 'п', $str);
            $str = str_replace('П̓', 'П', $str);
            $str = str_replace('п̓', 'п', $str);
            $str = str_replace('Ҧ', 'П', $str);
            $str = str_replace('ҧ', 'п', $str);
            $str = str_replace('Ԥ', 'П', $str);
            $str = str_replace('ԥ', 'п', $str);

            $str = str_replace('Р́', 'Р', $str);
            $str = str_replace('р́', 'р', $str);
            $str = str_replace('Р̌', 'Р', $str);
            $str = str_replace('р̌', 'р', $str);
            $str = str_replace('Ҏ', 'Р', $str);
            $str = str_replace('ҏ', 'р', $str);

            $str = str_replace('С́', 'С', $str);
            $str = str_replace('с́', 'с', $str);
            $str = str_replace('С̌', 'С', $str);
            $str = str_replace('с̌', 'с', $str);
            $str = str_replace('Ҫ̓', 'С', $str);
            $str = str_replace('ҫ̓', 'с', $str);
            $str = str_replace('Ҫ', 'С', $str);
            $str = str_replace('ҫ', 'с', $str);

            $str = str_replace('Т́', 'Т', $str);
            $str = str_replace('т́', 'т', $str);
            $str = str_replace('Т̑', 'Т', $str);
            $str = str_replace('т̑', 'т', $str);
            $str = str_replace('Т̈', 'Т', $str);
            $str = str_replace('т̈', 'т', $str);
            $str = str_replace('Т̓', 'Т', $str);
            $str = str_replace('т̓', 'т', $str);
            $str = str_replace('Ꚋ', 'Т', $str);
            $str = str_replace('ꚋ', 'т', $str);
            $str = str_replace('Ҭ', 'Т', $str);
            $str = str_replace('ҭ', 'т', $str);
            $str = str_replace('Ꚍ̆', 'Т', $str);
            $str = str_replace('ꚍ̆', 'т', $str);

            $str = str_replace('У́', 'У', $str);
            $str = str_replace('у́', 'у', $str);
            $str = str_replace('Ӳ', 'У', $str);
            $str = str_replace('ӳ', 'у', $str);
            $str = str_replace('У̀', 'У', $str);
            $str = str_replace('у̀', 'у', $str);
            $str = str_replace('Ў', 'У', $str);
            $str = str_replace('ў', 'у', $str);
            $str = str_replace('Ӱ', 'У', $str);
            $str = str_replace('ӱ', 'у', $str);
            $str = str_replace('Ӱ́', 'У', $str);
            $str = str_replace('ӱ́', 'у', $str);
            $str = str_replace('Ӱ̄', 'У', $str);
            $str = str_replace('ӱ̄', 'у', $str);
            $str = str_replace('Ӯ', 'У', $str);
            $str = str_replace('ӯ', 'у', $str);
            $str = str_replace('У̃', 'У', $str);
            $str = str_replace('у̃', 'у', $str);
            $str = str_replace('У̊', 'У', $str);
            $str = str_replace('у̊', 'у', $str);
            $str = str_replace('Ү́', 'У', $str);
            $str = str_replace('ү́', 'у', $str);
            $str = str_replace('Ұ', 'У', $str);
            $str = str_replace('ұ', 'у', $str);


            $str = str_replace('Ф̑', 'Ф', $str);
            $str = str_replace('ф̑', 'ф', $str);
            $str = str_replace('Ф̓', 'Ф', $str);
            $str = str_replace('ф̓', 'ф', $str);


            $str = str_replace('Х́', 'Х', $str);
            $str = str_replace('х́', 'х', $str);
            $str = str_replace('Х̌', 'Х', $str);
            $str = str_replace('х̌', 'х', $str);
            $str = str_replace('Х̆', 'Х', $str);
            $str = str_replace('х̆', 'х', $str);
            $str = str_replace('Х̑', 'Х', $str);
            $str = str_replace('х̑', 'х', $str);
            $str = str_replace('Х̇', 'Х', $str);
            $str = str_replace('х̇', 'х', $str);
            $str = str_replace('Х̓', 'Х', $str);
            $str = str_replace('х̓', 'х', $str);
            $str = str_replace('Ӽ', 'Х', $str);
            $str = str_replace('ӽ', 'х', $str);
            $str = str_replace('Ӿ', 'Х', $str);
            $str = str_replace('ӿ', 'х', $str);
            $str = str_replace('Ҳ', 'Х', $str);
            $str = str_replace('ҳ', 'х', $str);
            $str = str_replace('Ԧ', 'Х', $str);
            $str = str_replace('ԧ', 'х', $str);

            $str = str_replace('Ц́', 'Ц', $str);
            $str = str_replace('ц́', 'ц', $str);
            $str = str_replace('Ц̌', 'Ц', $str);
            $str = str_replace('ц̌', 'ц', $str);
            $str = str_replace('Ц̓', 'Ц', $str);
            $str = str_replace('ц̓', 'ц', $str);
            $str = str_replace('Ꚏ̆', 'Ц', $str);
            $str = str_replace('ꚏ̆', 'ц', $str);

            $str = str_replace('Ч̑', 'Ч', $str);
            $str = str_replace('ч̑', 'ч', $str);
            $str = str_replace('Ӵ', 'Ч', $str);
            $str = str_replace('ӵ', 'ч', $str);
            $str = str_replace('Ч̓', 'Ч', $str);
            $str = str_replace('ч̓', 'ч', $str);
            $str = str_replace('Ҹ', 'Ч', $str);
            $str = str_replace('ҹ', 'ч', $str);
            $str = str_replace('Ҷ', 'Ч', $str);
            $str = str_replace('ҷ', 'ч', $str);
            $str = str_replace('Ҽ̆', 'Ч', $str);
            $str = str_replace('ҽ̆', 'ч', $str);
            $str = str_replace('Ҿ', 'Ч', $str);
            $str = str_replace('ҿ', 'ч', $str);

            $str = str_replace('Ш̆', 'Ш', $str);
            $str = str_replace('ш̆	Ш', 'ш', $str);
            $str = str_replace('Ш̑', 'Ш', $str);
            $str = str_replace('ш̑	Ш', 'ш', $str);
            $str = str_replace('Ꚗ̆', 'Ш', $str);
            $str = str_replace('ꚗ̆	Швэ', 'ш', $str);

            $str = str_replace('Щ̆', 'Щ', $str);
            $str = str_replace('щ̆', 'щ', $str);

            $str = str_replace('Ъ̀', 'Ъ', $str);
            $str = str_replace('ъ̀', 'ъ', $str);

            $str = str_replace('Ы́', 'Ы', $str);
            $str = str_replace('ы́', 'ы', $str);
            $str = str_replace('Ы̆', 'Ы', $str);
            $str = str_replace('ы̆', 'ы', $str);
            $str = str_replace('Ӹ', 'Ы', $str);
            $str = str_replace('ӹ', 'ы', $str);
            $str = str_replace('Ы̄', 'Ы', $str);
            $str = str_replace('ы̄', 'ы', $str);
            $str = str_replace('Ы̃', 'Ы', $str);
            $str = str_replace('ы̃', 'ы', $str);

            $str = str_replace('Э́', 'Э', $str);
            $str = str_replace('э́', 'э', $str);
            $str = str_replace('Э̆', 'Э', $str);
            $str = str_replace('э̆', 'э', $str);
            $str = str_replace('Э̄', 'Э', $str);
            $str = str_replace('э̄', 'э', $str);
            $str = str_replace('Э̇', 'Э', $str);
            $str = str_replace('э̇', 'э', $str);
            $str = str_replace('Ӭ', 'Э', $str);
            $str = str_replace('ӭ', 'э', $str);
            $str = str_replace('Ӭ́', 'Э', $str);
            $str = str_replace('ӭ́', 'э', $str);
            $str = str_replace('Ӭ̄', 'Э', $str);
            $str = str_replace('ӭ̄', 'э', $str);

            $str = str_replace('Ю́', 'Ю', $str);
            $str = str_replace('ю́', 'ю', $str);
            $str = str_replace('Ю̀', 'Ю', $str);
            $str = str_replace('ю̀', 'ю', $str);
            $str = str_replace('Ю̂', 'Ю', $str);
            $str = str_replace('ю̂', 'ю', $str);
            $str = str_replace('Ю̆', 'Ю', $str);
            $str = str_replace('ю̆', 'ю', $str);
            $str = str_replace('Ю̈', 'Ю', $str);
            $str = str_replace('ю̈', 'ю', $str);
            $str = str_replace('Ю̈́', 'Ю', $str);
            $str = str_replace('ю̈́', 'ю', $str);
            $str = str_replace('Ю̄', 'Ю', $str);
            $str = str_replace('ю̄', 'ю', $str);

            $str = str_replace('Я́', 'Я', $str);
            $str = str_replace('я́', 'я', $str);
            $str = str_replace('Я̀', 'Я', $str);
            $str = str_replace('я̀', 'я', $str);
            $str = str_replace('Я̆', 'Я', $str);
            $str = str_replace('я̆', 'я', $str);
            $str = str_replace('Я̈', 'Я', $str);
            $str = str_replace('я̈', 'я', $str);
            $str = str_replace('Я̄', 'Я', $str);
            $str = str_replace('я̄', 'я', $str);
        }

        //-------------------------------------------------


        $str = str_replace('Ȧ́', 'A', $str);
        $str = str_replace('ȧ́', 'a', $str);
        $str = str_replace('Ǡ', 'A', $str);
        $str = str_replace('ǡ', 'a', $str);
        $str = str_replace('Ꜳ̇', 'A', $str);
        $str = str_replace('ꜳ̇', 'a', $str);

        $str = str_replace('Ạ', 'A', $str);
        $str = str_replace('ạ', 'a', $str);
        $str = str_replace('Ạ́', 'A', $str);
        $str = str_replace('ạ́', 'a', $str);
        $str = str_replace('Ạ̀', 'A', $str);
        $str = str_replace('ạ̀', 'a', $str);
        $str = str_replace('Ậ', 'A', $str);
        $str = str_replace('ậ', 'a', $str);
        $str = str_replace('Ặ', 'A', $str);
        $str = str_replace('ặ', 'a', $str);
        $str = str_replace('Ạ̄', 'A', $str);
        $str = str_replace('ạ̄', 'a', $str);
        $str = str_replace('Ạ̃', 'A', $str);
        $str = str_replace('ạ̃', 'a', $str);
        $str = str_replace('Ạ̈', 'A', $str);
        $str = str_replace('ạ̈', 'a', $str);

        $str = str_replace('Ḃ', 'B', $str);
        $str = str_replace('ḃ', 'b', $str);

        $str = str_replace('Ḅ', 'B', $str);
        $str = str_replace('ḅ', 'b', $str);


        $str = str_replace('Ç̇', 'C', $str);
        $str = str_replace('ç̇', 'c', $str);

        $str = str_replace('C̣', 'C', $str);
        $str = str_replace('c̣', 'c', $str);
        $str = str_replace('Č̣', 'C', $str);



        $str = str_replace('Ḍ', 'D', $str);
        $str = str_replace('ḍ', 'd', $str);
        $str = str_replace('Ḍ́', 'D', $str);
        $str = str_replace('ḍ́', 'd', $str);
        $str = str_replace('ᴅ̣', 'd', $str);


        $str = str_replace('Ė̄', 'E', $str);
        $str = str_replace('ė̄', 'e', $str);
        $str = str_replace('Ė́', 'E', $str);
        $str = str_replace('ė́', 'e', $str);
        $str = str_replace('Ė̃', 'E', $str);
        $str = str_replace('ė̃', 'e', $str);
        $str = str_replace('ĕ̇', 'e', $str);

        $str = str_replace('Ẹ', 'E', $str);
        $str = str_replace('ẹ', 'e', $str);
        $str = str_replace('Ẹ́', 'E', $str);
        $str = str_replace('ẹ́', 'e', $str);
        $str = str_replace('Ẹ̀', 'E', $str);
        $str = str_replace('ẹ̀', 'e', $str);
        $str = str_replace('Ệ', 'E', $str);
        $str = str_replace('ệ', 'e', $str);
        $str = str_replace('Ẹ̆', 'E', $str);
        $str = str_replace('ẹ̆', 'e', $str);
        $str = str_replace('Ẹ̄', 'E', $str);
        $str = str_replace('ẹ̄', 'e', $str);
        $str = str_replace('Ẹ̃', 'E', $str);
        $str = str_replace('ẹ̃', 'e', $str);
        $str = str_replace('Ə̣', 'E', $str);
        $str = str_replace('ə̣', 'e', $str);
        $str = str_replace('Ə̣̀', 'E', $str);
        $str = str_replace('ə̣̀', 'e', $str);

        $str = str_replace('Ḟ', 'F', $str);
        $str = str_replace('ḟ', 'f', $str);

        $str = str_replace('F̣', 'F', $str);
        $str = str_replace('f̣', 'f', $str);

        $str = str_replace('Ġ', 'G', $str);
        $str = str_replace('ġ', 'g', $str);
        $str = str_replace('ġ̓', 'g', $str);

        $str = str_replace('G̣', 'G', $str);
        $str = str_replace('g̣', 'g', $str);

        $str = str_replace('Ḣ', 'H', $str);
        $str = str_replace('ḣ', 'h', $str);

        $str = str_replace('Ḥ', 'H', $str);
        $str = str_replace('ḥ', 'h', $str);

        $str = str_replace('İ', 'I', $str);
        $str = str_replace('i', 'i', $str);

        $str = str_replace('Ị', 'I', $str);
        $str = str_replace('ị', 'i', $str);
        $str = str_replace('Ị́', 'I', $str);
        $str = str_replace('ị́', 'i', $str);
        $str = str_replace('Ị̀', 'I', $str);
        $str = str_replace('ị̀', 'i', $str);
        $str = str_replace('Ị̂', 'I', $str);
        $str = str_replace('ị̂', 'i', $str);
        $str = str_replace('Ị̃', 'I', $str);
        $str = str_replace('ị̃', 'i', $str);

        $str = str_replace('J̣', 'J', $str);
        $str = str_replace('j̣', 'j', $str);

        $str = str_replace('J̣̌', 'J', $str);
        $str = str_replace('ǰ̣', 'j', $str);



        $str = str_replace('Ḳ', 'K', $str);
        $str = str_replace('ḳ', 'k', $str);
        $str = str_replace('Ḳ̄', 'K', $str);
        $str = str_replace('ḳ̄', 'k', $str);

        $str = str_replace('L̇', 'L', $str);
        $str = str_replace('l̇', 'l', $str);

        $str = str_replace('Ḷ', 'L', $str);
        $str = str_replace('ḷ', 'l', $str);
        $str = str_replace('Ḹ', 'L', $str);
        $str = str_replace('ḹ', 'l', $str);
        $str = str_replace('Ḹ́', 'L', $str);
        $str = str_replace('ḹ́', 'l', $str);
        $str = str_replace('Ḹ̀', 'L', $str);
        $str = str_replace('ḹ̀', 'l', $str);
        $str = str_replace('Ḷ́', 'L', $str);
        $str = str_replace('ḷ́', 'l', $str);
        $str = str_replace('Ḷ̀', 'L', $str);
        $str = str_replace('ḷ̀', 'l', $str);
        $str = str_replace('Ł̣', 'L', $str);
        $str = str_replace('ł̣', 'l', $str);
        $str = str_replace('Ḷ̓', 'L', $str);
        $str = str_replace('ḷ̓', 'l', $str);
        $str = str_replace('l̮̣̓', 'l', $str);
        $str = str_replace('ʟ̣̮̣̓', 'l', $str);



        $str = str_replace('Ṃ', 'M', $str);
        $str = str_replace('ṃ', 'm', $str);
        $str = str_replace('Ṃ́', 'M', $str);
        $str = str_replace('ṃ́', 'm', $str);
        $str = str_replace('Ṃ̓', 'M', $str);
        $str = str_replace('ṃ̓', 'm', $str);


        $str = str_replace('Ŋ̇Ŋ̇̓', 'N', $str);
        $str = str_replace('ŋ̇ŋ̇̓', 'n', $str);

        $str = str_replace('Ṇ', 'N', $str);
        $str = str_replace('ṇ', 'n', $str);
        $str = str_replace('Ṇ́', 'N', $str);
        $str = str_replace('ṇ́', 'n', $str);
        $str = str_replace('Ṇ̓', 'N', $str);
        $str = str_replace('ṇ̓', 'n', $str);
        $str = str_replace('ɴ̣', 'n', $str);



        $str = str_replace('Ȱ', 'O', $str);
        $str = str_replace('ȱ', 'o', $str);

        $str = str_replace('Ọ', 'O', $str);
        $str = str_replace('ọ', 'o', $str);
        $str = str_replace('Ộ', 'O', $str);
        $str = str_replace('ộ', 'o', $str);
        $str = str_replace('Ợ', 'O', $str);
        $str = str_replace('ợ', 'o', $str);
        $str = str_replace('Ọ́', 'O', $str);
        $str = str_replace('ọ́', 'o', $str);
        $str = str_replace('Ọ̀', 'O', $str);
        $str = str_replace('ọ̀', 'o', $str);
        $str = str_replace('Ọ̄', 'O', $str);
        $str = str_replace('ọ̄', 'o', $str);
        $str = str_replace('Ọ̆', 'O', $str);
        $str = str_replace('ọ̆', 'o', $str);
        $str = str_replace('Ŏ̤̣', 'O', $str);
        $str = str_replace('ŏ̤̣', 'o', $str);
        $str = str_replace('Ō̤̣', 'O', $str);
        $str = str_replace('ō̤̣', 'o', $str);
        $str = str_replace('Ọ̈', 'O', $str);
        $str = str_replace('ọ̈', 'o', $str);
        $str = str_replace('O̤̣', 'O', $str);
        $str = str_replace('o̤̣', 'o', $str);
        $str = str_replace('Ọ̃', 'O', $str);
        $str = str_replace('ọ̃', 'o', $str);
        $str = str_replace('Õ̤̣', 'O', $str);
        $str = str_replace('õ̤̣', 'o', $str);



        $str = str_replace('P̣', 'P', $str);
        $str = str_replace('p̣', 'p', $str);

        $str = str_replace('Q̇', 'Q', $str);
        $str = str_replace('q̇', 'q', $str);

        $str = str_replace('Ṙ', 'R', $str);
        $str = str_replace('ṙ', 'r', $str);

        $str = str_replace('Ṛ', 'R', $str);
        $str = str_replace('ṛ', 'r', $str);
        $str = str_replace('Ṛ́', 'R', $str);
        $str = str_replace('ṛ́', 'r', $str);
        $str = str_replace('Ṛ̀', 'R', $str);
        $str = str_replace('ṛ̀', 'r', $str);
        $str = str_replace('Ṝ', 'R', $str);
        $str = str_replace('ṝ', 'r', $str);
        $str = str_replace('Ṝ́', 'R', $str);
        $str = str_replace('ṝ́', 'r', $str);
        $str = str_replace('Ṝ̀', 'R', $str);
        $str = str_replace('ṝ̀', 'r', $str);


        $str = str_replace('Ṥ', 'S', $str);
        $str = str_replace('ṥ', 's', $str);
        $str = str_replace('Ṧ', 'S', $str);
        $str = str_replace('ṧ', 's', $str);
        $str = str_replace('Ṩ', 'S', $str);
        $str = str_replace('ṩ', 's', $str);

        $str = str_replace('Ṣ', 'S', $str);
        $str = str_replace('ṣ', 's', $str);
        $str = str_replace('Ṩ', 'S', $str);
        $str = str_replace('ṩ', 's', $str);
        $str = str_replace('Ṣ̄', 'S', $str);
        $str = str_replace('ṣ̄', 's', $str);
        $str = str_replace('Ṣ̌', 'S', $str);
        $str = str_replace('ṣ̌', 's', $str);
        $str = str_replace('Ṣ̤', 'S', $str);
        $str = str_replace('ṣ̤', 's', $str);
        $str = str_replace('Ṣ̱', 'S', $str);
        $str = str_replace('ṣ̱', 's', $str);



        $str = str_replace('Ṭ', 'T', $str);
        $str = str_replace('ṭ', 't', $str);
        $str = str_replace('Ṭ̈', 'T', $str);
        $str = str_replace('ṭ̈', 't', $str);
        $str = str_replace('Ṭ̤', 'T', $str);
        $str = str_replace('ṭ̤', 't', $str);
        $str = str_replace('Ṭ́', 'T', $str);
        $str = str_replace('ṭ́', 't', $str);
        $str = str_replace('ṭ̓́', 't', $str);



        $str = str_replace('Ụ', 'U', $str);
        $str = str_replace('ụ', 'u', $str);
        $str = str_replace('Ự', 'U', $str);
        $str = str_replace('ự', 'u', $str);
        $str = str_replace('Ụ̄', 'U', $str);
        $str = str_replace('ụ̄', 'u', $str);
        $str = str_replace('Ụ́', 'U', $str);
        $str = str_replace('ụ́', 'u', $str);
        $str = str_replace('Ụ̀', 'U', $str);
        $str = str_replace('ụ̀', 'u', $str);
        $str = str_replace('Ụ̂', 'U', $str);
        $str = str_replace('ụ̂', 'u', $str);
        $str = str_replace('Ụ̈', 'U', $str);
        $str = str_replace('ụ̈', 'u', $str);
        $str = str_replace('Ụ̃', 'U', $str);
        $str = str_replace('ụ̃', 'u', $str);

        $str = str_replace('Ṿ', 'V', $str);
        $str = str_replace('ṿ', 'v', $str);



        $str = str_replace('Ẉ', 'W', $str);
        $str = str_replace('ẉ', 'w', $str);


        $str = str_replace('ꭓ̇', 'X', $str);

        $str = str_replace('X̣', 'X', $str);
        $str = str_replace('ꭓ̣', 'X', $str);
        $str = str_replace('x̣', 'x', $str);



        $str = str_replace('Ỵ', 'Y', $str);
        $str = str_replace('ỵ', 'y', $str);



        $str = str_replace('Ẓ', 'Z', $str);
        $str = str_replace('ẓ', 'z', $str);
        $str = str_replace('Ẓ̌', 'Z', $str);
        $str = str_replace('ẓ̌', 'z', $str);
        $str = str_replace('ǯ̣', 'Z', $str);
        $str = str_replace('Ø', 'O', $str);
        $str = str_replace('ø', 'o', $str);
        $str = str_replace("ʻ", "'", $str);
        $str = str_replace("‘", "'", $str);
        $str = str_replace("ʼ", "'", $str);




        return $str;
    }
}