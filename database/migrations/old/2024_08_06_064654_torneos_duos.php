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
        //add table torneos_duos
        Schema::create('torneos_duos', function (Blueprint $table) {
            $table->id();

            //inscripcion id
            $table->unsignedBigInteger('inscripcion_id');
            $table->foreign('inscripcion_id')->references('id')->on('torneos_categorias_inscripciones');
            
            //incrispcion id 2
            $table->unsignedBigInteger('inscripcion_id_2')->nullable();
            $table->foreign('inscripcion_id_2')->references('id')->on('torneos_categorias_inscripciones');

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
        //
    }
};
