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
        //add columns to clientes

        Schema::table('clientes', function (Blueprint $table) {
            //apellidos
            $table->string('apellidos')->nullable();
            //nombre
            $table->string('nombre');
            //direccion
            $table->string('direccion')->nullable();
            //codigo postal
            $table->string('codigo_postal')->nullable();
            //poblacion
            $table->string('poblacion')->nullable();
            //provincia
            $table->string('provincia')->nullable();
            //fijo
            $table->string('fijo')->nullable();
            //movil
            $table->string('movil')->nullable();
            //DNI
            $table->string('DNI');
            //email
            $table->string('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
