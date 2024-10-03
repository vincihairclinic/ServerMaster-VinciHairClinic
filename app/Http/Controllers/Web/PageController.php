<?php

namespace App\Http\Controllers\Web;

use App\AppConf;
use App\Models\Page;
use App\Models\Privacy;
use App\W;

class PageController extends WebController
{
    public function __construct()
    {
        parent::__construct();

        W::$pageType = 'page';
        self::_loadBackUrl();
    }

    public function page($model)
    {
        if ($model = Page::where('route', $model)->first()) {
            return view('web.page', compact('model'));
        }
        abort(404);
    }

}
