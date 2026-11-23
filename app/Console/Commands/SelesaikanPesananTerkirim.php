<?php

namespace App\Console\Commands;

use App\Mail\StatusPesananMail;
use App\Models\Pesanan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SelesaikanPesananTerkirim extends Command
{
    protected $signature = 'pesanan:selesaikan-terkirim
                            {--hari=7 : Dikirim -> Selesai otomatis jika kurir tidak pernah lapor DELIVERED dan sudah lebih dari N hari sejak dikirim (0 = nonaktif)}
                            {--hari-tiba=3 : Tiba -> Selesai otomatis jika sudah lebih dari N hari sejak tiba (0 = nonaktif)}
                            {--maks=50 : Maksimal pesanan yang dicek per tahap per eksekusi (membatasi pemakaian kredit API)}';

    protected $description = 'Dikirim -> Tiba jika kurir melaporkan DELIVERED; Tiba -> Selesai setelah masa tunggu komplain';

    public function handle(): int
    {
        $hari     = max(0, (int) $this->option('hari'));
        $hariTiba = max(0, (int) $this->option('hari-tiba'));
        $maks     = max(1, (int) $this->option('maks'));

        $tiba    = 0;
        $selesai = 0;

        // ---------------------------------------------------------------
        // TAHAP 1: pesanan "Dikirim" -> "Tiba" (atau "Selesai" lewat batas hari)
        // ---------------------------------------------------------------
        $ids = Pesanan::whereRaw('LOWER(TRIM(status)) = ?', ['dikirim'])
            ->whereNotNull('no_resi')
            ->where('no_resi', '!=', '')
            ->orderBy('updated_at')
            ->limit($maks)
            ->pluck('id');

        foreach ($ids as $id) {
            $pesanan = Pesanan::find($id);
            if (!$pesanan) {
                continue;
            }

            $statusKurir = $this->statusKurir($pesanan); // 'DELIVERED' / status lain / null (gagal cek)

            if ($statusKurir === 'DELIVERED') {
                // paket sampai: pindah ke Tiba, pembeli masih punya waktu untuk konfirmasi/komplain
                $p = $this->ubahStatus($id, 'dikirim', 'Tiba', ['tiba_at' => now()]);
                if ($p) {
                    $tiba++;
                    $this->line("Pesanan {$p->kode_pesanan} tiba: kurir melaporkan paket diterima");
                    $this->kirimEmail($p);
                }
                continue;
            }

            // fallback: kurir tidak pernah lapor DELIVERED, hitung dari waktu dikirim
            $waktuKirim = $pesanan->dikirim_at ?? $pesanan->updated_at;

            if (
                $hari > 0
                && $waktuKirim
                && $waktuKirim->lt(now()->subDays($hari))
                && !str_contains((string) $statusKurir, 'RETURN') // paket diretur: jangan diselesaikan otomatis
            ) {
                $p = $this->ubahStatus($id, 'dikirim', 'Selesai', ['tiba_at' => now()]);
                if ($p) {
                    $selesai++;
                    $this->line("Pesanan {$p->kode_pesanan} selesai: melewati batas {$hari} hari sejak dikirim");
                    $this->kirimEmail($p);
                }
            }
        }

        // ---------------------------------------------------------------
        // TAHAP 2: pesanan "Tiba" -> "Selesai" setelah masa tunggu
        // ---------------------------------------------------------------
        if ($hariTiba > 0) {
            $idsTiba = Pesanan::whereRaw('LOWER(TRIM(status)) = ?', ['tiba'])
                ->whereNotNull('tiba_at')
                ->where('tiba_at', '<=', now()->subDays($hariTiba))
                ->orderBy('tiba_at')
                ->limit($maks)
                ->pluck('id');

            foreach ($idsTiba as $id) {
                $p = $this->ubahStatus($id, 'tiba', 'Selesai');
                if ($p) {
                    $selesai++;
                    $this->line("Pesanan {$p->kode_pesanan} selesai: lewat {$hariTiba} hari sejak tiba");
                    $this->kirimEmail($p);
                }
            }
        }

        $this->info("Pesanan tiba: {$tiba}, pesanan diselesaikan: {$selesai}");
        return self::SUCCESS;
    }

    // Kunci baris & cek ulang status, supaya tidak bentrok dengan perubahan admin.
    // Mengembalikan pesanan yang sudah diubah, atau null kalau statusnya sudah bukan $dari.
    private function ubahStatus(int $id, string $dari, string $ke, array $tambahan = []): ?Pesanan
    {
        return DB::transaction(function () use ($id, $dari, $ke, $tambahan) {
            $p = Pesanan::where('id', $id)->lockForUpdate()->first();
            if (!$p || strtolower(trim((string) $p->status)) !== $dari) {
                return null;
            }
            $p->forceFill(['status' => $ke] + $tambahan)->save();
            return $p;
        });
    }

    // Email notifikasi (gagal kirim tidak membatalkan perubahan status)
    private function kirimEmail(Pesanan $p): void
    {
        if ($p->user && $p->user->email) {
            try {
                Mail::to($p->user->email)->send(new StatusPesananMail($p));
            } catch (\Throwable $e) {
                Log::error('[Pesanan] Gagal kirim email status otomatis: ' . $e->getMessage());
            }
        }
    }

    // Status paket dari Binderbyte. Null kalau resi/ekspedisi tidak lengkap atau API gagal.
    private function statusKurir(Pesanan $p): ?string
    {
        $kurir = $this->kodeKurir($p->ekspedisi);
        $resi  = trim((string) $p->no_resi);

        if ($kurir === '' || $resi === '') {
            return null;
        }

        try {
            $r = Http::timeout(10)->get('https://api.binderbyte.com/v1/track', [
                'api_key' => config('services.binderbyte.api_key'),
                'courier' => $kurir,
                'awb'     => $resi,
            ]);
            $j = $r->json();

            if (!$r->ok() || (int) ($j['status'] ?? 0) !== 200) {
                return null;
            }

            return strtoupper((string) ($j['data']['summary']['status'] ?? ''));
        } catch (\Throwable $e) {
            Log::warning('[SelesaiOtomatis] Binderbyte gagal untuk ' . $p->kode_pesanan . ': ' . $e->getMessage());
            return null;
        }
    }

    // Samakan nama ekspedisi di DB dengan kode kurir Binderbyte (sama seperti di PesananController)
    private function kodeKurir(?string $e): string
    {
        $e = preg_replace('/[^a-z0-9]/', '', strtolower(trim((string) $e)));
        $map = [
            'jne' => 'jne', 'jnexpress' => 'jne',
            'jnt' => 'jnt', 'jt' => 'jnt', 'jet' => 'jnt', 'jtexpress' => 'jnt',
            'sicepat' => 'sicepat',
            'anteraja' => 'anteraja',
            'pos' => 'pos', 'posindonesia' => 'pos',
            'tiki' => 'tiki',
            'ninja' => 'ninja', 'ninjaxpress' => 'ninja',
            'sap' => 'sap',
        ];
        return $map[$e] ?? $e;
    }
}