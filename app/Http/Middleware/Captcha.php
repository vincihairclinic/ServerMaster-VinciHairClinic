<?php

namespace App\Http\Middleware;

use App\AppConf;
use App\Repositories\Base\CurlRepository;
use Closure;

class Captcha
{
    /**
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(empty(AppConf::$captcha_secret)){
            return $next($request);
        }

        if(!empty($request->captcha)){
            if($response = CurlRepository::sendCurl('https://www.google.com/recaptcha/api/siteverify', http_build_query([
                'secret' => AppConf::$captcha_secret, 'response' => $request->captcha
            ]), 'post', true)){
                if(!empty($response->score) && $response->score >= 0.3){
                    return $next($request);
                }
            }
        }

        return redirect()->back()->withErrors(['captcha' => true]);
    }

}