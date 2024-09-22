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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            //email
            $table->string('email1')->nullable();
            //nombre
            $table->string('nombre')->nullable();
            //apellido
            $table->string('apellido')->nullable();
            //telefono
            $table->string('tlf1')->nullable();
            //direccion
            $table->string('calle')->nullable();
            //genero
            $table->string('genero')->nullable();
            //fecha de nacimiento
            $table->date('fecha_nacimiento')->nullable();
            //pais 
            $table->string('pais')->nullable();
            //ciudad
            $table->string('ciudad')->nullable();
            //categoria
            $table->unsignedBigInteger('categoria_id')->nullable();
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
        Schema::dropIfExists('clientes');
    }
};
