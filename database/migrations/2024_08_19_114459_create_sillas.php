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
        Schema::create('sillas', function (Blueprint $table) {
            $table->id();
            //numero
            $table->integer('numero');
            //fila
            $table->integer('fila');
            //grada id
            $table->bigInteger('id_grada')->unsigned();
            $table->foreign('id_grada')->references('id')->on('gradas')->nullable();
              //zona id
            $table->bigInteger('id_zona')->unsigned();
            $table->foreign('id_zona')->references('id')->on('zonas')->nullable();
            
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
        Schema::dropIfExists('sillas');
    }
};
