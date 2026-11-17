<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $t) {
            // admin yang membalas (untuk menampilkan nama admin di bubble customer)
            if (!Schema::hasColumn('chat_messages', 'admin_id')) {
                $t->unsignedBigInteger('admin_id')->nullable();
            }
            // durasi voice note / audio (detik)
            if (!Schema::hasColumn('chat_messages', 'attachment_duration')) {
                $t->unsignedInteger('attachment_duration')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $t) {
            if (Schema::hasColumn('chat_messages', 'admin_id')) {
                $t->dropColumn('admin_id');
            }
            if (Schema::hasColumn('chat_messages', 'attachment_duration')) {
                $t->dropColumn('attachment_duration');
            }
        });
    }
};