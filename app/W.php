<?php

namespace App;

use App\Models\User;
use Jenssegers\Agent\Agent;

class W
{
    static $isMobile = false;
    static $isAmp = false;

    static $pageType = 'index';

    static $tmp = [];

    static $isHome = false;
    static $isEmptyParams = false;

    static $pageTitle = '';
    static $pageDescription = '';
    static $metaTitle = '';
    static $metaDescription = '';
    static $metaKeywords = [];

    static $breadcrumbs = [];
    static $urlParams = null;

    /**
     * @var User
     */
    static $user = null;

    static $backUrl = '';

    static $faq_text = null;
    static $seo_text = null;
    static $search_keywords = null;
    static $search_titles = null;
    static $ads_keywords = null;
    static $tags = [];

    //---------------------------------------------------

    static function init()
    {
        $agent = new Agent();
        W::$isMobile = $agent->isMobile();
        if(W::$isAmp = strpos(request()->getHost(), 'amp.') !== false){
            W::$isMobile = true;
        }
    }

}