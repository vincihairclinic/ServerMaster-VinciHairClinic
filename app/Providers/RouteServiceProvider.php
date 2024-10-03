<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    public const HOME = '/home';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapApiRoutes();

        $this->mapDashboardRoutes();

        $this->mapAuthRoutes();

        $this->mapWebRoutes();

        $this->mapDefaultRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapDashboardRoutes()
    {
        Route::middleware('dashboard')
            ->namespace($this->namespace)
            ->group(base_path('routes/dashboard.php'));
    }

    protected function mapAuthRoutes()
    {
        Route::middleware('dashboard')
            ->namespace($this->namespace)
            ->group(base_path('routes/auth.php'));
    }

    protected function mapDefaultRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/default.php'));
    }
}
