<?php

namespace App\Http\Middleware;

use Closure;

class CheckEnv
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!config('app.debug') && config('app.env') != 'production') {
            //abort(404);
        }
        return $next($request);
    }

}