<?php

namespace SanRatul\ShopAssist\Support;

use Illuminate\Validation\Rule;
use SanRatul\ShopAssist\Services\SettingsService;

class SettingsValidator
{
    public function __construct(
        protected SettingsService $settings,
    ) {
    }

    public function rules(array $data = []): array
    {
        $selectedProvider = $data['provider'] ?? null;

        $rules = [
            'enabled' => ['boolean'],
            'guest.enabled' => ['boolean'],
            'provider' => [
                'nullable',
                Rule::in(array_keys(config('shopassist.providers', []))),
            ],
        ];

        foreach (config('shopassist.providers', []) as $provider => $config) {

            foreach (($config['settings'] ?? []) as $field => $fieldConfig) {

                $key = "providers.{$provider}.{$field}";

                $fieldRules = ['nullable', 'string'];

                $isRequired = $fieldConfig['required'] ?? false;
                $isEncrypted = $fieldConfig['encrypted'] ?? false;

                /*
                 * Only validate the selected provider.
                 */
                if ($provider === $selectedProvider && $isRequired) {

                    /*
                     * Skip required validation if an encrypted
                     * value already exists.
                     */
                    $existingValue = $isEncrypted
                        ? $this->settings->get($key)
                        : null;

                    if (blank($existingValue)) {
                        array_unshift($fieldRules, 'required');
                    }
                }

                $rules[$key] = $fieldRules;
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];

        foreach (config('shopassist.providers', []) as $provider => $config) {

            foreach (($config['settings'] ?? []) as $field => $fieldConfig) {

                $key = "providers.{$provider}.{$field}";

                if ($fieldConfig['required'] ?? false) {
                    $messages["{$key}.required"] = sprintf(
                        'Please enter your %s %s.',
                        $config['label'],
                        $fieldConfig['label']
                    );
                }
            }
        }

        return $messages;
    }
}