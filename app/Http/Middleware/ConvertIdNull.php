<?php

namespace App\Http\Middleware;

use Closure;

class ConvertIdNull
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $r = $request->input();
        $request->request->replace(self::convertRequest($r));

        return $next($request);
    }

    static function convertRequest($request)
    {
        if(empty($request)){
            return [];
        }

        foreach ($request as $i => $r){
            if(is_array($r)){
                $request[$i] = self::convertRequest($r);
            }
        }
        if(isset($request['id'])){
            $request['id'] = !empty($request['id']) ? $request['id'] : null;
        }

        foreach ($request as $i => $v){
            if(!in_array($i, [])){
                if(str_ends_with($i, '_id')/* && !in_array($i, ['qb_id'])*/){
                    $request[$i] = !empty($request[$i]) ? $request[$i] : null;
                }
            }
        }

        return $request;
    }

}