<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopassist_messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conversation_id')
                ->constrained('shopassist_conversations')
                ->cascadeOnDelete();

            $table->string('role', 20);

            $table->longText('content');

            // openai, anthropic, google, ollama, openrouter, etc.
            $table->string('provider', 50)->nullable();

            // gpt-4o-mini, claude-sonnet-4, gemini-2.5-pro, etc.
            $table->string('model', 100)->nullable();

            // Token usage for this specific message
            $table->unsignedInteger('tokens')->nullable();

            // Additional provider-specific data
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);

            $table->index(['provider', 'model']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopassist_messages');
    }
};