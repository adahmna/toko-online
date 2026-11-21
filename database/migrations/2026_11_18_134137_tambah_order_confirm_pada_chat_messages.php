<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahOrderConfirmPadaChatMessages extends Migration
{
    public function up()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('pesanan_id')->nullable();
            $table->timestamp('address_confirmed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['pesanan_id', 'address_confirmed_at']);
        });
    }
}