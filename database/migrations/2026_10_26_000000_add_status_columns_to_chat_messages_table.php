<?php

use App\Models\ChatMessage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $t->timestamp('delivered_at')->nullable()->after('is_read');
            $t->timestamp('read_at')->nullable()->after('delivered_at');
        });

        // Pesan lama yang sudah dibaca -> langsung dianggap delivered + read
        DB::table($this->table())
            ->where('is_read', 1)
            ->update([
                'delivered_at' => DB::raw('created_at'),
                'read_at'      => DB::raw('created_at'),
            ]);
    }

    public function down(): void
    {
        Schema::table($this->table(), function (Blueprint $t) {
            $t->dropColumn(['delivered_at', 'read_at']);
        });
    }
};