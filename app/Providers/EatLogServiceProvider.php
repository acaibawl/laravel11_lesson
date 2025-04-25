<?php

namespace App\Providers;

use App\Services\EatLog;
use Illuminate\Support\ServiceProvider;

class EatLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('eatLog', function () {
            return new EatLog();
        } );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function provides()
    {
        return ['eatLog'];
    }
}
