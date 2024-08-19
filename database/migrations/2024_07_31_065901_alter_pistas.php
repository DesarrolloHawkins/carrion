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
        //add foreign key to deporte
        Schema::table('pistas', function (Blueprint $table) {
            $table->foreign('deporte')->references('id')->on('deporte');
            //tipo
            $table->foreign('tipo')->references('id')->on('pista_tipo');
            //caracteristicas
            $table->foreign('caracteristicas')->references('id')->on('pista_caracteristicas');
            //tamano
            $table->foreign('tamano')->references('id')->on('pista_tamano');
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
