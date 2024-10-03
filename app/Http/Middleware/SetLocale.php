<?php

namespace App\Http\Middleware;

use App\AppConf;
use Closure;

class SetLocale
{
    /**
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Auth::check()){
            AppConf::$lang = \Auth::user()->language_key;
        }
        return $next($request);
    }

}