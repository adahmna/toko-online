<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah kolom durasi untuk voice note / audio di tabel chat_messages.
 *
 * CATATAN: kalau kolom `type` di tabel ini bertipe ENUM('text','image','file','sticker'),
 * nilai 'audio' dan 'voice' harus ditambahkan juga. Di MySQL:
 *   ALTER TABLE chat_messages MODIFY type VARCHAR(20) NOT NULL DEFAULT 'text';
 * Kalau kolomnya sudah string biasa, tidak perlu melakukan apa-apa.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('chat_messages', 'attachment_duration')) {
                $table->unsignedSmallInteger('attachment_duration')->nullable()->after('attachment_size');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            if (Schema::hasColumn('chat_messages', 'attachment_duration')) {
                $table->dropColumn('attachment_duration');
            }
        });
    }
};