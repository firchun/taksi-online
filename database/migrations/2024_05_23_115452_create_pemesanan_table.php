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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user');
            $table->foreignId('id_taksi');
            $table->foreignId('id_rute_asal');
            $table->foreignId('id_rute_tujuan');
            $table->integer('jumlah_penumpang');
            $table->boolean('barang_bawaan')->default(0);
            $table->enum('besar_bawaan', ['Besar', 'Sedang', 'Kecil'])->nullable();
            $table->boolean('pesanan_selesai')->default(0);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_taksi')->references('id')->on('taksi');
            $table->foreign('id_rute_asal')->references('id')->on('rute');
            $table->foreign('id_rute_tujuan')->references('id')->on('rute');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanan');
    }
};
