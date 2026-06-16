<?php

namespace SanRatul\ShopAssist\Services;

use SanRatul\ShopAssist\Support\AIResponse;

class ChatService
{
    public function __construct(
        protected ConversationService $conversations,
        protected ProviderManager $providers,
        protected SettingsService $settings,
    ) {
    }

    /**
     * Send a guest message to the AI provider.
     */
    public function send(string $message)
    {
        // Find or create the active guest conversation.
        $conversation = $this->conversations
            ->findOrCreateGuest();

        // Store the user's message.
        $this->conversations->addMessage(
            $conversation,
            'user',
            $message
        );

        // Build messages with system prompt.
        $messages = $this->buildMessages($conversation);

        // Send the conversation to the configured provider.
        $response = $this->providers
            ->driver()
            ->chat($messages);

        // Store the assistant response.
        $this->conversations->addMessage(
            $conversation,
            'assistant',
            $response->content,
            $response
        );

        return $response;
    }

    /**
     * Build provider-agnostic messages.
     */
    protected function buildMessages($conversation): array
    {
        $messages = $this->conversations
            ->history($conversation);

        $systemPrompt = $this->settings->get(
            'settings.system_prompt'
        );

        if (! empty($systemPrompt)) {
            array_unshift($messages, [
                'role' => 'system',
                'content' => $systemPrompt,
            ]);
        }

        return $messages;
    }
}