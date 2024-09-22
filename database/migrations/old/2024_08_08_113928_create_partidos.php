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
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('torneo_id');
            $table->unsignedBigInteger('equipo1_id')->nullable();
            $table->unsignedBigInteger('equipo2_id')->nullable();
            //dia
            $table->date('dia');
            //hora_inicio
            $table->time('hora_inicio');
            //hora_fin
            $table->time('hora_fin');
            //pista_id
            $table->unsignedBigInteger('pista_id');
            //resultado nullable
            $table->string('resultado')->nullable();
            $table->timestamps();

            $table->foreign('torneo_id')->references('id')->on('torneos');
            $table->foreign('equipo1_id')->references('id')->on('torneos_duos');
            $table->foreign('equipo2_id')->references('id')->on('torneos_duos');
            $table->foreign('pista_id')->references('id')->on('pistas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partidos');
    }
};
