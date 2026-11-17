<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('keranjang')) {
            Schema::create('keranjang', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('user')->onDelete('cascade');
                $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
                $table->integer('jumlah')->default(1);
                $table->decimal('harga', 12, 2);
                $table->timestamps();

                // Satu user hanya punya satu baris per produk
                $table->unique(['user_id', 'produk_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};