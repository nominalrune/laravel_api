<?php

namespace App\Providers;

use App\Services\CalendarQueryParserService;
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
        // $this->app->bind(CalendarQueryParserService::class, function ($app) {
        //     $request = $app->make('request');
        //     $url = $request->fullUrl();
        //     return new CalendarQueryParserService($url);
        // });
        // if($this->app->environment() == 'local'){
        //     $this->app->register(\Vyuldashev\LaravelOpenApi\OpenApiServiceProvider::class,);
        //  }
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
