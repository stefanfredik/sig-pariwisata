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
        if (\Illuminate\Support\Facades\App::environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        if (\Illuminate\Support\Facades\App::environment('local') || \Illuminate\Support\Facades\App::environment('dev')) {
            \Illuminate\Support\Facades\Vite::useHotFile(public_path('hot'));
        }
    }
}
