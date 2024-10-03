<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class CheckToken
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->header('Authorization') == 'Bearer '.config('app.token') || $request->token == config('app.token')){
            return $next($request);
        }
        abort(Response::HTTP_FORBIDDEN);
    }

}