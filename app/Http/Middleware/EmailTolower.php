<?php

namespace App\Http\Middleware;

use Closure;

class EmailTolower
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!empty($request->email)){
            $request->email = trim(str_replace(' ', '', mb_strtolower($request->email)));
            $request->request->add([
                'email'    => $request->email,
            ]);
        }
        return $next($request);
    }

}