<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LamaranKerja extends Model
{
    protected $table = 'lamaran_kerja';

    protected $fillable = [
        'nama', 'email', 'hp', 'alasan', 'cv', 'status', 'catatan_admin',
    ];

    const STATUS_PENDING  = 0;
    const STATUS_DITERIMA = 1;
    const STATUS_DITOLAK  = 2;

    public function user()
    {
        return $this->hasOne(User::class, 'lamaran_id');
    }
}