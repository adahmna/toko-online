<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    public $timestamps = false;
    protected $table = "kategori";
    protected $guarded = ['id'];

    // Tambahkan ini
    public function produk()
    {
        return $this->hasMany(\App\Models\Produk::class, 'kategori_id');
    }
}