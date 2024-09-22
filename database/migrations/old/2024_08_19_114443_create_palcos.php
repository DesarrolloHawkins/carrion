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
        Schema::create('palcos', function (Blueprint $table) {
            $table->id();
            //num sillas
            $table->integer('num_sillas');
            //zona id 
            $table->bigInteger('id_zona')->unsigned()->nullable();
            $table->foreign('id_zona')->references('id')->on('zonas');
            //sector id
            $table->bigInteger('id_sector')->unsigned()->nullable();
            $table->foreign('id_sector')->references('id')->on('sectores');
            //ext_prop
            $table->text('ext_prop')->nullable();
            //numero
            $table->integer('numero');
            //coordenada_x
            $table->string('coordenada_x')->nullable();
            //coordenada_y
            $table->string('coordenada_y')->nullable();
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
        Schema::dropIfExists('palcos');
    }
};
