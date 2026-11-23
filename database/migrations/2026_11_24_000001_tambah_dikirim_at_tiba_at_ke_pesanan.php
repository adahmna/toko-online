<?php

use App\Models\Pesanan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function tabel(): string
    {
        // ambil nama tabel dari model, jadi tidak perlu menebak 'pesanan' atau 'pesanans'
        return (new Pesanan())->getTable();
    }

    public function up(): void
    {
        Schema::table($this->tabel(), function (Blueprint $table) {
            $table->timestamp('dikirim_at')->nullable();
            $table->timestamp('tiba_at')->nullable();
        });

        // pesanan yang sedang Dikirim saat ini: pakai updated_at sebagai perkiraan waktu kirim
        DB::table($this->tabel())
            ->whereRaw('LOWER(TRIM(status)) = ?', ['dikirim'])
            ->whereNull('dikirim_at')
            ->update(['dikirim_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table($this->tabel(), function (Blueprint $table) {
            $table->dropColumn(['dikirim_at', 'tiba_at']);
        });
    }
};