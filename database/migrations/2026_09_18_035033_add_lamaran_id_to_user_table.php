<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->foreignId('lamaran_id')->nullable()->after('id')
                ->constrained('lamaran_kerja')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropForeign(['lamaran_id']);
            $table->dropColumn('lamaran_id');
        });
    }
};