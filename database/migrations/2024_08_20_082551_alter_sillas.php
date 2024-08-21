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
        //change fila to string on sillas

        Schema::table('sillas', function (Blueprint $table) {
            $table->string('fila')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //change to integer
        Schema::table('sillas', function (Blueprint $table) {
            $table->integer('fila')->change();
        });
    }
};
