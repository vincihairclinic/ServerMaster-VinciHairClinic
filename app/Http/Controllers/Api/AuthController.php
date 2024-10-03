<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Auth\AutoRegisterRequest;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\Auth\CheckEmailRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Auth\SendConfirmationPasswordResetLinkRequest;
use App\Models\Datasets\UserRole;
use App\Models\Datasets\UserStatus;
use App\Models\User;
use App\Repositories\Base\FirebaseAuthRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends ApiController
{
    public function checkEmail(CheckEmailRequest $request)
    {
        return $this->respondSuccess();
    }

    public function forgotPassword(SendConfirmationPasswordResetLinkRequest $request)
    {
        //firebase email url https://power-wave.pino.pp.ua/auth/check
        //dd(md5('qwerqwer')); //d74682ee47c3fffd5dcd749f840fcdd4

        if ($model = User::where('email', $request->email)->whereIn('role_id', [UserRole::USER])->first()) {
            if (FirebaseAuthRepository::resetPasswordVerificationSend($model->email)) {
                return $this->respondSuccess('Successfully!');
            }
        }
        return $this->respondError('Something went wrong, please try again');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        if(!Hash::check($request->current_password, Auth::user()->password)){
            return $this->respondError('Please try typing your password again or go to the “Forgot Password” option', 'Invalid password');
        }

        return Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]) ? $this->respondSuccess() : $this->respondError();
    }

    //------------------------------------------------

    public function login(Request $request, LoginRequest $validationRequest)
    {
        User::setStaticVisible(User::$publicSelfColumns);

        $password = $request->password;
        $model = null;

        $model = User::where('email', $request->email)->whereIn('role_id', [UserRole::USER])->first();

        if (!empty($model)) {
            if(!Hash::check($password, $model->password)){
                return $this->respondError('Invalid password', Response::HTTP_FORBIDDEN);
            }

            if($response = self::getAccessToken($request, 'email')){
                if(!empty($response->access_token)){
                    $model->updated_at = Carbon::now();
                    if($model->save()){
                        return $this->respond([
                            'token_type' => $response->token_type,
                            'token' => $response->access_token,
                            'user' => $model,
                        ]);
                    }
                }
            }
        }

        return $this->respondError('User does not exist', 'Invalid');
    }

    public function register(Request $request, RegisterRequest $validationRequest)
    {
        User::setStaticVisible(User::$publicSelfColumns);

        $model = new User();
        $model->status_id = UserStatus::ACTIVE;
        $model->email = $request->email;
        $model->updated_at = Carbon::now();
        $model->password = Hash::make($request->password);
        $model->full_name = $request->full_name;
        $model->role_id = UserRole::USER;
        $model->country_id = !$request->has('country_id') ? null : $request->country_id;
        $model->language_key = !$request->has('language_key') ? null : $request->language_key;
        if($model->save()){
            if($response = self::getAccessToken($request, 'email')){
                if(!empty($response->access_token)){
                    FirebaseAuthRepository::emailVerificationSend($model->email);

                    $model->app_state = !$request->has('app_state') ? null : $request->app_state;
                    if($model->save()) {
                        return $this->respond([
                            'token_type' => $response->token_type,
                            'token'      => $response->access_token,
                            'user'       => User::where('id', $model->id)->first(),
                        ]);
                    }
                }
            }
        }

        return $this->respondError('User was not registered');
    }

    public function autoRegister(Request $request, AutoRegisterRequest $validationRequest)
    {
        User::setStaticVisible(User::$publicSelfColumns);
        User::$hide_auto_email = false;

        $model = new User();
        $model->status_id = UserStatus::ACTIVE;
        $model->role_id = UserRole::USER;
        $model->email = '_t_m_p_'.now()->timestamp.'_'.rand(1111, 9999).'@gmail.com';
        $model->updated_at = Carbon::now();
        $model->password = Hash::make(hash('sha256', $model->email));
        $model->country_id = !$request->has('country_id') ? null : $request->country_id;
        $model->language_key = !$request->has('language_key') ? null : $request->language_key;

        if($model->save()){
            $request->request->add([
                'email'    => $model->email,
                'password' => hash('sha256', $model->email),
            ]);

            if($response = self::getAccessToken($request, 'email')){
                if(!empty($response->access_token)){
                    FirebaseAuthRepository::emailVerificationSend($model->email);

                    $model->app_state = !$request->has('app_state') ? null : $request->app_state;
                    if($model->save()) {
                        User::$hide_auto_email = true;
                        return $this->respond([
                            'token_type' => $response->token_type,
                            'token'      => $response->access_token,
                            'user'       => User::where('id', $model->id)->first(),
                        ]);
                    }
                }
            }
        }

        return $this->respondError('User was not registered');
    }

    //----------------------------------------------

    protected function getAccessToken($request, $grantType)
    {
        $client = Client::where('password_client', 1)->first();

        $request->request->add([
            'grant_type'    => $grantType,
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
        ]);

        $tokenRequest = Request::create(
            'oauth/token',
            'POST'
        );

        $response = \Route::dispatch($tokenRequest);

        return json_decode($response->getContent());
    }

    protected function addToResponse($response, $addValues = [])
    {
        $json = json_decode($response->getContent(), true);
        if (!empty($json['access_token'])) {
            $json = array_merge($json, (array)$addValues);
        }
        return $response->setContent(json_encode($json));
    }

    protected function isAccessToken($response)
    {
        $json = json_decode($response->getContent(), true);
        if (!empty($json['access_token'])) {
            return true;
        }
        return false;
    }
}
