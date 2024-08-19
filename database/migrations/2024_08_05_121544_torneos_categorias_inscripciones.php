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
        //new table torneos_categorias_inscripciones
        Schema::create('torneos_categorias_inscripciones', function (Blueprint $table) {
            $table->id();
            //torneo_categoria_id
            $table->unsignedBigInteger('torneo_categoria_id');
            $table->foreign('torneo_categoria_id')->references('id')->on('torneos_categorias');
            //jugador_id
            $table->unsignedBigInteger('jugador_id')->nullable();
            $table->foreign('jugador_id')->references('id')->on('clientes');
            //fecha inscripcion
            $table->date('fecha_inscripcion');

            //email
            $table->string('email');
            //telefono
            $table->string('telefono')->nullable();
            //DNI
            $table->string('DNI');
            //nickName
            $table->string('nickName')->nullable();
            //nombre
            $table->string('nombre');
            //apellidos
            $table->string('apellidos');
            //ciudad
            $table->string('ciudad')->nullable();
            //genero
            $table->string('genero');
            //categoria
            $table->string('categoria');
            //comentario text
            $table->text('comentario')->nullable();
            //pagado boolean
            $table->boolean('pagado')->default(false);
            //total_precio decimal
            $table->decimal('total_precio', 8, 2);
            
            //pendiente decimal
            $table->decimal('pendiente', 8, 2);
            //soft delete
            $table->softDeletes();

           
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
        //drop table torneos_categorias_inscripciones
        Schema::dropIfExists('torneos_categorias_inscripciones');
    }
};
