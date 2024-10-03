<?php

namespace App\Http\Middleware;

use Closure;

class CheckKey
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->token === config('app.token')){
            return $next($request);
        }
        abort(404);
    }

}