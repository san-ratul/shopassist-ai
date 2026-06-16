<?php

namespace SanRatul\ShopAssist\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use SanRatul\ShopAssist\Components\SettingsPage;
use SanRatul\ShopAssist\Providers\AI\GeminiProvider;
use SanRatul\ShopAssist\Services\ProviderManager;
use SanRatul\ShopAssist\Services\SettingsService;

class ShopAssistServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/shopassist.php', 'shopassist'
        );

        $this->app->singleton(
            SettingsService::class,
            SettingsService::class
        );

        $this->app->singleton(ProviderManager::class, function ($app) {
            $manager = new ProviderManager($app);

            $manager->extend('gemini', function ($app) {
                return new GeminiProvider(
                    $app->make(SettingsService::class)
                );
            });

            return $manager;
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../../resources/views',
            'shopassist'
        );

        $this->publishes([
           __DIR__ . '/../../config/shopassist.php' => config_path('shopassist.php'),
        ], 'shopassist-config');

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations')
        ], 'shopassist-migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        Blade::component('shopassist::settings-page', SettingsPage::class);
    }
}