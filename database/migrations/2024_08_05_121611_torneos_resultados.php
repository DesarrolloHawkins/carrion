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
        //new table torneos_resultados
        Schema::create('torneos_resultados', function (Blueprint $table) {
            $table->id();
            //torneo id
            $table->unsignedBigInteger('torneo_id');
            $table->foreign('torneo_id')->references('id')->on('torneos');
            //jugador id
            $table->unsignedBigInteger('jugador_id')->nullable();
            $table->foreign('jugador_id')->references('id')->on('clientes');
            //inscripcion id
            $table->unsignedBigInteger('inscripcion_id');
            $table->foreign('inscripcion_id')->references('id')->on('torneos_categorias_inscripciones');

            //puntos
            $table->integer('puntos');

            //resultado
            $table->string('resultado');

            //posicion final
            $table->integer('posicion_final');


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
        //delete table torneos_resultados
        Schema::dropIfExists('torneos_resultados');
    }
};
