<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // berat disimpan dalam GRAM, boleh desimal
        DB::statement('ALTER TABLE produk MODIFY berat DECIMAL(14,2) NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE produk MODIFY berat INT NOT NULL DEFAULT 0');
    }
};