<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CalendarQueryParserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CalendarQueryParserService::class, function ($app) {
            $request = $app->make('request');
            $url = $request->fullUrl();
            return new CalendarQueryParserService($url);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
