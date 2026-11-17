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
            if (!Schema::hasColumn('pesanan', 'no_resi')) {
                $table->string('no_resi')->nullable()->after('status');
            }
        });
    }
    public function down()
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('no_resi');
        });
    }
};