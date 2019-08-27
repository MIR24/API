<?php

namespace App\Providers;

use App\Library\Services\Import\MIRTVImporter;
use App\Library\Services\Import\SmartTvImporter;
use App\Library\Services\Import\SmartTvVideo;
use App\Library\Services\TimeReplacer\TimeReplacer;
use App\Library\Services\TokenValidation\RegistrationUser;
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
        $this->app->bind('App\Library\Services\RegistrationUser', function ($app) {
            return new RegistrationUser();
        });
        $this->app->bind('App\Library\Services\Import\SmartTvImporter', function ($app) {
            return new SmartTvImporter(config('channels'));
        });
        $this->app->bind('App\Library\Services\Import\SmartTvVideo', function ($app) {
            return new SmartTvVideo(config('channels.archive'));
        });
        $this->app->bind('App\Library\Services\Import\MIRTVImporter', function ($app) {
            return new MIRTVImporter(config('mir_news'));
        });

    }
}
