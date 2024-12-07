<?php

namespace App\Providers;

use App\Services\CacheService;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CacheService::class, function ($app) {
            // Példa: 'companies' címkével inicializáljuk a CacheService-t
            return new CacheService('companies');
        });
    }

    public function boot(): void
    {
        //
    }
}
