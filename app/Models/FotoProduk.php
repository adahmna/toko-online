<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoProduk extends Model
{
    use HasFactory;

    protected $table = 'foto_produk';
    protected $guarded = [];
    
    protected $fillable = [
        'produk_id',
        'foto',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function gambar()
    {
        return $this->hasMany(FotoProduk::class, 'produk_id');
    }
    
}