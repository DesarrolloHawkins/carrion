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
        Schema::create('membresias_ventajas', function (Blueprint $table) {
            $table->id();
            //membresia_id
            $table->unsignedBigInteger('membresia_id');
            $table->foreign('membresia_id')->references('id')->on('membresias');
            //zona_id
            $table->unsignedBigInteger('zona_id');
            //tipo descuento
            $table->string('tipo_descuento');
            //descuento
            $table->decimal('descuento', 8, 2);
            //boolean lunes
            $table->boolean('lunes')->default(0);
            //boolean martes
            $table->boolean('martes')->default(0);
            //boolean miercoles
            $table->boolean('miercoles')->default(0);
            //boolean jueves
            $table->boolean('jueves')->default(0);
            //boolean viernes
            $table->boolean('viernes')->default(0);
            //boolean sabado
            $table->boolean('sabado')->default(0);
            //boolean domingo
            $table->boolean('domingo')->default(0);
            //hora inicio
            $table->time('hora_inicio');    
            //hora fin
            $table->time('hora_fin');
            //antelacion_reserva
            $table->integer('antelacion_reserva');            
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
        Schema::dropIfExists('membresias_ventajas');
    }
};
