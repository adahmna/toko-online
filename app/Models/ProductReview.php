<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $table = 'product_reviews';
    protected $fillable = ['produk_id', 'user_id', 'pesanan_id', 'rating', 'komentar', 'foto'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}