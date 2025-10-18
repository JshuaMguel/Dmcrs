<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Http\Middleware\SuperAdminMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register SuperAdmin middleware alias
        Route::aliasMiddleware('superadmin', SuperAdminMiddleware::class);

        // Force HTTPS in production (Railway uses a proxy)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
