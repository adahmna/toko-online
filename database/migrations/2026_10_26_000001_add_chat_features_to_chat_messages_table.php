<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah kolom untuk:
 *  - admin_id            : siapa admin yang mengirim pesan (supaya nama admin tampil di chat customer)
 *  - reaction_customer   : reaksi emoji dari customer
 *  - reaction_admin      : reaksi emoji dari admin
 *  - starred_customer    : pesan dibintangi customer
 *  - starred_admin       : pesan dibintangi admin
 *  - pinned              : pesan disematkan (satu per percakapan)
 *
 * Nama tabel diambil otomatis dari model ChatMessage, jadi tidak perlu diubah.
 */
return new class extends Migration
{
    private function tableName(): string
    {
        return (new \App\Models\ChatMessage())->getTable();
    }

    public function up(): void
    {
        $t = $this->tableName();

        Schema::table($t, function (Blueprint $table) use ($t) {
            if (! Schema::hasColumn($t, 'admin_id')) {
                $table->unsignedBigInteger('admin_id')->nullable();
            }
            if (! Schema::hasColumn($t, 'reaction_customer')) {
                $table->string('reaction_customer', 32)->nullable();
            }
            if (! Schema::hasColumn($t, 'reaction_admin')) {
                $table->string('reaction_admin', 32)->nullable();
            }
            if (! Schema::hasColumn($t, 'starred_customer')) {
                $table->boolean('starred_customer')->default(false);
            }
            if (! Schema::hasColumn($t, 'starred_admin')) {
                $table->boolean('starred_admin')->default(false);
            }
            if (! Schema::hasColumn($t, 'pinned')) {
                $table->boolean('pinned')->default(false);
            }
        });
    }

    public function down(): void
    {
        $t = $this->tableName();

        Schema::table($t, function (Blueprint $table) use ($t) {
            foreach (['admin_id', 'reaction_customer', 'reaction_admin', 'starred_customer', 'starred_admin', 'pinned'] as $col) {
                if (Schema::hasColumn($t, $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
