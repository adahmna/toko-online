<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // kolom product_id untuk kartu produk
        if (!Schema::hasColumn('chat_messages', 'product_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->nullable();
            });
        }

        // pastikan kolom type menerima nilai 'product' (kalau sebelumnya ENUM, ini melonggarkannya)
        DB::statement("ALTER TABLE chat_messages MODIFY type VARCHAR(20) NOT NULL DEFAULT 'text'");
    }

    public function down(): void
    {
        if (Schema::hasColumn('chat_messages', 'product_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }
    }
};