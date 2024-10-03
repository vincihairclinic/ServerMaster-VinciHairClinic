<?php

namespace App\Http\Controllers\Web;

use App\Repositories\PaginationRepository;
use App\W;
use Illuminate\Routing\Controller as BaseController;

class WebController extends BaseController
{
    public function __construct()
    {
        W::init();
        PaginationRepository::init();
    }

    static function getBackUrl()
    {
        $curUrl = !empty($curUrl) && is_string($curUrl) ? $curUrl : request()->fullUrl();
        if(!empty($curUrl)){
            $curUrl = mb_strtolower(rtrim($curUrl, '/'));
        }
        $prevUrl = !empty($prevUrl) && is_string($prevUrl) ? $prevUrl : (!empty(app('url')->previous()) ? app('url')->previous() : '');
        if(!empty($prevUrl)){
            $prevUrl = mb_strtolower(rtrim($prevUrl, '/'));
        }

        //-----------------------------------------------

        if(mb_strpos($curUrl, '?') !== false){
            return explode('?', $curUrl)[0];
        }

        //-----------------------------------------------

        if(mb_strpos($prevUrl, route('home')) !== false){
            return $prevUrl;
        }

        return route('home');
    }

    static function _loadBackUrl()
    {
        W::$backUrl = self::getBackUrl();
        return W::$backUrl;
    }

    //-------------------------------------------------

    static function _loadMeta()
    {
        W::$metaTitle = '';
        W::$metaDescription = '';
    }
}
