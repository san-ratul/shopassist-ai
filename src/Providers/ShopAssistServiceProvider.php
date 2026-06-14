<?php

namespace SanRatul\ShopAssist\Providers;

use Illuminate\Support\ServiceProvider;

class ShopAssistServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/shopassist.php', 'shopassist'
        );
    }

    public function boot(): void
    {
        $this->publishes([
           __DIR__ . '/../../config/shopassist.php' => config_path('shopassist.php'),
        ], 'shopassist-config');

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations')
        ], 'shopassist-migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}