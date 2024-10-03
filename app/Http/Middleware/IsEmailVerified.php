<?php

namespace App\Http\Middleware;

use App\Models\Datasets\UserStatus;
use App\Models\User;
use Closure;

class IsEmailVerified
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
            if($user = User::where('email', $request->email)->first()){
                if($user->status_id == UserStatus::NEW){
                    return redirect()->route('login')->withErrors(['verified' => $request->email]);
                }else if($user->status_id == UserStatus::BLOCKED){
                    return redirect()->route('home');
                    //return redirect()->route('login')->withErrors(['blocked' => 1]);
                }
            }
        }
        return $next($request);
    }

}