<?php

namespace SanRatul\ShopAssist\Providers\AI;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use SanRatul\ShopAssist\Contracts\AIProvider;
use SanRatul\ShopAssist\Services\SettingsService;
use SanRatul\ShopAssist\Support\AIResponse;

class GeminiProvider implements AIProvider
{
    public function __construct(
        protected SettingsService $settings
    ) {
    }

    /**
     * Send messages to Gemini and return a normalized response.
     *
     * @throws RequestException
     * @throws ConnectionException
     */
    public function chat(array $messages): AIResponse
    {
        $payload = $this->buildPayload($messages);

        $data = $this->sendRequest($payload);

        return $this->buildResponse($data);
    }

    /**
     * Get Gemini API key from settings.
     */
    protected function getApiKey(): string
    {
        $apiKey = $this->settings->get('providers.gemini.api_key');

        if (blank($apiKey)) {
            throw new RuntimeException(
                'Gemini API key is not configured.'
            );
        }

        return $apiKey;
    }

    /**
     * Get the configured Gemini model.
     */
    protected function getModel(): string
    {
        return $this->settings->get(
            'providers.gemini.model',
            'gemini-2.5-flash'
        );
    }

    /**
     * Build Gemini request payload.
     */
    protected function buildPayload(array $messages): array
    {
        $payload = [
            'contents' => $this->transformMessages($messages),

            'generationConfig' => [
                'temperature' => (float) $this->settings->get(
                    'providers.gemini.temperature',
                    0.7
                ),

                'maxOutputTokens' => (int) $this->settings->get(
                    'providers.gemini.max_tokens',
                    2048
                ),
            ],
        ];

        /*
         * Gemini handles system prompts separately.
         */
        if ($systemPrompt = $this->getSystemPrompt($messages)) {
            $payload['systemInstruction'] = [
                'parts' => [
                    [
                        'text' => $systemPrompt,
                    ],
                ],
            ];
        }

        return $payload;
    }

    /**
     * Extract the system prompt from messages.
     */
    protected function getSystemPrompt(array $messages): ?string
    {
        foreach ($messages as $message) {
            if (($message['role'] ?? null) === 'system') {
                return $message['content'] ?? null;
            }
        }

        return null;
    }

    /**
     * Transform OpenAI-style messages into Gemini format.
     *
     * OpenAI:
     *
     * [
     *     [
     *         'role' => 'user',
     *         'content' => 'Hello',
     *     ],
     *     [
     *         'role' => 'assistant',
     *         'content' => 'Hi!',
     *     ],
     * ]
     *
     * Gemini:
     *
     * [
     *     [
     *         'role' => 'user',
     *         'parts' => [
     *             [
     *                 'text' => 'Hello',
     *             ],
     *         ],
     *     ],
     *     [
     *         'role' => 'model',
     *         'parts' => [
     *             [
     *                 'text' => 'Hi!',
     *             ],
     *         ],
     *     ],
     * ]
     */
    protected function transformMessages(array $messages): array
    {
        return collect($messages)
            ->filter(function ($message) {
                return in_array(
                    $message['role'] ?? null,
                    ['user', 'assistant'],
                    true
                );
            })
            ->map(function ($message) {
                return [
                    'role' => ($message['role'] ?? null) === 'assistant'
                        ? 'model'
                        : 'user',

                    'parts' => [
                        [
                            'text' => (string) ($message['content'] ?? ''),
                        ],
                    ],
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Send request to Gemini API.
     *
     * @throws RequestException
     * @throws ConnectionException
     */
    protected function sendRequest(array $payload): array
    {
        $response = Http::timeout(60)
            ->withHeaders([
                'x-goog-api-key' => $this->getApiKey(),
            ])
            ->post(
                $this->getEndpoint(),
                $payload
            );

        $response->throw();

        return $response->json();
    }

    /**
     * Build the Gemini endpoint URL.
     */
    protected function getEndpoint(): string
    {
        return sprintf(
            '%s/%s/models/%s:generateContent',
            rtrim(
                config(
                    'shopassist.providers.gemini.base_url',
                    'https://generativelanguage.googleapis.com'
                ),
                '/'
            ),
            config(
                'shopassist.providers.gemini.api_version',
                'v1beta'
            ),
            $this->getModel()
        );
    }

    /**
     * Convert Gemini response into a normalized AIResponse.
     */
    protected function buildResponse(array $data): AIResponse
    {
        $content = data_get(
            $data,
            'candidates.0.content.parts.0.text'
        );

        if (blank($content)) {
            throw new RuntimeException(
                'Gemini returned an empty response.'
            );
        }

        return new AIResponse(
            content: $content,
            provider: 'gemini',
            model: $this->getModel(),
            tokens: data_get(
                $data,
                'usageMetadata.totalTokenCount'
            ),
            metadata: [
                'finish_reason' => data_get(
                    $data,
                    'candidates.0.finishReason'
                ),

                'usage' => [
                    'prompt_tokens' => data_get(
                        $data,
                        'usageMetadata.promptTokenCount'
                    ),

                    'completion_tokens' => data_get(
                        $data,
                        'usageMetadata.candidatesTokenCount'
                    ),

                    'thought_tokens' => data_get(
                        $data,
                        'usageMetadata.thoughtsTokenCount'
                    ),

                    'total_tokens' => data_get(
                        $data,
                        'usageMetadata.totalTokenCount'
                    ),
                ],

                'response_id' => data_get(
                    $data,
                    'responseId'
                ),
            ],
        );
    }
}