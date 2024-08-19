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
        Schema::create('pistas', function (Blueprint $table) {
            $table->id();
            //nombre
            $table->string('nombre');
            //deporte
            $table->unsignedBigInteger('deporte');
            //tipo
            $table->unsignedBigInteger('tipo');
            //caracteristicas 
            $table->unsignedBigInteger('caracteristicas');
            //tamaño 
            $table->unsignedBigInteger('tamano');
            //online?
            $table->boolean('online');
            //disponible?
            $table->boolean('disponible');
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
        Schema::dropIfExists('pistas');
    }
};
