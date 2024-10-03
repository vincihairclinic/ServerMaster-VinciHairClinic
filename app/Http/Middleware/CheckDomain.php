<?php

namespace App\Http\Middleware;

use App\AppConf;
use Closure;

class CheckDomain
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!AppConf::$check_domain_enable){
            return $next($request);
        }

        $originUrl = $request->fullUrl();
        $requestPath = parse_url($originUrl, PHP_URL_PATH);
        $requestQuery = parse_url($originUrl, PHP_URL_QUERY);
        $correctUrl = 'https://'.(strpos(request()->getHost(), 'amp.') !== false ? 'amp.' : '').config('app.name').str_replace('//', '/', str_replace('///', '/', $requestPath)).(!empty($requestQuery) ? '?'.(parse_url($originUrl, PHP_URL_QUERY)) : '');

        if($originUrl != $correctUrl){
            return redirect($correctUrl, 301);
        }

        if(in_array($_SERVER['REQUEST_URI'], ['/?'])){
            return redirect($request->fullUrl(), 301);
        }

        if(strpos(request()->getHost(), 'amp.') !== false){
            $parseUrl = parse_url($request->fullUrl());
            if($parseUrl['scheme'].'://'.$parseUrl['host'] == 'https://amp.'.config('app.name')){
                return $next($request);
            }
            if(!empty($parseUrl['path'])){
                return redirect('https://amp.'.config('app.name').$parseUrl['path'].(!empty($parseUrl['query']) ? '?'.$parseUrl['query'] : ''), 301);
            }
            return redirect('https://amp.'.config('app.name').(!empty($parseUrl['query']) ? '?'.$parseUrl['query'] : ''), 301);
        }

        $parseUrl = parse_url($request->fullUrl());
        if($parseUrl['scheme'].'://'.$parseUrl['host'] == 'https://'.config('app.name')){
            return $next($request);
        }
        if(!empty($parseUrl['path'])){
            return redirect('https://'.config('app.name').$parseUrl['path'].(!empty($parseUrl['query']) ? '?'.$parseUrl['query'] : ''), 301);
        }
        return redirect('https://'.config('app.name').(!empty($parseUrl['query']) ? '?'.$parseUrl['query'] : ''), 301);
    }

}