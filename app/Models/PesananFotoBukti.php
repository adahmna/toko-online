<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananFotoBukti extends Model
{
    protected $table = 'pesanan_foto_bukti';
    protected $fillable = ['pesanan_id', 'foto', 'keterangan'];
}