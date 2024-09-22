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
        //add default value to column metodo_pago in reservas
        Schema::table('reservas', function (Blueprint $table) {
            //add default value to column metodo_pago in reservas but metodo_pago already exists
            $table->string('metodo_pago')->default('tarjeta')->change();
            //add column isInvitado boolean
            $table->boolean('isInvitado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop 
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('isInvitado');
        });
    }
};
