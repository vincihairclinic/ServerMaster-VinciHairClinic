<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\CheckDomain::class,
        \App\Http\Middleware\EmailTolower::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            //\Illuminate\Session\Middleware\StartSession::class,
            //\App\Http\Middleware\AccessLoad::class,
            //\App\Http\Middleware\VerifyCsrfToken::class,
        ],

        'dashboard' => [
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\TrustProxies::class,
            //\Fruitcake\Cors\HandleCors::class,

            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\AccessLoad::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \App\Http\Middleware\JsonRequestMiddleware::class,
            \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,

            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\TrustProxies::class,

            //'throttle:60|60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'convert.string.booleans' => \App\Http\Middleware\ConvertStringBooleans::class,
        'convert.id.null' => \App\Http\Middleware\ConvertIdNull::class,
        'access.load' => \App\Http\Middleware\AccessLoad::class,
        'check.env' => \App\Http\Middleware\CheckEnv::class,
        'check.key' => \App\Http\Middleware\CheckKey::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'json.request' => \App\Http\Middleware\JsonRequest::class,
        'captcha' => \App\Http\Middleware\Captcha::class,
        'check.ip' => \App\Http\Middleware\CheckIp::class,
        'check.get.params' => \App\Http\Middleware\CheckGetParams::class,
        'set.locale' => \App\Http\Middleware\SetLocale::class,
        'is.email.verified' => \App\Http\Middleware\IsEmailVerified::class,
        'auth.access' => \App\Http\Middleware\AuthAccess::class,
        'email.tolower' => \App\Http\Middleware\EmailTolower::class,
        'check.domain' => \App\Http\Middleware\CheckDomain::class,
        'csrf.get' => \App\Http\Middleware\CsrfGet::class,
        'csrf' => \App\Http\Middleware\VerifyCsrfToken::class,
        'check.token' => \App\Http\Middleware\CheckToken::class,
        'cors' => \App\Http\Middleware\Cors::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
