<?php

namespace App\Providers;

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
        /*$this->app->bind(
            \App\Http\Controllers\Admin\UserCrudController::class
        );*/
        $this->app->bind(
            \Backpack\CRUD\app\Http\Controllers\Auth\LoginController::class,
            \App\Http\Controllers\Auth\LoginController::class
        );
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
