<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->decimal('ongkir', 15, 2)->default(0)->after('total_harga');
            $table->string('kota_tujuan', 100)->nullable()->after('ongkir');
            $table->string('id_kota_tujuan', 20)->nullable()->after('kota_tujuan');
        });
    }

    public function down()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['ongkir', 'kota_tujuan', 'id_kota_tujuan']);
        });
    }
};