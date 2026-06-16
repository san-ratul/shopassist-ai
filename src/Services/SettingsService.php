<?php

namespace SanRatul\ShopAssist\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use SanRatul\ShopAssist\Models\Setting;

class SettingsService
{

    protected string $cacheKey = 'shopassist_settings';


    /**
     * Get all settings
     */
    public function all(): Collection
    {
        $settings = Cache::rememberForever($this->cacheKey, function () {

            $settings = [];

            foreach (Setting::query()->get() as $setting) {

                $value = $setting->is_encrypted
                    ? Crypt::decryptString($setting->value)
                    : $setting->value;

                Arr::set($settings, $setting->key, $value);
            }

            return $settings;
        });

        return collect($settings);
    }

    /**
     * Get a setting value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $value = data_get(
            $this->all()->toArray(),
            $key
        );

        if ($value !== null) {
            return $value;
        }

        if ($default !== null) {
            return $default;
        }

        return $this->getConfigDefault($key);
    }

    protected function getConfigDefault(string $key): mixed
    {
        if (str_starts_with($key, 'settings.')) {
            $field = str_replace('settings.', '', $key);

            return config("shopassist.settings.{$field}.default");
        }

        if (str_starts_with($key, 'providers.')) {
            $parts = explode('.', $key);

            return config(
                "shopassist.providers.{$parts[1]}.settings.{$parts[2]}.default"
            );
        }

        return null;
    }

    /**
     * Create or update a setting.
     */
    public function set(string $key, mixed $value, bool $encrypt = false): Setting
    {
        $setting = Setting::query()->updateOrCreate(
            [
                'key' => $key,
            ],
            [
                'value' => $encrypt ? Crypt::encryptString((string)$value) : $value,
                'is_encrypted' => $encrypt,
            ]
        );

        $this->clearCache();

        return $setting;
    }

    /**
     * Delete a setting
     */
    public function forget(string $key): void
    {
        Setting::query()
            ->where('key', $key)
            ->delete();

        $this->clearCache();
    }

    /**
     * Clear settings cache.
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }

    public function saveMany(array $settings): void
    {
        DB::transaction(function () use ($settings) {

            /*
             * General settings.
             */
            $this->set(
                'enabled',
                (bool) data_get($settings, 'enabled', false)
            );

            $this->set(
                'guest.enabled',
                (bool) data_get($settings, 'guest.enabled', false)
            );

            $this->set(
                'provider',
                data_get($settings, 'provider')
            );

            /*
             * Global settings.
             */
            foreach (config('shopassist.settings', []) as $field => $fieldConfig) {

                $key = "settings.{$field}";

                if (! Arr::has($settings, $key)) {
                    continue;
                }

                $value = data_get($settings, $key);

                if (is_string($value)) {
                    $value = trim($value);
                }

                $this->set(
                    $key,
                    $value,
                    $fieldConfig['encrypted'] ?? false
                );
            }

            /*
             * Provider settings.
             */
            foreach (config('shopassist.providers', []) as $provider => $config) {

                foreach (($config['settings'] ?? []) as $field => $fieldConfig) {

                    $key = "providers.{$provider}.{$field}";

                    /*
                     * Ignore fields not present in the request.
                     */
                    if (! Arr::has($settings, $key)) {
                        continue;
                    }

                    $value = data_get($settings, $key);

                    /*
                     * Preserve existing encrypted values when blank.
                     */
                    if (
                        ($fieldConfig['encrypted'] ?? false)
                        && blank($value)
                    ) {
                        continue;
                    }

                    /*
                     * Normalize values.
                     */
                    if (is_string($value)) {
                        $value = trim($value);
                    }

                    $this->set($key, $value);
                }
            }
        });
    }

}