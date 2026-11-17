<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $guarded = [];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function gambar()
    {
        return $this->hasMany(FotoProduk::class, 'produk_id');
    }

    // ✅ Tambahkan ini
    public function pesananDetail()
    {
        return $this->hasMany(PesananDetail::class, 'produk_id');
    }
}