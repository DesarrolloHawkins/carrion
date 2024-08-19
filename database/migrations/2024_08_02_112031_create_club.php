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
        Schema::create('club', function (Blueprint $table) {
            $table->id();
            //nombre
            $table->string('nombre');
            //numero de pistas
            $table->integer('numero_pistas');
            //pagina web
            $table->string('pagina_web');
            //facebook
            $table->string('facebook');
            //twitter
            $table->string('twitter');
            //descripcion tipo text
            $table->text('descripcion');
            //nombre_contacto
            $table->string('nombre_contacto');
            //email_contacto
            $table->string('email_contacto');
            //telefono
            $table->string('telefono');
            //direccion
            $table->string('direccion');
            //pais
            $table->string('pais');
            //ciudad
            $table->string('ciudad');
            //poblacion
            $table->string('poblacion');
            //codigo_postal
            $table->string('codigo_postal');
            //apertura por cada dia de la semana, hora de inicio y fin
            $table->time('lunes_apertura');
            $table->time('lunes_cierre');
            $table->time('martes_apertura');
            $table->time('martes_cierre');
            $table->time('miercoles_apertura');
            $table->time('miercoles_cierre');
            $table->time('jueves_apertura');
            $table->time('jueves_cierre');
            $table->time('viernes_apertura');
            $table->time('viernes_cierre');
            $table->time('sabado_apertura');
            $table->time('sabado_cierre');
            $table->time('domingo_apertura');
            $table->time('domingo_cierre');
            //extracto
            $table->text('extracto');
            //limite reserva
            $table->integer('limite_reserva');
            //tiempo cancelacion
            $table->integer('tiempo_cancelacion');
            //maximo reservas dia
            $table->integer('maximo_reservas_dia');
            //maximo reservas activas
            $table->integer('maximo_reservas_activas');
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
        Schema::dropIfExists('club');
    }
};
