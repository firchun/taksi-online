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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'verified')) {
                $table->dropColumn('verified');
            }
        });

        // Tambahkan kolom 'verified' ke tabel 'taksi'
        Schema::table('taksi', function (Blueprint $table) {
            $table->boolean('verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taksi', function (Blueprint $table) {
            //
        });
    }
};
