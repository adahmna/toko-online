<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanan', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('status');
            }
        });

        Schema::table('USER', function (Blueprint $table) {
            if (!Schema::hasColumn('USER', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('foto');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pesanan', 'is_read')) {
                $table->dropColumn('is_read');
            }
        });

        Schema::table('USER', function (Blueprint $table) {
            if (Schema::hasColumn('USER', 'is_read')) {
                $table->dropColumn('is_read');
            }
        });
    }
};