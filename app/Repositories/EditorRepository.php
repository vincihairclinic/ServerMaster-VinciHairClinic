<?php

namespace App\Repositories;

use App\AppConf;
use App\Repositories\Base\CurlRepository;
use App\Repositories\Base\FileRepository;
use App\Repositories\Base\StringClearRepository;
use Illuminate\Support\Str;

class EditorRepository
{
    static function getImageFromResponse($url = null, $response = null)
    {
        $imageUrl = null;
        if(!empty($url)){
            $response = CurlRepository::sendCurl($url);
        }

        if(!empty($response)){
            preg_match('#<meta property="og:image" content="(.+)>#iU', $response, $matches);
            if (!empty($matches) && !empty($matches[1])) {
                if(BaseRepository::isValidImageUrl($matches[1])) {
                    $imageUrl = $matches[1];
                }
            }

            if(empty($imageUrl)){
                preg_match_all('#http[s]?://\S+\.(jpeg|jpg|gif|png)#iU', $response, $matches);
                if(!empty($matches) && !empty($matches[0])) {
                    foreach ($matches[0] as $match){
                        if(BaseRepository::isValidImageUrl($match)){
                            $imageUrl = $match;
                            break;
                        }
                    }
                }
            }

        }

        return $imageUrl;
    }

    static function clearHtml($str = '', $allowableTags = '', $removeLineFeeds = '')
    {
        if(!empty($str)){
            $str = BaseRepository::stripTags($str, $allowableTags);
            $str = StringClearRepository::clearHex($str);
            $str = StringClearRepository::clearDblSpace($str, $removeLineFeeds);
            if(!empty($allowableTags)){
                $str = preg_replace('/^(\s+)?<br>(\s+)?((\s)?(<br>)?)+/', '', $str);
                $str = preg_replace('/((\s)?(<br>)?)?(\s+)?<br>(\s+)?$/', '', $str);
                $str = str_replace('>', '> ', $str);
                $str = str_replace('<', ' <', $str);
                $str = str_replace('<li> <li>', ' ', $str);
                $str = !empty($str) ? preg_replace('/<(\w+)\b(?:\s+[\w\-.:]+(?:\s*=\s*(?:"[^"]*"|"[^"]*"|[\w\-.:]+))?)*\s*\/?>\s*<\/\1\s*>/', ' ', $str) : '';
                $str = StringClearRepository::clearDblSpace($str, $removeLineFeeds);
            }
        }

        return $str;
    }

    static function processingBlocksJson($value)
    {
        $blocks = is_string($value) ? json_decode($value) : $value;
        //dd(1, $blocks);
        $firstBlockHeader = null;
        $embedPreview = null;
        $embedService = null;
        $result = (object)[
            'blocks_json' => null,
            'uploaded_images' => [],
            'title' => null,
            'description' => null,
            'image' => null,
        ];

        foreach ($blocks as $i => $block){
            if($block->type == 'header'){
                $block->data->text = EditorRepository::clearHtml($block->data->text);
                if(!empty($block->data->text)){
                    if($block->data->level == 1){
                        if(!empty($result->title)){
                            $block->data->level = 2;
                        }else{
                            $result->title = $block->data->text;
                            $result->title = mb_strimwidth($result->title, 0, 100, '...');
                        }
                    }
                    $strLenLimit = $block->data->level == 1 ? 100 : ($block->data->level == 2 ? 160 : ($block->data->level == 3 ? 200 : 250));
                    if(mb_strlen($block->data->text) > $strLenLimit){
                        $block->data->text = mb_strimwidth($block->data->text, 0, $strLenLimit-3, '...');
                    }
                    if(empty($firstBlockHeader)){
                        $firstBlockHeader = [$i, $block];
                    }
                }
                if(empty($block->data->text)){
                    $block = null;
                }
            }else if($block->type == 'paragraph'){
                //$block->data->text = EditorRepository::clearHtml($block->data->text, '<a><li><ul><ol><span><p><b><i><h2><h3><h4><code><table><th><tr><td><mark><br>', 'br');
                $block->data->text = EditorRepository::clearHtml($block->data->text, '<a><b><i><mark><code><br>', 'br');
                if(!empty($block->data->text)){
                    if(empty($result->description) || mb_strlen($result->description) < 160){
                        $result->description = (!empty($result->description) ? $result->description.' ' : '').EditorRepository::clearHtml($block->data->text);
                    }
                }
                if(empty($block->data->text)){
                    $block = null;
                }
            }else if($block->type == 'image'){
                if(!empty($block->data->file->url)){
                    if(Str::startsWith($block->data->file->url, AppConf::$storage_url)){
                        $block->data->file->url = FileRepository::getStorageFilePath($block->data->file->url);
                        $result->uploaded_images[] = $block->data->file->url;
                    }
                    if(empty($result->image)){
                        $result->image = $block->data->file->url;
                    }
                }
                if(empty($block->data->file->url)){
                    $block = null;
                }
            }else if($block->type == 'delimiter'){
                if(!$i || $blocks[$i-1]->type == 'delimiter'){
                    $block = null;
                }
            }else if($block->type == 'list'){
                if(!empty($block->data->items)){
                    foreach ($block->data->items as $j => $item){
                        $item = EditorRepository::clearHtml($item, '<a><b><i><mark><code>');
                        if(empty($item)){
                            $item = null;
                        }
                        $block->data->items[$j] = $item;
                    }
                    $block->data->items = array_values(array_filter($block->data->items));
                }
                if(empty($block->data->items)) {
                    $block = null;
                }
            }else if($block->type == 'checklist'){
                if(!empty($block->data->items)){
                    foreach ($block->data->items as $j => $item){
                        $item->text = EditorRepository::clearHtml($item->text, '<a><b><i><mark><code>');
                        if(empty($item->text)){
                            $item =null;
                        }
                        $block->data->items[$j] = $item;
                    }
                    $block->data->items = array_values(array_filter($block->data->items));
                }
                if(empty($block->data->items)) {
                    $block = null;
                }
            }else if($block->type == 'alert'){
                $block->data->message = EditorRepository::clearHtml($block->data->message);
                if(empty($block->data->message)){
                    $block = null;
                }
            }else if($block->type == 'code'){
                if(empty(StringClearRepository::clearDblSpace($block->data->code))){
                    $block = null;
                }
            }else if($block->type == 'table'){
                if(!empty($block->data->content)){
                    foreach ($block->data->content as $j => $tr){
                        $isEmptyTr = true;
                        foreach ($tr as $td => $v){
                            $v = EditorRepository::clearHtml($v, '<a><b><i><mark><code>');
                            if(!empty($v)){
                                $isEmptyTr = false;
                            }
                            $block->data->content[$j][$td] = $v;
                        }
                        if($isEmptyTr){
                            $block->data->content[$j] = null;
                        }
                    }
                    $block->data->content = array_filter($block->data->content);
                }
                if(empty($block->data->content)) {
                    $block = null;
                }
            }else if($block->type == 'linkTool'){
                if(empty($block->data->link)) {
                    $block = null;
                }
            }else if($block->type == 'embed'){
                if(!empty($block->data->embed)){
                    $block->data->embed = htmlspecialchars_decode($block->data->embed);
                    $block->data->source = htmlspecialchars_decode($block->data->source);
                    if(empty($result->image) && empty($embedPreview)){
                        $embedService = $block->data->service;
                        if(in_array($embedService, ['vimeo', 'motorsport', 'coub', 'youtube'])){
                            if($embedService == 'youtube'){
                                $embedId = explode('embed/', $block->data->embed);
                                if(!empty($embedId) && !empty($embedId[1])){
                                    $embedId[1] = explode('?', $embedId[1])[0];
                                    if(BaseRepository::isValidImageUrl('https://i.ytimg.com/vi/'.$embedId[1].'/mqdefault.jpg')){
                                        $embedPreview = 'https://i.ytimg.com/vi/'.$embedId[1].'/mqdefault.jpg';
                                    }else if(BaseRepository::isValidImageUrl('https://i.ytimg.com/vi/'.$embedId[1].'/sddefault.jpg')){
                                        $embedPreview = 'https://i.ytimg.com/vi/'.$embedId[1].'/sddefault.jpg';
                                    }else if(BaseRepository::isValidImageUrl('https://i.ytimg.com/vi/'.$embedId[1].'/hqdefault.jpg')){
                                        $embedPreview = 'https://i.ytimg.com/vi/'.$embedId[1].'/hqdefault.jpg';
                                    }else if(BaseRepository::isValidImageUrl('https://i.ytimg.com/vi/'.$embedId[1].'/default.jpg')){
                                        $embedPreview = 'https://i.ytimg.com/vi/'.$embedId[1].'/default.jpg';
                                    }
                                }
                            }else{
                                $embedPreview = EditorRepository::getImageFromResponse($block->data->embed);
                            }
                            if(empty($result->image)){
                                if(!empty($embedPreview)){
                                    $result->image = $embedPreview;
                                }else if(!empty($embedService)){
                                    if(in_array($embedService, ['twitch-channel', 'twitch-video'])){
                                        $embedPreview = 'twitch.jpg';
                                    }else if(in_array($embedService, ['yandex-music-album', 'yandex-music-track', 'yandex-music-playlist'])){
                                        $embedPreview = 'yandex-music.jpg';
                                    }else{
                                        $embedPreview = $embedService.'.jpg';
                                    }
                                    $result->image = asset('images/service/'.$embedPreview);
                                }
                            }
                        }
                    }
                }
                if(empty($block->data->embed)) {
                    $block = null;
                }
            }

            $blocks[$i] = $block;
        }

        if(!empty($result->description)){
            $result->description = mb_strimwidth($result->description, 0, 160, '...');
        }
        if(empty($result->title) && !empty($firstBlockHeader)){
            $blocks[$firstBlockHeader[0]]->data->level = 1;
            $result->title = $blocks[$firstBlockHeader[0]]->data->text;
            $result->title = EditorRepository::clearHtml($result->title);
            $result->title = mb_strimwidth($result->title, 0, 100, '...');
        }
        if(empty($result->title) && !empty($result->description)){
            $result->title = mb_strimwidth($result->description, 0, 100, '...');
            array_unshift($blocks, (object)[
                'type' => 'header',
                'data' => (object)[
                    'text' => $result->title,
                    'level' => 1
                ],
            ]);
        }
        $blocks = array_values(array_filter($blocks));
        $result->blocks_json = json_encode($blocks, JSON_UNESCAPED_UNICODE);



        return $result;
    }
}