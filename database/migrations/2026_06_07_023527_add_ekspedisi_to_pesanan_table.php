<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanan', 'ekspedisi')) {
                $table->string('ekspedisi', 100)->nullable()->after('status');
            }
            if (!Schema::hasColumn('pesanan', 'layanan_ekspedisi')) {
                $table->string('layanan_ekspedisi', 100)->nullable()->after('ekspedisi');
            }
            if (!Schema::hasColumn('pesanan', 'no_resi')) {
                $table->string('no_resi', 100)->nullable()->after('layanan_ekspedisi');
            }
        });
    }
    public function down()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn(['ekspedisi', 'layanan_ekspedisi', 'no_resi']);
        });
    }
};