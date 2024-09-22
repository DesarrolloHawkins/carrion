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
            //pista id
            $table->unsignedBigInteger('pista_id');
            $table->foreign('pista_id')->references('id')->on('pistas');
            //cliente id
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes');
            //dia
            $table->date('dia');
            //hora_inicio
            $table->time('hora_inicio');
            //hora_fin
            $table->time('hora_fin');
            //precio
            $table->decimal('precio', 8, 2)->nullable();
            //tipo de pago
            $table->enum('tipo_pago', ['unico', 'dividido']);
            //nombre del jugador
            $table->string('nombre_jugador');
            //nota
            $table->text('nota')->nullable();
            //tipo de reserva si es recurrente, clase o normal
            $table->enum('tipo_reserva', ['normal', 'clase', 'recurrente']);

            //fecha inicio de la reserva recurrente
            $table->date('fecha_inicio_recurrente')->nullable();
            //fecha fin de la reserva recurrente
            $table->date('fecha_fin_recurrente')->nullable();
            //dias de la semana de la reserva recurrente
            //lunes boolean
            $table->boolean('lunes')->default(false);
            //martes boolean
            $table->boolean('martes')->default(false);
            //miercoles boolean
            $table->boolean('miercoles')->default(false);
            //jueves boolean
            $table->boolean('jueves')->default(false);
            //viernes boolean
            $table->boolean('viernes')->default(false);
            //sabado boolean
            $table->boolean('sabado')->default(false);
            //domingo boolean
            $table->boolean('domingo')->default(false);
            //repetir cada x semana
            $table->integer('repetir_cada')->nullable();
            //monitor
            $table->unsignedBigInteger('monitor_id')->nullable();
            $table->foreign('monitor_id')->references('id')->on('monitores');
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
