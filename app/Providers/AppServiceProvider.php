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
    public function boot()
    {
        if ($this->app->environment('local')) {
            $this->app->bind('path.public', function () {
                return base_path('public');
            });

            // Adicione a seguinte linha:
            app('url')->forceRootUrl(config('app.url'));
        }
    }
}
