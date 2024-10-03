<?php

namespace App\Providers;

use App;
use App\AppConf;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //pusher_dump([\Request::getPathInfo(), request()->input()]);
        if(in_array(request()->getClientIp(), ['195.85.219.132', '77.123.43.236','91.213.187.22','31.128.76.180'])) {
            config(['app.debug' => true]);
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        AppConf::load();
    }
}
