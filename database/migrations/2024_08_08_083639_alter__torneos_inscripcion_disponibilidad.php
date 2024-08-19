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
        //change foreign key of inscripcion_id to other table, table of TorneosDuos
        Schema::table('torneos_inscripcion_disponibilidad', function (Blueprint $table) {
            //delete foreign key torneos_inscripcion_disponibilidad_inscripcion_id_foreign
            $table->dropForeign('torneos_inscripcion_disponibilidad_inscripcion_id_foreign');
            //add foreign key inscripcion_id to table torneos_duos
            $table->foreign('inscripcion_id')->references('id')->on('torneos_duos');

            //hora no disponible add
            $table->time('hora_no_disponible')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop
        Schema::table('torneos_inscripcion_disponibilidad', function (Blueprint $table) {
            $table->dropForeign('torneos_inscripcion_disponibilidad_inscripcion_id_foreign');
            $table->dropColumn('hora_no_disponible');
        });
    }
};
