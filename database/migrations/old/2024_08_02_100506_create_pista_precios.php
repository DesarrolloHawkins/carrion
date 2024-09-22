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
        Schema::create('pista_precios', function (Blueprint $table) {
            $table->id();
            //pista_id
            $table->unsignedBigInteger('pista_id');
            $table->foreign('pista_id')->references('id')->on('pistas');
            //regla
            $table->string('regla');
            //duracion
            $table->integer('duracion');
            //precio
            $table->decimal('precio', 8, 2);
            //hora inicio
            $table->time('hora_inicio');
            //hora fin
            $table->time('hora_fin');
            //lunes boolean
            $table->boolean('lunes');
            //martes boolean
            $table->boolean('martes');
            //miercoles boolean
            $table->boolean('miercoles');
            //jueves boolean
            $table->boolean('jueves');
            //viernes boolean
            $table->boolean('viernes');
            //sabado boolean
            $table->boolean('sabado');
            //domingo boolean
            $table->boolean('domingo');
            //temporal boolean
            $table->boolean('temporal');
            //nombre_temporal
            $table->string('nombre_temporal')->nullable();
            //fecha_inicio
            $table->date('fecha_inicio')->nullable();
            //fecha_fin
            $table->date('fecha_fin')->nullable();

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
        Schema::dropIfExists('pista_precios');
    }
};
