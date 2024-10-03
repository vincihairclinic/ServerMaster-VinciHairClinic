<?php

namespace App\Http\Middleware;

use App\Access;
use Closure;

class AccessLoad
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Access::load();

        return $next($request);
    }

}