<?php

namespace App\Providers;

use App\Library\Services\TimeReplacer\TimeReplacer;
use App\Library\Services\TokenValidation\TokenValidation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceScheme(env('APP_HTTP_SCHEME', 'https'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Library\Services\TimeReplacer', function ($app) {
            return new TimeReplacer();
        });
        $this->app->bind('App\Library\Services\TokenValidation', function ($app) {
            return new TokenValidation();
        });

    }
}
