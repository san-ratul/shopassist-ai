<?php

namespace SanRatul\ShopAssist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Conversation extends Model
{

    protected $table = 'shopassist_conversations';

    protected $fillable = [
        'uuid',
        'session_id',
        'started_at',
        'ended_at',
        'total_messages',
        'total_tokens',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * The owner of this conversation.
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Messages belongs to this conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(
            Message::class,
            'conversation_id',
        );
    }

    public function end(): void
    {
        $this->update([
            'ended_at' => now(),
        ]);
    }

    public function incrementMessageCount(int $count = 1): void
    {
        $this->increment('total_messages', $count);
    }

    public function incrementTokenUsage(int $tokens): void
    {
        $this->increment('total_tokens', $tokens);
    }

    public function isGuest(): bool
    {
        return $this->user === null;
    }

    public function isEnded(): bool
    {
        return $this->ended_at !== null;
    }


}