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
        Schema::create('torneos', function (Blueprint $table) {
            $table->id();
            //inscripcion que puede ser individual o por equipos
            $table->string('inscripcion');
            //nombre del torneo
            $table->string('nombre');
            //descripcion del torneo en tipo text
            $table->text('descripcion');
            //imagen
            $table->string('imagen');
            //normativa pdf
            $table->string('normativa');
            //metalico
            $table->string('precio');
            //metalico socio
            $table->string('precio_socio');
            //metalico pronto pago
            $table->string('precio_pronto_pago');
            //metalico socio pronto pago
            $table->string('precio_socio_pronto_pago');
            //condiciones
            $table->text('condiciones');
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
        Schema::dropIfExists('torneos');
    }
};
