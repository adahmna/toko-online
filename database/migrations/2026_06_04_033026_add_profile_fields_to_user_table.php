<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'foto')) {
                $table->string('foto')->nullable()->after('hp');
            }
            if (!Schema::hasColumn('user', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('foto');
            }
            if (!Schema::hasColumn('user', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['foto', 'tempat_lahir', 'tanggal_lahir']);
        });
    }
};