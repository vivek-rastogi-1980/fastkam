<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Redirect authenticated users trying to access guest auth routes directly to services
        \Illuminate\Auth\Middleware\RedirectIfAuthenticated::redirectUsing(function () {
            return route('admin.services');
        });

        $host = request()->getHost();
        $isLocalHost = in_array($host, ['localhost', '127.0.0.1', '::1']) || str_ends_with($host, '.test') || str_ends_with($host, '.local');

        if (!$isLocalHost && (app()->environment('production') || env('FORCE_HTTPS', false) || request()->server('HTTP_X_FORWARDED_PROTO') === 'https')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
