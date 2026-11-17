<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            // Mengubah tipe data role menjadi string/varchar biasa agar tidak error lagi
            $table->string('role', 50)->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->enum('role', ['admin', 'user'])->change();
        });
    }
};