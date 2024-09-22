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
        //new table torneos_inscripcion_disponibilidad
        Schema::create('torneos_inscripcion_disponibilidad', function (Blueprint $table) {
            $table->id();
            //torneo id
            $table->unsignedBigInteger('torneo_id');
            $table->foreign('torneo_id')->references('id')->on('torneos');
            //inscripcion id
            $table->unsignedBigInteger('inscripcion_id');
            $table->foreign('inscripcion_id')->references('id')->on('torneos_categorias_inscripciones');
            
            //fecha de no disponibilidad
            $table->date('fecha_no_disponible');

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
        //delete table torneos_inscripcion_disponibilidad
        Schema::dropIfExists('torneos_inscripcion_disponibilidad');
    }
};
