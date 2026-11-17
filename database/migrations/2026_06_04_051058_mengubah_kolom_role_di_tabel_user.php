<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            // Kita ubah tipe data 'role' menjadi VARCHAR (string) biasa 
            // agar bisa menampung kata 'admin', 'user', maupun 'customer' tanpa batasan ENUM
            $table->string('role', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->change();
        });
    }
};