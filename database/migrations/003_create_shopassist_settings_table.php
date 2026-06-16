<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shopassist_settings', function (Blueprint $table) {
            $table->id();

            $table->string('key')->unique();

            $table->longText('value')->nullable();

            $table->boolean('is_encrypted')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopassist_settings');
    }
};