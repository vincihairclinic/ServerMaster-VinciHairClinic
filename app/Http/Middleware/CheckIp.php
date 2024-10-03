<?php

namespace App\Http\Middleware;

use Closure;

class CheckIp
{
    /**
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(in_array(request()->getClientIp(), [
            '195.85.219.132',
            '77.123.43.236',
        ]) || md5($_SERVER['HTTP_USER_AGENT']) === '69f388424298a726a85a8ca73bbfedaa') {

            return $next($request);
        }
        abort(404);
    }

}