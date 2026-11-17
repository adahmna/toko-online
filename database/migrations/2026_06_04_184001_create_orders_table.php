<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_order')->unique();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->enum('status', ['pending', 'proses', 'selesai', 'batal'])->default('pending');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};