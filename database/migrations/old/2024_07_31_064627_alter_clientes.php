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
        //relacion de categoria id con categorias_jugadores
        Schema::table('clientes', function (Blueprint $table) {
            $table->foreign('categoria_id')->references('id')->on('categorias_jugadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //relacion de categoria id con categorias_jugadores
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('clientes_categoria_id_foreign');
        });
    }
};
