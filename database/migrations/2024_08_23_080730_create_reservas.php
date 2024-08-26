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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            //add id_silla
            $table->unsignedBigInteger('id_silla');
            $table->foreign('id_silla')->references('id')->on('sillas');
            //id cliente
            $table->unsignedBigInteger('id_cliente');
            $table->foreign('id_cliente')->references('id')->on('clientes');
            //fecha
            $table->date('fecha')->nullable();
            //año
            $table->year('año')->nullable();
            //eventoid
            $table->unsignedBigInteger('id_evento');
            $table->foreign('id_evento')->references('id')->on('eventos');  

            //precio 
            $table->decimal('precio', 8, 2)->nullable();

            //estado
            $table->enum('estado', ['reservada', 'pagada', 'cancelada']);
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
        Schema::dropIfExists('reservas');
    }
};
