<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('USER', function (Blueprint $table) {
            if (!Schema::hasColumn('USER', 'tempat_lahir')) {
                $table->string('tempat_lahir', 100)->nullable()->after('hp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('USER', function (Blueprint $table) {
            if (Schema::hasColumn('USER', 'tempat_lahir')) {
                $table->dropColumn('tempat_lahir');
            }
        });
    }
};