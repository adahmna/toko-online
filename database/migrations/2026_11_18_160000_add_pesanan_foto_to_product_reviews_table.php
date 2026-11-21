<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('product_reviews', 'pesanan_id')) {
                $table->unsignedBigInteger('pesanan_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('product_reviews', 'foto')) {
                $table->string('foto')->nullable()->after('komentar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            if (Schema::hasColumn('product_reviews', 'foto')) { $table->dropColumn('foto'); }
            if (Schema::hasColumn('product_reviews', 'pesanan_id')) { $table->dropColumn('pesanan_id'); }
        });
    }
};