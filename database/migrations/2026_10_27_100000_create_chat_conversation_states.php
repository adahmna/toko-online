<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_conversation_states', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('conv_id')->unique(); // >0 = customer, <0 = tamu
            $table->timestamp('archived_at')->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->string('blocked_reason', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_conversation_states');
    }
};