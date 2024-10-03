<?php

namespace App\Repositories\Base;

use App\Repositories\BaseRepository;

class ParseRepository
{
    static $exceptTags = ['<b>','</b>','<p>','</p>','<br>','<br/>','<br />','<i>','</i>','<h1>','</h1>','<h2>','</h2>','<h3>','</h3>','<h4>','</h4>','<h5>','</h5>','<h6>','</h6>','<a>','</a>','<li>','</li>','<ul>','</ul>','<ol>','</ol>','<span>','</span>'];

    static function clearEmptyTags($str)
    {
        $str = str_replace('<li> <li>', ' ', $str);
        return !empty($str) ? preg_replace('/<(\w+)\b(?:\s+[\w\-.:]+(?:\s*=\s*(?:"[^"]*"|"[^"]*"|[\w\-.:]+))?)*\s*\/?>\s*<\/\1\s*>/', ' ', $str) : '';
    }

    static function tidyRepair($str, $exceptTags = [])
    {
        $exceptTags = !empty($exceptTags) && is_array($exceptTags) ? $exceptTags : ($exceptTags === false ? [] : self::$exceptTags);
        $str = htmlspecialchars_decode($str);
        $str = StringRepository::addSpaceBetweenTags($str);
        $str = tidy_repair_string($str, [
            'clean'       => 1,
            'doctype'     => 'omit',
            'indent'      => 2,
            'output-html' => 1,
            'tidy-mark'   => 0,
            'wrap'        => 0,
            'show-body-only' => 1,
        ]);

        if(empty(BaseRepository::stripTags($str))){
            return '';
        }

        $str = BaseRepository::stripTags($str, implode('', $exceptTags));
        $str = StringRepository::clearSpace($str);
        $str = ParseRepository::clearEmptyTags($str);

        $str = StringRepository::addSpaceBetweenTags($str);
        $str = StringRepository::clearSpace($str);

        return $str;
    }

    static function convertToSimpleHtml($str, $exceptTags = [])
    {
        $exceptTags = !empty($exceptTags) && is_array($exceptTags) ? $exceptTags : ($exceptTags === false ? [] : self::$exceptTags);
        $str = htmlspecialchars_decode($str);
        $str = StringRepository::addSpaceBetweenTags($str);
        $str = tidy_repair_string($str, [
            'clean'       => 1,
            'doctype'     => 'omit',
            'indent'      => 2,
            'output-html' => 1,
            'tidy-mark'   => 0,
            'wrap'        => 0,
            'show-body-only' => 1,
        ]);
        $str = ParseRepository::replaceTagInText(['<!--', '-->'], $str);
        $str = ParseRepository::replaceTagInText([' style="', '"'], $str);
        $str = ParseRepository::replaceTagInText([' class="', '"'], $str);
        $str = ParseRepository::replaceTagInText('style', $str);

        if(empty(BaseRepository::stripTags($str))){
            return '';
        }

        $str = BaseRepository::stripTags($str, implode('', $exceptTags));
        $str = StringRepository::clearSpace($str);
        $str = ParseRepository::clearEmptyTags($str);

        $str = StringRepository::addSpaceBetweenTags($str);
        $str = StringRepository::clearSpace($str);

        return $str;
    }

    static function checkTagParam($tag, $toReg = false, $equals = false)
    {
        if(!is_array($tag)){
            if($toReg){
                if($equals){
                    $tag = ['<'.$tag.'>', '<\/'.$tag.'>'];
                }else{
                    $tag = ['<\s*?'.$tag.'\b[^>]*>', '</'.$tag.'\b[^>]*>'];
                }
            }else{
                $equals = $equals === false ? '>' : $equals;
                $tag = ['<'.$tag.$equals, '</'.$tag.'>'];
            }
        }

        return $tag;
    }

    static function getHtmlArrayBetweenTag($tag, $str, $openTagAppend = '>')
    {
        $resArray = [];
        $globalOffset = 0;
        $strLen = mb_strlen($str);

        while (true) {
            if($globalOffset >= $strLen - 1){
                break;
            }
            $item = self::getHtmlBetweenTag($tag, $str, $openTagAppend, $globalOffset);
            if(empty($item)){
                break;
            }
            if(mb_strlen($item) > 1){
                $resArray[] = $item;
            }
        }
        return $resArray;
    }

    /*
        dd(self::getAllBetweenTag('span', '<span class="st">111<span class="st"><span class="st">111<span class="st">111<span class="st">222</span>333</span></span><span class="st">111</span>222</span>333</span>', ' class="st">'));
        dd(self::getAllBetweenTag('div', '<div>111<div><div>111</div><div>111</div>222</div>333</div>'));
        dd(self::getAllBetweenTag('div', '<div>111<div>222</div>333</div>'));
        dd(self::getAllBetweenTag('div', '<div>111</div>222<div>333</div>'));
    */
    static function getHtmlBetweenTag($tag, $str, $openTagAppend = '>', &$globalOffset = 0)
    {
        if(empty($str)){
            return [];
        }

        if($globalOffset > 0){
            $str = mb_substr($str, $globalOffset);
        }

        $tag = self::checkTagParam($tag, false, '');
        $tagOrigin = $tag;

        $getFrom = false;
        $getTo = false;
        $countOpenTagsTmp = 0;
        $countOpenTags = 0;
        $countCloseTags = 0;

        $offset = 0;
        $step = 0;
        while (true) {
            $tag[0] = $tagOrigin[0];
            if(!$step){
                $tag[0] .= $openTagAppend;
            }
            $openTagIndex = mb_strpos($str, $tag[0], $offset);
            $closeTagIndex = mb_strpos($str, $tag[1], $offset);
            if($closeTagIndex === false || $openTagIndex === false){
                break;
            }
            $getTo = $closeTagIndex;

            if($openTagIndex !== false && ($getFrom === false || $openTagIndex < $closeTagIndex)){
                $countOpenTags++;
                $countOpenTagsTmp++;
                $offset = $openTagIndex + 1;
                if($getFrom === false){
                    $getFrom = $openTagIndex;
                }
            }else if($getFrom !== false){
                $countOpenTagsTmp--;
                $countCloseTags++;
                $offset = $closeTagIndex + 1;
                /*if($isLog && $countOpenTagsTmp <= 0){
                    dd(compact('tag', 'countOpenTagsTmp', 'countOpenTags', 'countCloseTags', 'offset', 'openTagIndex', 'closeTagIndex', 'getFrom', 'getTo', 'str'));
                }*/
            }

            if($getFrom !== false && $countOpenTags == $countCloseTags && $countOpenTagsTmp <= 0){
                break;
            }

            if($getFrom !== false) {
                $step++;
            }
        }

        if($getFrom === false || $getTo === false){
            return '';
        }

        $getFrom = $getFrom + mb_strlen($tag[0].$openTagAppend);
        $globalOffset = $globalOffset + $getTo + 1;

        $res = mb_substr($str, $getFrom);
        $getTo = $getTo - $getFrom;
        $res = mb_substr($res, 0, $getTo);

        return $res;
    }

    static function getTextInTag($tag, $str, $equals = false)
    {
        if(empty($str)){
            return [];
        }

        $tag = self::checkTagParam($tag, true, $equals);
        preg_match_all('#'.$tag[0].'(.*?)'.$tag[1].'#siu', $str, $matches);

        return !empty($matches) && !empty($matches[1]) ? BaseRepository::clearEmptyElementsInArray($matches[1]) : [];
    }

    static function replaceBetweenTag($tag, $str, $openTagAppend = '>', $replaceTo = '')
    {
        $arrSearch = self::getHtmlArrayBetweenTag($tag, $str, $openTagAppend);
        foreach ($arrSearch as $i => $v){
            $str = preg_replace('#'.preg_quote($v).'#siu', $replaceTo, $str);
        }
        return $str;
    }

    static function replaceTagInText($tag, $text, $replaceTo = '', $equals = false)
    {
        $tag = self::checkTagParam($tag, true, $equals);
        $text = preg_replace('#'.$tag[0].'(.*?)'.$tag[1].'#siu', $replaceTo, $text);
        return $text;
    }

    static function wrapTextToTag($tag, $text)
    {
        $tag = self::checkTagParam($tag);
        return $tag[0].$text.$tag[1];
    }

    static function stripTagsInTag($tag, $str)
    {
        $tag = self::checkTagParam($tag);

        $str = self::explode($tag[0], -1, $str);
        foreach ($str as $i => $v){
            if($i > 0){
                $str[$i] = self::explode($tag[1], -1, $v);
                if(!empty($str[$i][0])){
                    $str[$i][0] = self::wrapTextToTag($tag, StringRepository::clearSpace(BaseRepository::stripTags($str[$i][0])));
                }
                $str[$i] = StringRepository::implode('', $str[$i]);
            }
        }
        return StringRepository::implode('', $str);
    }

    static function explode($delimiter, $getElement, $str, $returnFalseIfNotExist = true)
    {
        if(empty($str)){
            return $getElement < 0 && !is_array($str) ? [] : (!empty($str) ? $str : '');
        }
        $str = explode($delimiter, $str);

        if($returnFalseIfNotExist && $getElement > 0 && empty($str[$getElement])){
            return false;
        }
        if ($getElement < 0) {
            if(empty($str)){
                return false;
            }
            if($getElement == -2){
                if(is_array($str) && isset($str[0])){
                    array_splice($str, 0, 1);
                }
            }
            return !is_array($str) ? [] : $str;
        }
        return !empty($str[$getElement]) ? $str[$getElement] : $str[0];
    }

    static function getSlugFromUrl($url)
    {
        if(empty($url)){
            return null;
        }
        $url = explode('/', $url);
        $count = count($url);
        if(!empty($url[$count-1]) && $count > 3){
            return trim($url[$count-1]);
            //return preg_replace('/[^a-zA-Z0-9-]/', '', Str::before($url[$count-1], '.'));
        }else if(!empty($url[$count-2]) && $count > 4){
            return trim($url[$count-2]);
            //return preg_replace('/[^a-zA-Z0-9-]/', '', Str::before($url[$count-2], '.'));
        }
        return null;
    }

    //-------------------------------------------------

    static function tagToLower($str)
    {
        if(!empty($str)){
            foreach (self::$exceptTags as $item){
                $str = str_replace(mb_strtoupper($item), mb_strtolower($item), $str);
            }

            $str = str_replace('<br><br><br>', '<br>', $str);
            $str = str_replace('<br><br>', '<br>', $str);

            $str = str_replace(' <b> </b> ', '', $str);
            $str = str_replace('<b> </b>', '', $str);
            $str = str_replace('<b>-</b>', '', $str);
            $str = str_replace('<b> - </b>', '', $str);

            $str = str_replace(' <p> </p> ', '', $str);
            $str = str_replace('<p> </p>', '', $str);
            $str = str_replace('<p>-</p>', '', $str);
            $str = str_replace('<p> - </p>', '', $str);

            $str = str_replace('<br><br><br>', '<br>', $str);
            $str = str_replace('<br><br>', '<br>', $str);

            $str = str_replace('    ', ' ', $str);
            $str = str_replace('   ', ' ', $str);
            $str = str_replace('  ', ' ', $str);
            $str = str_replace('  ', ' ', $str);
        }

        return $str;
    }

    static function convertToArray($str)
    {
        $str = preg_replace('/\s\s+/', ' ', str_replace(',', ' ', $str));
        $arr = [];
        foreach (explode(' ', $str) as $item){
            $item = trim($item);

            if(!empty($item)){
                $arr[] = $item;
            }
        }
        return array_values(array_unique(array_filter($arr)));
    }
}