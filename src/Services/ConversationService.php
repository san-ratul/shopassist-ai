<?php

namespace SanRatul\ShopAssist\Services;

use Illuminate\Support\Str;
use SanRatul\ShopAssist\Models\Conversation;
use SanRatul\ShopAssist\Models\Message;
use SanRatul\ShopAssist\Support\AIResponse;

class ConversationService
{
    /** 
     * Find the active guest conversation for the current session.
     * Create a new one if none exists. 
     */
    public function findOrCreateGuest(): Conversation
    {
        $sessionId = session()->getId();

        $conversation = Conversation::query()
                            ->where('session_id', $sessionId)
                            ->whereNull('ended_at')
                            ->latest()
                            ->first();

        if (! empty($conversation)) {
            return $conversation;
        }

        return Conversation::create([
            'uuid' => (string) Str::uuid(),
            'session_id' => $sessionId,
            'started_at' => now(),
        ]);
    }

    /**
     * Save a message into the conversation.
     */
    public function addMessage(
        Conversation $conversation,
        string $role,
        string $content,
        ?AIResponse $response = null
    ): Message
    {
        $data = [
            'role' => $role,
            'content' => $content,
        ];

        if ($response !== null) {
            $data['provider'] = $response->provider;
            $data['model'] = $response->model;
            $data['tokens'] = $response->tokens;
            $data['metadata'] = $response->metadata;
        }

        $message = $conversation
                    ->messages()
                    ->create($data);

        $conversation->incrementMessageCount();

        if (! empty($response) && $response->tokens) {
            $conversation->incrementTokenUsage(
                $response->tokens,
            );
        }

        return $message;
    }

    /**
     * Return conversation history in OpenAI-style format.
     */
    public function history(Conversation $conversation): array
    {
        return $conversation
                ->messages()
                ->oldest()
                ->get()
                ->map(function ($message) {
                    return [
                        'role' => $message->role,
                        'content' => $message->content,
                    ];
                })
                ->all();
    }

    /**
     * Mark a conversation as ended.
     */

    public function end(Conversation $conversation): void
    {
        $conversation->end();
    }
}