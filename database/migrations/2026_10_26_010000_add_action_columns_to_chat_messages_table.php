<?php

use App\Models\ChatMessage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function table(): string
    {
        return (new ChatMessage)->getTable();
    }

    public function up(): void
    {
        Schema::table($this->table(), function (Blueprint $t) {
            $t->timestamp('edited_at')->nullable();            // pesan pernah diedit
            $t->timestamp('deleted_everyone_at')->nullable();  // dihapus untuk semua orang
            $t->boolean('hidden_customer')->default(false);    // dihapus untuk customer saja
            $t->boolean('hidden_admin')->default(false);       // dihapus untuk admin saja
            $t->unsignedBigInteger('reply_to_id')->nullable(); // membalas pesan tertentu
        });
    }

    public function down(): void
    {
        Schema::table($this->table(), function (Blueprint $t) {
            $t->dropColumn(['edited_at', 'deleted_everyone_at', 'hidden_customer', 'hidden_admin', 'reply_to_id']);
        });
    }
};