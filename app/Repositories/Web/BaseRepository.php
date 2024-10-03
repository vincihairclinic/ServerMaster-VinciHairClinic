<?php

namespace App\Repositories\Web;

use App\W;

class BaseRepository
{
    static function clearMetaStr()
    {
        W::$metaTitle = str_replace('"', '', W::$metaTitle);
        W::$metaDescription = str_replace('"', '', W::$metaDescription);
    }

}