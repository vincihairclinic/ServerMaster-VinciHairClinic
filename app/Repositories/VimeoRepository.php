<?php

namespace App\Repositories;

use App\AppConf;
use App\Application;
use App\Repositories\Base\CurlRepository;
use App\Repositories\Base\StringClearRepository;
use App\Repositories\Base\StringRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class VimeoRepository
{
    static function getVideoPreview($videoUrl)
    {
        if(!empty($videoUrl)){
            try {
                $path = explode('/', parse_url($videoUrl)['path']);
                $path = $path[count($path) - 1];
                $response = CurlRepository::sendCurl('https://player.vimeo.com/video/'.$path.'/config');
                $response = json_decode($response);
                $response = $response->video->thumbs->base;
                //$response = collect($response)->sortByDesc('height')->first()->url;
                return $response;
            }catch (Exception $e){}
        }

        return null;
    }

    static function getVideoCode($videoUrl)
    {
        if(!empty($videoUrl)){
            try {
                $path = explode('/', parse_url($videoUrl)['path']);
                $path = $path[count($path) - 1];
                return $path;
            }catch (Exception $e){}
        }

        return null;
    }

}