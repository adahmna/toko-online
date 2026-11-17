<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanan', 'kota_tujuan')) {
                $table->string('kota_tujuan')->nullable();
            }
            if (!Schema::hasColumn('pesanan', 'id_kota_tujuan')) {
                $table->string('id_kota_tujuan')->nullable();
            }
            if (!Schema::hasColumn('pesanan', 'alamat')) {
                $table->text('alamat')->nullable();
            }
            if (!Schema::hasColumn('pesanan', 'kode_pos')) {
                $table->string('kode_pos')->nullable();
            }
            if (!Schema::hasColumn('pesanan', 'ekspedisi')) {
                $table->string('ekspedisi')->nullable();
            }
            if (!Schema::hasColumn('pesanan', 'layanan_ekspedisi')) {
                $table->string('layanan_ekspedisi')->nullable();
            }
            if (!Schema::hasColumn('pesanan', 'ongkir')) {
                $table->decimal('ongkir', 12, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $columns = ['kota_tujuan', 'id_kota_tujuan', 'alamat', 'kode_pos', 'ekspedisi', 'layanan_ekspedisi', 'ongkir'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('pesanan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};