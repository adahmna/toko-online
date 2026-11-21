<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected static function booted()
    {
        // setiap pesanan baru -> chat otomatis berisi kartu konfirmasi alamat
        static::created(function ($pesanan) {
            try {
                \App\Http\Controllers\LiveChatController::notifyOrder($pesanan);
            } catch (\Throwable $e) {
                report($e);
            }
        });
    }

    protected $fillable = [
        'user_id',
        'kode_pesanan',
        'total_harga',
        'ongkir',
        'kota_tujuan',
        'id_kota_tujuan',
        'alamat',
        'kode_pos',
        'metode_pembayaran',
        'batas_pembayaran',
        'status',
        'is_read',
        'ekspedisi',
        'layanan_ekspedisi',
        'no_resi',
        'snap_token',
    ];

    protected $casts = [
        'batas_pembayaran' => 'datetime',
        'is_read'          => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(PesananDetail::class, 'pesanan_id');
    }

    public function details()
    {
        return $this->hasMany(PesananDetail::class, 'pesanan_id');
    }

    public function getTotalFormatAttribute(): string
    {
        $total = $this->total_harga;
        if ($total >= 1_000_000) {
            return 'Rp ' . number_format($total / 1_000_000, 1) . 'jt';
        } elseif ($total >= 1_000) {
            return 'Rp ' . number_format($total / 1_000, 0, ',', '') . 'rb';
        }
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    public function getSudahExpiredAttribute(): bool
    {
        if (! $this->batas_pembayaran) return false;
        return now()->greaterThan($this->batas_pembayaran);
    }

    public function getSisaDetikAttribute(): int
    {
        if (! $this->batas_pembayaran) return 0;
        return max(0, now()->diffInSeconds($this->batas_pembayaran, false));
    }

    public function fotoBukti()
    {
        return $this->hasMany(\App\Models\PesananFotoBukti::class, 'pesanan_id')->latest();
    }}