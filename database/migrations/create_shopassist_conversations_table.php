<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopassistConversationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('shopassist_conversations', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->nullableMorphs('user');

            $table->string('session_id')->nullable()->index();

            $table->timestamp('started_at')->nullable();

            $table->timestamp('ended_at')->nullable();

            $table->unsignedInteger('total_messages')->default(0);

            $table->unsignedInteger('total_tokens')->default(0);

            $table->timestamps();

            $table->index(['user_type', 'user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopassist_conversations');
    }
}