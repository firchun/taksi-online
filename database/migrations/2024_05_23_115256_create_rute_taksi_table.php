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
        Schema::create('rute_taksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_taksi');
            $table->foreignId('id_rute');
            $table->timestamps();

            $table->foreign('id_taksi')->references('id')->on('taksi');
            $table->foreign('id_rute')->references('id')->on('rute');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rute_taksi');
    }
};
