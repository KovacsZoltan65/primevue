<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
    public function boot(): void
    {
        define('APP_ACTIVE', 1);
        define('APP_INACTIVE', 0);

        define('APP_MIN_STRING_LENGTH', 3);
        define('APP_MAX_STRING_LENGTH', 255);

        define('APP_DEC_LENGTHS', 10);
        define('APP_DEC_DIGITS', 2);

        Inertia::share([
            'errors' => function () {
                return Session::get('errors')
                    ? Session::get('errors')->getBag('default')->getMessages()
                    : (object) [];
            },
            //'baseUrl' => env('VUE_APP_BASE_URL'),
        ]);

        Inertia::share('flash', function(){
            return [
                'message' => Session::get('message'),
            ];
        });

        Inertia::share('csrf_token', function(){
            return csrf_token();
        });
    }
}
