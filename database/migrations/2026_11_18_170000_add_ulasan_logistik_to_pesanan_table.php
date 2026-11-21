<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $t = (new \App\Models\Pesanan)->getTable();
        if (!Schema::hasColumn($t, 'ulasan_logistik')) {
            Schema::table($t, function (Blueprint $table) {
                $table->text('ulasan_logistik')->nullable();
            });
        }
    }

    public function down(): void
    {
        $t = (new \App\Models\Pesanan)->getTable();
        if (Schema::hasColumn($t, 'ulasan_logistik')) {
            Schema::table($t, function (Blueprint $table) {
                $table->dropColumn('ulasan_logistik');
            });
        }
    }
};