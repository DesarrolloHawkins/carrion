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
        //add column to reservas metodo_pago que puede ser tarjeta, efectivo, transferencia
        Schema::table('reservas', function (Blueprint $table) {
            $table->string('metodo_pago')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //add column to reservas metodo_pago que puede ser tarjeta, efectivo, transferencia
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('metodo_pago');
        });
    }
};
