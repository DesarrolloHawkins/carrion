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
        //creatte table partido_resultados
        Schema::create('partido_resultados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partido_id');
            $table->unsignedBigInteger('torneo_id');
            //duo 1 id
            $table->unsignedBigInteger('duo_1_id');
            //duo 2 id
            $table->unsignedBigInteger('duo_2_id');

            //set winner    
            $table->unsignedBigInteger('winner_id')->nullable();

            //partidos ganados duo 1
            $table->integer('duo_1_wins')->default(0);

            //partidos ganados duo 2
            $table->integer('duo_2_wins')->default(0);



            //foreing key to partido_id
            $table->foreign('partido_id')->references('id')->on('partidos');

            //foreing key to torneo_id
            $table->foreign('torneo_id')->references('id')->on('torneos');

            //foreing key to duo_1_id
            $table->foreign('duo_1_id')->references('id')->on('torneos_duos');

            //foreing key to duo_2_id
            $table->foreign('duo_2_id')->references('id')->on('torneos_duos');

            //foreing key to winner_id
            $table->foreign('winner_id')->references('id')->on('torneos_duos');

            
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
        //drop table partido_resultados
        Schema::dropIfExists('partido_resultados');

    }
};
