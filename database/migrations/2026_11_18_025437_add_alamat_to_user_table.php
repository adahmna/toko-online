<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('user', 'alamat')) {
            Schema::table('user', function (Blueprint $table) {
                $table->string('alamat')->nullable()->after('hp');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('user', 'alamat')) {
            Schema::table('user', function (Blueprint $table) {
                $table->dropColumn('alamat');
            });
        }
    }
};