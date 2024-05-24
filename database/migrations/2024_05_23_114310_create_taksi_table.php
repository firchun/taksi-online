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
        Schema::create('taksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user');
            $table->string('plat_nomor')->unique();
            $table->string('merek', 50);
            $table->string('warna', 50);
            $table->string('foto_depan');
            $table->string('foto_samping');
            $table->string('foto_sim');
            $table->enum('status', ['Tersedia', 'Full']);
            $table->boolean('aktif')->default(0);
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taksi');
    }
};
