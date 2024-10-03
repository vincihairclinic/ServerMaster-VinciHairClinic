<?php

namespace App\Http\Middleware;

use Closure;

class CsrfGet
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $sessionToken = $request->session()->token();
        $token = $request->input('_token');
        if (!is_string($sessionToken) || ! is_string($token) || !hash_equals($sessionToken, $token) ) {
            abort(404);
        }

        return $next($request);
    }

}