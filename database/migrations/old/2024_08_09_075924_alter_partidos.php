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
        //add column TorneosCategoriasid
        Schema::table('partidos', function (Blueprint $table) {
            $table->unsignedBigInteger('torneos_categorias_id')->nullable();
            $table->foreign('torneos_categorias_id')->references('id')->on('torneos_categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop column TorneosCategoriasid
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropForeign('torneos_torneos_categorias_id_foreign');
            $table->dropColumn('torneos_categorias_id');
        });
    }
};
