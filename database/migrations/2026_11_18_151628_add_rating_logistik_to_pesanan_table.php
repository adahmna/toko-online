<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pesanan', 'rating_logistik')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->unsignedTinyInteger('rating_logistik')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('rating_logistik');
        });
    }
};