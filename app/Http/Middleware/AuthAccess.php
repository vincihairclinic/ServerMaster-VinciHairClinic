<?php

namespace App\Http\Middleware;

use App\Access;
use App\Models\Datasets\UserStatus;
use Auth;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class AuthAccess
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles = [])
    {
        if(!empty(Auth::user()) && Auth::user()->status_id == UserStatus::ACTIVE){
            $allowedRoles = array_slice(func_get_args(), 2);
            if(!empty($allowedRoles)){
                foreach ($allowedRoles as $role){
                    if(Access::is($role)){
                        return $next($request);
                    }
                }
            }else{
                return $next($request);
            }
        }

        if($request->wantsJson()){
            return response()->json([
                'message'     => 'Forbidden',
                'status_code' => Response::HTTP_FORBIDDEN,
            ], Response::HTTP_FORBIDDEN);
        }

        $error = 'access_denied';
        $value = 1;
        $with = [];
        if(!empty(Auth::user())){
            if(Auth::user()->status_id == UserStatus::NEW){
                $error = 'verified';
                $value = Auth::user()->email;
                $with['email'] = $value;
            }else if (Auth::user()->status_id == UserStatus::BLOCKED){
                $error = 'blocked';
                return redirect()->back();
                //return redirect()->route('home');
            }
            Auth::guard()->logout();
            $request->session()->invalidate();
        }
        return redirect()->route('login')->withErrors([$error => $value])->with($with);
    }

}