<?php

namespace App\Providers;

use Cocur\Slugify\Slugify;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Parsedown;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('templates.pagination');

        $this->app->bind(Slugify::class, Slugify::class);
        $this->app->bind(Parsedown::class, function ($app) {
            $parsedown = new Parsedown();
            $parsedown->setSafeMode(true);

            return $parsedown;
        });
    }
}
