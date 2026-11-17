<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $t) {
            $t->string('type', 20)->default('text')->after('message'); // text | image | file | sticker
            $t->string('attachment_path')->nullable();
            $t->string('attachment_name')->nullable();
            $t->string('attachment_mime', 100)->nullable();
            $t->unsignedBigInteger('attachment_size')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $t) {
            $t->dropColumn(['type', 'attachment_path', 'attachment_name', 'attachment_mime', 'attachment_size']);
        });
    }
};