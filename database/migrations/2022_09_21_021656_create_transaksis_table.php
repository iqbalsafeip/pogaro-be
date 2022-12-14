<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('barber_id');
            $table->string('servis_id');
            $table->string('pengguna_id');
            $table->string('status');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('waktu');
            $table->string('tanggal');
            $table->string('ongkir');
            $table->string("detail_alamat");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
};
