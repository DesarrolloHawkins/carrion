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
        Schema::create('partido_resultados_sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partido_resultado_id');
            $table->integer('set_number')->default(1);
            $table->integer('duo_1_score')->default(0);
            $table->integer('duo_2_score')->default(0);
            //duo 1 id
            $table->unsignedBigInteger('duo_1_id');
            //duo 2 id
            $table->unsignedBigInteger('duo_2_id');



            //foreing key to partido_resultado_id
            $table->foreign('partido_resultado_id')->references('id')->on('partido_resultados');

            //foreing key to duo_1_id
            $table->foreign('duo_1_id')->references('id')->on('torneos_duos');

            //foreing key to duo_2_id
            $table->foreign('duo_2_id')->references('id')->on('torneos_duos');

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
        Schema::dropIfExists('partido_resultados_sets');
    }
};
