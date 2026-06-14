<?php

namespace SanRatul\ShopAssist\Providers;

use Illuminate\Support\ServiceProvider;

class ShopAssistServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/shopassist-ai.php', 'shopassist-ai'
        );
    }

    public function boot(): void
    {
        $this->publishes([
           __DIR__ . '/../../config/shopassist-ai.php' => config_path('shopassist-ai.php'),
        ], 'shopassist-config');
    }
}