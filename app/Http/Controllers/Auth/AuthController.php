<?php

namespace App\Http\Controllers\Auth;

use App;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\Auth\SendConfirmationEmailLinkRequest;
use App\Http\Requests\Auth\SendConfirmationPasswordResetLinkRequest;
use App\Models\Datasets\UserStatus;
use App\Models\User;
use App\Repositories\Base\FirebaseAuthRepository;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    use AuthenticatesUsers;

    public function __construct(Request $request)
    {

    }

    public function success(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return view('auth.success');
    }

    public function expiredLink(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return view('auth.expired-link');
    }

    public function error(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return view('auth.error');
    }

    public function showFormConfirmationEmail(Request $request)
    {
        $email = $request->email;
        return view('auth.confirmation-email', compact('email'));
    }

    public function sendConfirmationEmailLink(SendConfirmationEmailLinkRequest $request)
    {
        if($user = User::where('email', $request->email)->first()){
            if(FirebaseAuthRepository::emailVerificationSend($user->email)){
                return redirect()->route('auth.success')->with(['resend_check_your_email' => 1]);
            }
        }
        return redirect()->route('auth.confirmation-email')->withErrors(['email' => 'Invalid E-mail address']);
    }

    public function showFormConfirmationPasswordReset(Request $request)
    {
        $email = $request->email;
        return view('auth.confirmation-password-reset', compact('email'));
    }

    public function sendConfirmationPasswordResetLink(SendConfirmationPasswordResetLinkRequest $request)
    {
        if($user = User::where('email', $request->email)->first()){
            if(FirebaseAuthRepository::resetPasswordVerificationSend($user->email)){
                return redirect()->route('auth.success')->with(['check_your_email' => 1]);
            }
        }
        return redirect()->route('auth.confirmation-password-reset')->withErrors(['email' => 'Invalid E-mail address']);
    }

    public function showFormPasswordReset(Request $request)
    {
        $email = $request->email;
        return view('auth.password-reset', compact('email'));
    }

    public function passwordReset(PasswordResetRequest $request)
    {
        if(!empty($request->oobCode)){
            if($email = FirebaseAuthRepository::resetPasswordVerificationCheck($request->oobCode)){
                if($user = User::where('email', $email)->first()){
                    $user->password = Hash::make(hash('sha256', $request->password));
                    if($user->save()){
                        return redirect()->route('auth.success')->with(['password_was_changed' => 1]);
                    }
                }
            }
            return redirect()->route('auth.expired-link')->withErrors(['error' => 1]);
        }

        if($user = User::where('email', $request->email)->first()){
            $user->password = Hash::make(hash('sha256', $request->password));
            if($user->save()){
                return redirect()->route('auth.success')->with(['password_was_changed' => 1]);
            }
        }
        return redirect()->route('auth.password-reset')->withErrors(['email' => 'Invalid E-mail address']);
    }

    public function check(Request $request)
    {
        if($request->mode == 'resetPassword'){
            $oobCode = $request->oobCode;
            $email = null;
            return view('auth.password-reset', compact('email', 'oobCode'));
        }else if($request->mode == 'verifyEmail'){
            if($email = FirebaseAuthRepository::emailVerificationCheck($request->oobCode)){
                if($user = User::where('email', $email)->where('status_id', UserStatus::ACTIVE)->first()){
                    $user->is_email_verified = true;
                    if($user->save()){
                        FirebaseAuthRepository::deleteAccount($email);
                        return redirect()->route('auth.success');
                    }
                }
            }
        }

        return redirect()->route('auth.expired-link')->withErrors(['error' => 1]);
    }

}
