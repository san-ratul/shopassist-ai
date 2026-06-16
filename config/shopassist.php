<?php


return [

    'settings' => [
        'system_prompt' => [
            'label' => 'System Prompt',
            'type' => 'textarea',
            'required' => false,
            'default' => 'You are a customer support assistant for XYZ Store. Be polite. Answer briefly. If you don\'t know the answer, ask the customer to contact support.',
            'help' => 'Define how the AI assistant should behave.',
        ],
    ],

    'providers' => [

        'openai' => [
            'label' => 'OpenAI',

            'settings' => [
                'api_key' => [
                    'label' => 'API Key',
                    'type' => 'password',
                    'encrypted' => true,
                    'required' => true,
                ],
            ],
        ],

        'gemini' => [
            'label' => 'Gemini',

            'base_url' => env(
                'SHOPASSIST_GEMINI_BASE_URL',
                'https://generativelanguage.googleapis.com'
            ),

            'api_version' => env(
                'SHOPASSIST_GEMINI_API_VERSION',
                'v1beta'
            ),

            'settings' => [
                'api_key' => [
                    'label' => 'API Key',
                    'type' => 'password',
                    'encrypted' => true,
                    'required' => true,
                ],

                'model' => [
                    'label' => 'Model',
                    'type' => 'text',
                    'default' => 'gemini-2.5-flash',
                    'required' => true,
                ],

                'temperature' => [
                    'label' => 'Temperature',
                    'type' => 'number',
                    'default' => 0.7,
                ],

                'max_tokens' => [
                    'label' => 'Max Tokens',
                    'type' => 'number',
                    'default' => 2048,
                ],
            ],
        ],

        'anthropic' => [
            'label' => 'Anthropic',

            'settings' => [
                'api_key' => [
                    'label' => 'API Key',
                    'type' => 'password',
                    'encrypted' => true,
                    'required' => true,
                ],
            ],
        ],

    ],

];