<?php

namespace App\Providers;

use Cocur\Slugify\Slugify;
use Illuminate\Pagination\Paginator;
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
    }
}
