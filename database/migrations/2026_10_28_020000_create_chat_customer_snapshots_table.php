<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_customer_snapshots', function (Blueprint $t) {
            $t->unsignedBigInteger('conv_id')->primary(); // id user lama (= chat_messages.customer_id lama)
            $t->string('nama')->nullable();
            $t->string('email')->nullable()->index();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_customer_snapshots');
    }
};