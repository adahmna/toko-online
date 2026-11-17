<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $fillable = [
        'customer_id',
        'user_id',
        'status',
        'noresi',
        'kurir',
        'layanan_ongkir',
        'biaya_ongkir',
        'estimasi_ongkir',
        'total_berat',
        'total_harga',
        'alamat',
        'pos',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Format total harga ke Rupiah singkat (misal: Rp 150rb)
    public function getTotalFormatAttribute(): string
    {
        $total = $this->total_harga;
        if ($total >= 1000000) {
            return 'Rp ' . number_format($total / 1000000, 1) . 'jt';
        } elseif ($total >= 1000) {
            return 'Rp ' . number_format($total / 1000, 0, ',', '') . 'rb';
        }
        return 'Rp ' . number_format($total, 0, ',', '.');
    }
}