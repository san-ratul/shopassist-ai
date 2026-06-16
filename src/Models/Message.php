<?php

namespace SanRatul\ShopAssist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{

    protected $table = 'shopassist_messages';

    protected $fillable = [
        'conversation_id',
        'role',
        'content',
        'provider',
        'model',
        'tokens',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /*
     * The conversation this message belongs to
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isAssistant(): bool
    {
        return $this->role === 'assistant';
    }

    public function isSystem(): bool
    {
        return $this->role === 'system';
    }

    public function isTool(): bool
    {
        return $this->role === 'tool';
    }

}