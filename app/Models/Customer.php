<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'google_id',
        'google_token',
        'alamat',
        'pos',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public static function boot()
    {
        parent::boot();
        static::deleting(function ($customer) {
            // Hapus user yang terkait ketika customer dihapus
            if ($customer->user) {
                $customer->user->delete();
            }
        });
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}