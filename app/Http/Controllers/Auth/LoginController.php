<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\Base\YoutubeRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends DashboardController
{
    use AuthenticatesUsers;

    protected $redirectTo = '/redirect/after-login';

    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(LoginRequest $request)
    {
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $request->request->add([
            'password' => hash('sha256', $request->password),
        ]);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->route('home');
    }

    public function googleRedirectToProvider()
    {
        return Socialite::driver('google')->setScopes([
            'email',
            'profile',
            'openid',
        ])->with(['access_type' => 'offline', "prompt" => "consent select_account"])->redirect();
    }

    public function googleHandleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            if(!empty($user) && !empty($user->email)){

                $fullName = !empty($user->name) ? explode(' ', $user->name) : ['name', 'surname'];
                if(empty($fullName[1])){
                    $fullName[1] = 'surname';
                }

                if(!$model = User::where('email', $user->email)->first()){
                    $model = User::create([
                        'email' => $user->email,
                        'name' => !empty($user->user) && !empty($user->user->given_name) ? $user->user->given_name : $fullName[0],
                    ]);
                }
                $this->guard()->login($model);
            }
        }catch (\Exception $e){}

        return redirect()->route('login.google');
    }

    public function appleRedirectToProvider()
    {
        return Socialite::driver('apple')->redirect();
    }

    public function appleHandleProviderCallback()
    {
        try {
            $user = Socialite::driver('apple')->user();
            dd($user);
            if(!empty($user) && !empty($user->email)){

                $fullName = !empty($user->name) ? explode(' ', $user->name) : ['name', 'surname'];
                if(empty($fullName[1])){
                    $fullName[1] = 'surname';
                }

                if(!$model = User::where('email', $user->email)->first()){
                    $model = User::create([
                        'email' => $user->email,
                        'name' => !empty($user->user) && !empty($user->user->name) ? $user->user->name : $fullName[0],
                    ]);
                }
                $this->guard()->login($model);
            }
        }catch (\Exception $e){
            dd($e->getMessage());
        }

        return redirect()->route('login.apple');
    }

    public function linkedinRedirectToProvider()
    {
        return Socialite::driver('linkedin')->setScopes([
            'r_liteprofile',
            'r_emailaddress',

            //'r_contactinfo',
            //'w_member_social',
            //'r_basicprofile',
        ])/*->with(['access_type' => 'offline', "prompt" => "consent select_account"])*/->redirect();
    }

    public function linkedinHandleProviderCallback()
    {
        try {
            $user = Socialite::driver('linkedin')->user();
            //$socialiteUser = Socialite::driver($service)->userFromToken($request->providerToken);
            //$socialUsername = $socialiteUser->getNickname();

            dd($user);
            return redirect(route('web.userattuer').'?id='.$user->id);

        }catch (\Exception $e){
            dd($e->getMessage());
        }

        return redirect()->route('login.linkedin');
    }


    public function youtubeGoogleRedirectToProvider()
    {
        \Config::set('services.google', config('services.youtube-google'));

        return Socialite::driver('google')->setScopes([
            'email',
            'profile',
            'openid',
            'https://www.googleapis.com/auth/youtube',
            'https://www.googleapis.com/auth/youtube.channel-memberships.creator',
            'https://www.googleapis.com/auth/youtube.force-ssl',
            'https://www.googleapis.com/auth/youtube.readonly',
            'https://www.googleapis.com/auth/youtube.upload',
            'https://www.googleapis.com/auth/youtubepartner',
            'https://www.googleapis.com/auth/youtubepartner-channel-audit',
        ])->with(['access_type' => 'offline', "prompt" => "consent select_account"])->redirect();
    }

    public function youtubeGoogleHandleProviderCallback()
    {
        \Config::set('services.google', config('services.youtube-google'));

        try {
            $user = Socialite::driver('google')->user();
            Setting::updateValue('youtube_refresh_token', $user->refreshToken);
            YoutubeRepository::run();
            return redirect()->route('dashboard.index');

        }catch (\Exception $e){}

        return redirect()->route('login.youtube-google');
    }
}
