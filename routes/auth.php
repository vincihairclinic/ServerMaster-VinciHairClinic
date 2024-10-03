<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => '\App\Http\Controllers\Auth'], function () {

    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');

    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register');

    Route::post('logout', 'LoginController@logout')->name('logout');
    Route::get('logout', function (){
        return redirect()->route('redirect.after-login');
    });



    Route::get('login/apple', 'LoginController@appleRedirectToProvider')->name('login.apple');
    Route::any('/login/apple/callback', 'LoginController@appleHandleProviderCallback')->withoutMiddleware('csrf');

    Route::get('login/google', 'LoginController@googleRedirectToProvider')->name('login.google');
    Route::get('google-callback', 'LoginController@googleHandleProviderCallback');

    Route::get('login/youtube-google', 'LoginController@youtubeGoogleRedirectToProvider')->name('login.youtube-google');
    Route::get('youtube/google-callback', 'LoginController@youtubeGoogleHandleProviderCallback');

    Route::get('login/linkedin', 'LoginController@linkedinRedirectToProvider')->name('login.linkedin');
    Route::get('login/linkedin/callback', 'LoginController@linkedinHandleProviderCallback');

    Route::get('', function (){
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect()->route('login');
    })->name('home');

    Route::group(['prefix' => 'auth'], function () {

        Route::get('check', 'AuthController@check');
        Route::get('success', 'AuthController@success')->name('auth.success');
        Route::get('expired-link', 'AuthController@expiredLink')->name('auth.expired-link');
        Route::get('error', 'AuthController@error')->name('auth.error');

        //Route::get('confirmation-email', 'AuthController@showFormConfirmationEmail')->name('auth.confirmation-email');
        //Route::post('confirmation-email', 'AuthController@sendConfirmationEmailLink')/*->middleware('captcha')*/;

        Route::get('confirmation-password-reset', 'AuthController@showFormConfirmationPasswordReset')->name('auth.confirmation-password-reset');
        Route::post('confirmation-password-reset', 'AuthController@sendConfirmationPasswordResetLink')/*->middleware('captcha')*/;

        Route::get('password-reset', 'AuthController@showFormPasswordReset')->name('auth.password-reset');
        Route::post('password-reset', 'AuthController@passwordReset')/*->middleware('captcha')*/;
    });

});

Route::group(['middleware' => ['auth']], function () {
    Route::get('redirect/after-login', function (){
        if(!empty(Auth::user())){
            if(Auth::user()->role_id == \App\Models\Datasets\UserRole::ADMIN){
                return redirect()->route('dashboard.user.index');
            } else if(Auth::user()->role_id == \App\Models\Datasets\UserRole::USER){
                return redirect()->route('web.topics');
            }
            Auth::logout();
        }
        return redirect()->route('home');
    })->name('redirect.after-login');
});