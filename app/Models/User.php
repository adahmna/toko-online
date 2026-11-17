<?php

namespace App\Models;

use App\Models\Concerns\SyncsChatSnapshot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SyncsChatSnapshot;

    const ROLE_SUPERADMIN = '1';
    const ROLE_ADMIN      = '0';
    const ROLE_CUSTOMER   = '2';

    protected $table = 'user';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'hp',
        'role',
        'status',
        'foto',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'is_read',
        'lamaran_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN]);
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }
}