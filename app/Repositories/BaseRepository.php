<?php

namespace App\Repositories;

use App\AppConf;
use App\Application;
use App\Repositories\Base\StringClearRepository;
use App\Repositories\Base\StringRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class BaseRepository
{
    static function convertArrayItemToInt($arr = [])
    {
        $result = [];
        foreach ($arr as $v){
            $result[] = (int)$v;
        }
        return $result;
    }

    static function convertArrayToObject($arr, $requiredId = false)
    {
        $result = [];
        foreach ($arr as $v){
            $v = (object)$v;
            if ($requiredId && empty($v->id)){
                $v->id = 0;
            }
            $result[] = $v;
        }
        return $result;
    }

    static function getValueOrNull($value)
    {
        return !empty($value) || $value === 0 ? $value : null;
    }

    static function isValidImageUrl($file) {
        if(empty($file)){
            return false;
        }
        try {
            $size = getimagesize($file);
        }catch (Exception $e){
            return false;
        }
        if(empty($size)){
            return false;
        }
        return (strtolower(substr($size['mime'], 0, 5)) == 'image' ? true : false);
    }

    static function mbUcfirst($string, $strtolower = false, $enc = 'UTF-8')
    {
        if($strtolower){
            $string = mb_strtolower($string);
        }
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) . mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }

    static function price($expression)
    {
        $expression = explode('.', (string)$expression);
        $pennies = '';
        if(!empty($expression[1])){
            if(strlen($expression[1]) == 1){
                $pennies = '.'.$expression[1].'0';
            }
        }else if(!empty($expression[0])){
            $pennies = '.00';
        }
        return $expression[0].$pennies;
    }

    static function age($birthday_at)
    {
        return !empty($birthday_at) ? (new \DateTime($birthday_at))->diff(new \DateTime('now'))->format('%y') : null;
    }

    static function slugZh($string)
    {
        $slug = mb_strtolower(
            preg_replace('/([?]|\p{P}|\s)+/u', '-', $string)
        );
        return trim($slug, '-');
    }

    static function slug($str, $default = false, $extension = '', $maxLength = 70)
    {
        if(!empty($str)){
            $str = str_replace('.', '-', $str);
            $str = str_replace('/', '-', $str);
            $str = str_replace('|', '-', $str);
            $str = preg_replace('/\b(\w+)\b(?=.*?\b\1\b)/ius', ' ', $str);
            $str = preg_replace('|\+\'&@#\/%?=~_$!:,.;_{}()\[\]«»“„"~`*|', '', $str);
            $str = str_replace('---', '-', $str);
            $str = str_replace('--', '-', $str);
            $str = trim($str);
            $str = mb_strimwidth($str, 0, $maxLength);
        }

        if($str != '-' && !empty($str)){
            if(!empty(AppConf::$not_use_latin_and_cyrillic)){
                $str = self::slugZh($str);
            }else{
                $str = Str::slug($str);
            }
        }

        $str = !empty($str) ? $str : ($default === false ? substr((string)time(), 3).''.rand(111111,999999) : $default);
        return Str::finish($str, $extension);
    }

    static function paginate($model, $parametersPaginate = [])
    {
        $parametersPaginate = self::parametersPaginate($parametersPaginate);
        return $model->paginate($parametersPaginate['per_page'], ['*'], 'page', $parametersPaginate['page']);
    }

    static function parametersPaginate($request)
    {
        if($request->all){
            $request->per_page = 999999;
        }
        return [
            'page'     => $request->page ? $request->page : 1,
            'per_page' => $request->per_page ? $request->per_page : config('models.*.per_page', 15),
        ];
    }

    static function prepareLoadRelation($data = [], $prefix = '', $only = false)
    {
        if(empty($data) || empty($prefix)){
            return $data;
        }
        $data = collect($data)->map(function ($item, $key) use ($prefix){
            return $prefix.'.'.$item;
        })->all();
        return $only ? $data : array_merge([$prefix], $data);
    }

    static function echoLog($value, $withoutDate = false)
    {
        if(Application::$isConsole){
            if($withoutDate){
               echo $value;
            }else{
                echo "\n".Carbon::now()->format('m-d H:i:s').' | '.$value;
            }
        }
    }

    static function sendCurlGetRequest($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    static function clearEmptyElementsInArray($arr, $unique = false, $toLowerCaseAll = false)
    {
        if (!empty($arr) && is_array($arr)) {
            if($toLowerCaseAll){
                foreach ($arr as $i => $item){
                    $item = StringRepository::clearSpace($item);
                    if(!empty($item)){
                        $arr[$i] = mb_strtolower($item);
                    }else{
                        $arr[$i] = null;
                    }
                }
            }

            if($unique && is_array($arr)){
                $arr = array_unique($arr);
            }
            return array_values(array_filter($arr, function ($item) {
                return !empty($item);
            }));
        }
        return [];
    }

    //------------------------------------------

    static function notEmptyVar($var, $methods = [], $isset = false)
    {
        if(($isset && isset($var)) || !empty($var)){
            if(!is_array($methods)){
                $methods = [$methods];
            }

            foreach ($methods as $j => $methodsTmp){
                $varTmp = $var;
                $methodsTmp = explode('.', $methodsTmp);
                $methodsTmpCount = count($methodsTmp);
                if(($isset && isset($varTmp)) || !empty($varTmp)){
                    foreach ($methodsTmp as $i => $method){
                        if(
                            ($isset && (
                                is_array($varTmp) ? isset($varTmp[$method]) : isset($varTmp->{$method})
                                )
                            ) || (
                            is_array($varTmp) ? !empty($varTmp[$method]) : !empty($varTmp->{$method})
                            )
                        ){
                            if($i >= $methodsTmpCount - 1){
                                break;
                            }
                            $varTmp = is_array($varTmp) ? $varTmp[$method] : $varTmp->{$method};
                        }else{
                            return false;
                        }
                    }
                }
            }
        }else{
            return false;
        }
        return true;
    }

    static function issetVar($var, $methods = '')
    {
        return self::notEmptyVar($var, $methods, true);
    }

    static function stripTagsWithContent($str)
    {
        preg_match_all("|<[^>]+>(.*)</[^>]+>|U", $str, $matches);
        $str = StringRepository::implode(' ', $matches);
        $str = StringClearRepository::clearDblSpace($str);
        return $str;
    }

    static function stripTags($str, $allowableTags = null)
    {
        if(empty($str)){
            return $str;
        }
        $str = StringRepository::addSpaceBetweenTags($str);
        $str = strip_tags($str, $allowableTags);
        $str = StringClearRepository::clearDblSpace($str);
        return $str;
    }
}