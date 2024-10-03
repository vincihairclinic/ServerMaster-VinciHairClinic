<?php

namespace App\Http\Middleware;

use Closure;

class ConvertStringBooleans
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach ($request->input() as $i => $v){
            if($v === true){
                $request->merge([$i => 1]);
            }
            if($v === false){
                $request->merge([$i => 0]);
            }
            if($v === 'true'){
                $request->merge([$i => 1]);
            }
            if($v === 'false'){
                $request->merge([$i => 0]);
            }
        }
        return $next($request);
    }

}