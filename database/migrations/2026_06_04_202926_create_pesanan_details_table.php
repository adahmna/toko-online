<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pesanan_details')) {
            Schema::create('pesanan_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pesanan_id');
                $table->unsignedBigInteger('produk_id');
                $table->string('nama_produk');
                $table->decimal('harga', 15, 2);
                $table->integer('jumlah');
                $table->decimal('subtotal', 15, 2);
                $table->timestamps();
                $table->foreign('pesanan_id')
                      ->references('id')
                      ->on('pesanan')
                      ->onDelete('cascade');
            });
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('pesanan_details');
    }
};