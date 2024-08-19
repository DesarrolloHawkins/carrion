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
        //nueva tabla torneos_categorias
        Schema::create('torneos_categorias', function (Blueprint $table) {
            $table->id();
           //torneo_id
            $table->unsignedBigInteger('torneo_id');
            $table->foreign('torneo_id')->references('id')->on('torneos');
            //categoria_id
            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('categorias_jugadores');
            //maximo jugadores por defecto 16
            $table->integer('max_jugadores')->default(16);

            //formato de juego
            $table->string('formato_juego');
            
            //incripciones abiertas o cerradas
            $table->boolean('inscripciones_abiertas')->default(true);

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
        //Delete table torneos_categorias
        Schema::dropIfExists('torneos_categorias');
    }
};
