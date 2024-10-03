<?php

namespace App\Http\Middleware;

use Closure;

class CheckGetParams
{
    /**
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->page == 1){
            return redirect()->to($request->url());
        }

        return $next($request);
    }

}