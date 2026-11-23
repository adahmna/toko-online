<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Produk;

class BatalkanPesananMenggantung extends Command
{
    protected $signature = 'pesanan:batalkan-menggantung {--menit=60}';
    protected $description = 'Batalkan pesanan yang belum pernah masuk ke pembayaran dan kembalikan stoknya';

    public function handle(): int
    {
        $batas = now()->subMinutes((int) $this->option('menit'));

        $ids = Pesanan::where('status', 'Menunggu Pembayaran')
            ->whereNull('snap_token')
            ->where('created_at', '<', $batas)
            ->pluck('id');

        $jumlah = 0;

        foreach ($ids as $id) {
            DB::transaction(function () use ($id, &$jumlah) {
                $pesanan = Pesanan::where('id', $id)->lockForUpdate()->first();

                // cek ulang: mungkin sudah berubah sejak query pertama
                if (!$pesanan || $pesanan->status !== 'Menunggu Pembayaran' || $pesanan->snap_token) {
                    return;
                }

                $pesanan->update(['status' => 'Dibatalkan']);

                foreach (PesananDetail::where('pesanan_id', $pesanan->id)->get() as $d) {
                    Produk::where('id', $d->produk_id)->increment('stok', $d->jumlah);
                }

                $jumlah++;
            });
        }

        $this->info("Pesanan dibatalkan: {$jumlah}");
        return self::SUCCESS;
    }
}