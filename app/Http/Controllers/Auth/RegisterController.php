<?php

namespace App\Http\Controllers\Auth;

use App\AppConf;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Datasets\UserStatus;
use App\Models\User;
use App\Repositories\Base\FirebaseAuthRepository;
use Exception;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;

class RegisterController extends DashboardController
{
    use RegistersUsers;

    protected $redirectTo = '/redirect/after-login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(RegisterRequest $request)
    {
        if(AppConf::$disableRegistration){
            return redirect()->route('register')->withErrors(['email' => 'Invalid E-mail address']);
        }

        if($user = $this->create($request->all())){
            if(FirebaseAuthRepository::emailVerificationSend($user->email)){
                return redirect()->route('login')->with(['register_check_your_email' => 1])->with(['email' => $user->email]);
            }
        }

        return redirect()->route('register')->withErrors(['email' => 'Invalid E-mail address']);
    }

    protected function create(array $data)
    {
        if($user = User::where('email', $data['email'])->first()){
            if($user->status_id == UserStatus::NEW){
                if($user->delete()){
                    $user = null;
                }
            }
        }

        if(!empty($user)){
            return null;
        }

        try {
            return User::create([
                'name' => !empty($data['name']) ? $data['name'] : explode('@', $data['email'])[0],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }catch (Exception $e){
            return null;
        }
    }
}
