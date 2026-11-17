<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_guests', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('nama', 60)->default('Tamu');
            $table->timestamps();
        });

        // customer_id boleh kosong untuk pesan dari tamu
        DB::statement('ALTER TABLE chat_messages MODIFY customer_id BIGINT UNSIGNED NULL');

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('guest_id')->nullable()->after('customer_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn('guest_id');
        });
        Schema::dropIfExists('chat_guests');
    }
};