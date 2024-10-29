<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DataProviders\DataProviderFactory;
use App\Services\UserService;

class DataProviderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DataProviderFactory::class, function ($app) {
            return new DataProviderFactory();
        });

        $this->app->singleton(UserService::class, function ($app) {
            return new UserService($app->make(DataProviderFactory::class));
        });
    }
} 