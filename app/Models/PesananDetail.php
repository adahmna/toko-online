<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    protected $table    = 'pesanan_details';
    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'nama_produk',
        'harga',
        'jumlah',
        'subtotal',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}