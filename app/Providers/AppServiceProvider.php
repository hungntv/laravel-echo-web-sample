<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\Broadcasters\TheRabbitmqBroadcaster;

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
        Broadcast::extend('therabbitmq', function () {
            return new TheRabbitmqBroadcaster();
        });
    }
}
