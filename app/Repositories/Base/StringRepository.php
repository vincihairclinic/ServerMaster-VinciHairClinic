<?php

namespace App\Repositories\Base;

use App\AppConf;
use App\Repositories\BaseRepository;
use ForceUTF8\Encoding;

class StringRepository extends StringClearRepository
{
    static $abc = [
        'en' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
        'ru' => ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'],
        'zh' => ['安', '吧', '八', '爸', '百', '北', '不', '大', '岛', '的', '弟', '地', '东', '都', '对', '多', '儿', '二', '方', '港', '哥', '个', '关', '贵', '国', '过', '海', '好', '很', '会', '家', '见', '叫', '姐', '京', '九', '可', '老', '李', '零', '六', '吗', '妈', '么', '没', '美', '妹', '们', '名', '明', '哪', '那', '南', '你', '您', '朋', '七','起','千','去','人','认','日','三','上','谁','什','生','师','十','识','是','四','他','她','台','天','湾','万','王','我','五','西','息','系','先','香','想','小','谢','姓','休','学','也','一','亿','英','友','月','再','张','这','中','字'],
        'de' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Ä', 'Ö', 'Ü', 'ß'],
        'es' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Ñ'],
        'fr' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
        'hi' => ['अ', 'आ', 'इ', 'ई', 'उ', 'ऊ', 'ए', 'ऐ', 'ओ', 'औ', 'क', 'ख', 'ग', 'घ', 'ङ', 'च', 'छ', 'ज', 'झ', 'ञ', 'ट', 'ठ', 'ड', 'ढ', 'ण', 'त', 'थ', 'द', 'ध', 'न', 'प', 'फ', 'ब', 'भ', 'म', 'य', 'र', 'ल', 'व', 'श', 'ष', 'स', 'ह'],
        'it' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
        'pl' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Ł', 'Ń', 'Ó', 'Ę', 'Ć', 'Ą', 'Ś', 'Ź', 'Ż'],

        'num' => ['1', '2', '3', '4', '5', '6', '7', '8', '9'],
    ];



    static $plChars = 'äöüÄÖÜßąĄćĆęĘłŁńŃóÓśŚźŹżŻŠŽšžŸŔÁÂĂÄĹÇČÉĘËĚÍÎĎŃŇÓÔŐÖŘŮÚŰÜÝŕáâăäĺçčéęëěíîďńňóôőöřůúűüý˙ŢţĐđßŒœĆćľ';
    const CYRILLIC_CHARS = 'абвгдежзийклмнопрстуфхцчшщюяьыёъэїіє';

    static function getOnlyCharsLen($str)
    {
        return mb_strlen(preg_replace('/[^a-z'.self::$plChars.self::CYRILLIC_CHARS.']/i', '', strip_tags($str)));
    }

    static function removeLastWord($str)
    {
        $str = trim($str);
        if(!empty($str) && mb_stripos($str, ' ') !== false){
            $str = ParseRepository::explode(' ', -1, $str);
            $str = array_slice($str, 0, -1);
            $str = implode(' ', $str);
        }
        return $str;
    }

    static function cyrillicChars($register = 'lower|upper')
    {
        if($register == 'lower'){
            return self::CYRILLIC_CHARS;
        }else if($register == 'upper'){
            return mb_strtoupper(self::CYRILLIC_CHARS);
        }
        return self::CYRILLIC_CHARS.mb_strtoupper(self::CYRILLIC_CHARS);
    }

    static function addSpaceBetweenTags($str)
    {
        $str = str_replace('>', '> ', $str);
        $str = str_replace('<', ' <', $str);
        return $str;
    }

    static function strReplaceFirstOnly($search, $replace, $subject, $withRegister = true)
    {
        if($withRegister){
            $pos = mb_strpos($subject, $search);
        }else{
            $pos = mb_stripos($subject, $search);
        }
        if ($pos !== false) {
            return substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }

    static function getMbStrlen($str)
    {
        $countX2 = mb_strlen(preg_replace("/[a-zA-Zа-яА-ЯіIїЇёЁ\s0-9-.,!?:]/iu", "", $str));
        $countAll = mb_strlen($str);
        return (($countAll - $countX2) + ($countX2 * 2));
    }

    static function strimwidth($str, $maxLen, $simpleStrLen = true)
    {
        if(empty($str)){
            return '';
        }
        $str = explode(' ', $str);
        $rStr = '';
        foreach ($str as $v){
            if($simpleStrLen){
                if(mb_strlen($rStr.' '.$v) <= $maxLen){
                    $rStr .= ' '.$v;
                }
            }else{
                if(StringRepository::getMbStrlen($rStr.' '.$v) <= $maxLen){
                    $rStr .= ' '.$v;
                }
            }
        }
        $str = $rStr;
        $str = trim($str, '-');
        $str = trim($str, ',');
        $str = StringClearRepository::clearDblSpace($str);
        $str = trim($str, ',');
        $str = trim($str, '-');
        $str = StringClearRepository::clearDblSpace($str);
        return mb_strimwidth($str, 0, $maxLen, '');
    }

    static function implode($glue, $arr = [])
    {
        if(empty($arr)){
            return '';
        }
        return is_array($arr) ? implode($glue, $arr) : '';
    }

    /*static function slug($str, $model = null, $ignoreId = null, $field = 'slug')
    {
        if(empty($str)){
            $str = microtime(true);
        }
        $str = self::replace('.', $str, '-');
        $str = self::replace('и', $str, 'ы');
        $str = Str::slug($str);
        if(!empty($model)){
            $query = $model::where($field, $str);
            if(!empty($ignoreId)){
                $query->where('id', '!=', $ignoreId);
            }
            if($query->first()){
                $str .= '-'.self::replace('.', microtime(true), '-');
            }
        }
        return $str;
    }*/

    static function urldecode($url)
    {
        return str_replace(['%3A', '%2F'], [':', '/'], urlencode($url));
    }

    static function pregQuote($str)
    {
        $str = preg_quote($str);
        $str = str_replace(' ', '\s', $str);
        return $str;
    }

    static function substrCount($search, $str, $isSearchRegular = false)
    {
        if(!$isSearchRegular) {
            $search = self::pregQuote($search);
            $search = '[\W]'.$search.'[\W]';
        }
        preg_match_all('#'.$search.'#siu', $str, $matches);
        return count($matches[0]);
    }

    static function contains($search, $str, $isSearchRegular = false)
    {
        if(!$isSearchRegular) {
            $search = self::pregQuote($search);
        }
        preg_match('#'.$search.'#siu', ' '.$str.' ', $matche);
        return !empty($matche);
    }

    static function startsWith($search, $str, $isSearchRegular = false)
    {
        if(!$isSearchRegular){
            $search = self::pregQuote($search);
        }
        preg_match('#^'.$search.'#siu', $str, $matche);
        return !empty($matche);
    }

    static function endsWith($search, $str, $isSearchRegular = false)
    {
        if(!$isSearchRegular) {
            $search = self::pregQuote($search);
        }
        preg_match('#'.$search.'$#siu', $str, $matche);
        return !empty($matche);
    }

    static function replaceBegin($search, $str, $replaceTo = '', $isSearchRegular = false)
    {
        $originStr = $str;
        if($search == $str){
            return $str;
        }
        if(!$isSearchRegular) {
            $search = self::pregQuote($search);
        }
        $str = preg_replace('#^'.$search.'#siu', $replaceTo, $str);
        $str = StringClearRepository::clearDblSpace($str);
        return !empty($str) ? $str : $originStr;
    }

    static function replaceEnd($search, $str, $replaceTo = '', $isSearchRegular = false)
    {
        if(!$isSearchRegular) {
            $search = self::pregQuote($search);
        }
        $str = preg_replace('#'.$search.'$#siu', $replaceTo, $str);
        $str = StringClearRepository::clearDblSpace($str);
        return $str;
    }

    static function replaceWithout($search, $str, $replaceTo = '', $isSearchRegular = false, $without = '')
    {
        return self::replace($search, $str, $replaceTo, $isSearchRegular, $without);
    }

    static function replaceAllWithout($str, $replaceTo = '', $without = '')
    {
        return self::replace('(.*?)', $str, $replaceTo, true, $without);
    }

    static function getOnlyChars($str)
    {
        if(empty($str)){
            return $str;
        }

        $str = self::replaceWithoutWord($str, '', false);
        $str = str_replace('-', ' ', $str);
        $str = str_replace('.', ' ', $str);
        $str = self::clearDblSpace($str);
        return $str;
    }

    static function replaceWithoutWord($str, $replaceTo = ' ', $withoutNumbers = true, $withBaseTextChars = false)
    {
        //$without = 1234567890

        $str = str_replace('#', ' ', $str);
        $str = str_replace('—', '-', $str);
        $str = trim($str, '-');

        $withBaseTextChars = !empty($withBaseTextChars) ? (is_string($withBaseTextChars) ? $withBaseTextChars : '?,:\&') : '';

        if(!empty(AppConf::$not_use_latin_and_cyrillic)){
            if($withoutNumbers === true){
                $str =  self::replace('[\W\d1234567890.-'.$withBaseTextChars.']', $str, $replaceTo, true, '1234567890.-'.$withBaseTextChars);
            }else{
                $str = preg_replace('/[\W\d-]/iu', '', $str);
            }
        }else{
            if(!empty($withoutNumbers) && is_string($withoutNumbers)){
                $str = preg_replace('/[^'.$withoutNumbers.'a-zA-Zа-яА-ЯіIїЇёЁ\s-]/iu', '', $str);
            }else if($withoutNumbers === true){
                $str =  self::replace('[\W\d1234567890.-'.$withBaseTextChars.']', $str, $replaceTo, true, '1234567890.-'.$withBaseTextChars);
                //$str =  self::replaceAllWithout($str, $replaceTo, '[a-z'.StringRepository::cyrillicChars().'-]');
            }else{
                $str = preg_replace('/[^a-zA-Zа-яА-ЯіIїЇёЁ\s-]/iu', '', $str);
            }
        }

        return $str;
        //return self::replaceAllWithout($str, $replaceTo, '[a-z'.StringRepository::cyrillicChars().'-]');
    }

    static function replace($search, $str, $replaceTo = '', $isSearchRegular = false, $without = '')
    {
        if(empty($str)){
            return '';
        }

        if(is_array($search)){
            foreach ($search as $s){
                $str = self::replace($s, $str, $replaceTo, $isSearchRegular, $without);
            }
            return $str;
        }

        $replaceTo = is_array($replaceTo) ? $replaceTo[rand(0, count($replaceTo)-1)] : $replaceTo;

        if(empty($search)){
            if(empty($without)){
                $without = '[a-z'.StringRepository::cyrillicChars().'-]';
            }
            $search = '(.*?)';
            $isSearchRegular = true;
        }

        if(!$isSearchRegular) {
            $search = self::pregQuote($search);
        }

        if(!empty($without)){
            preg_match_all('#'.$search.'#siu', $str, $matches);
            foreach ($matches[0] as $i => $v){
                $v = trim($v);
                if(empty($v) || mb_strripos($without, $v) !== false){
                    $matches[0][$i] = false;
                }else{
                    $matches[0][$i] = $v;
                }
            }

            if(empty($matches) || empty($matches[0])){
                return $str;
            }
            $matches = BaseRepository::clearEmptyElementsInArray($matches[0], true);

            $search = self::implode('', $matches);
            $search = StringClearRepository::clearDblSpace($search);
            $search = self::replace(' ', $search);

            if(empty($search)){
                return $str;
            }
            $search = '['.self::pregQuote($search).']';
        }


        $str = preg_replace('#'.Encoding::toUTF8($search).'#siu', $replaceTo, $str);
        $str = StringClearRepository::clearDblSpace($str);

        return $str;
    }

    static function getBetween($searchFrom, $searchTo, $str, $isSearchRegular = false, $ignoreCase = true)
    {
        if(empty($str)){
            return '';
        }
        if(!$isSearchRegular) {
            if(!empty($searchFrom)){
                $searchFrom = self::pregQuote($searchFrom);
            }
            if(!empty($searchTo)){
                $searchTo = self::pregQuote($searchTo);
            }
        }
        if(!empty($searchFrom)) {
            $str = preg_replace('#^(.*?)' . $searchFrom . '#' . (!empty($ignoreCase) ? 'i' : '') . 'us', '', $str);
        }
        if(!empty($searchTo)) {
            $str = preg_replace('#' . $searchTo . '(.*?)$#' . (!empty($ignoreCase) ? 'i' : '') . 'us', '', $str);
        }
        $str = StringClearRepository::clearDblSpace($str);
        return $str;
    }

    static function uniqueChars($str)
    {
        preg_match_all('#(.*?)#siu', $str, $matches);
        if(!empty($matches[0])){
            $matches = BaseRepository::clearEmptyElementsInArray($matches[0], true);
            return implode('', $matches);
        }
        return $str;
    }

    static function mbUcfirstWords($string, $firstWord = false, $enc = 'UTF-8')
    {
        $string = explode(' ', $string);
        foreach ($string as $i => $item){
            $item = mb_strtolower($item);
            if($firstWord != -1 && (mb_strlen($item) > 2 || $i == 0)){
                $string[$i] = BaseRepository::mbUcfirst($item, $enc);
                if($firstWord){
                    $firstWord = -1;
                }
            }else{
                $string[$i] = $item;
            }
        }

        return implode(' ', $string);
    }

    static function splitHalf($string, $center = 0.5) {
        $length2 = strlen($string) * $center;
        $tmp = explode(' ', $string);
        $index = 0;
        $result = ['', ''];
        foreach($tmp as $word) {
            if(!$index && strlen($result[0]) > $length2) $index++;
            $result[$index] .= $word.' ';
        }
        return $result;
    }

    //-----------------------------------------------------------------

    static function strpos($value, $chars = [])
    {
        foreach ($chars as $char) {
            if($str = strpos($value, $char)){
                $value = mb_substr($value, 0, $str);
            }
        }
        return $value;
    }

    //---------------------------------------------------------

    static function allArrayPermutations ($items, $perms = array(), &$result = []) {
        $firstRun = empty($result) ? true : false;
        if (empty($items)) {
            $tmp_result = '';
            foreach ($perms as $perm){
                $tmp_result .=  !empty($perm) && empty(stristr($tmp_result, $perm)) ? ' ' . $perm : '';
            }
            $result[] = $tmp_result;
        } else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                self::allArrayPermutations($newitems, $newperms, $result);
            }
        }
        if($firstRun){
            return implode(', ', $result);
        }
    }
}