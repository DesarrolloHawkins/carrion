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
        //drop column diaFinal
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('diaFinal');
            //drop diaEvento
            $table->dropColumn('diaEvento');
            //drop eventoMontaje, eventoLocalidad, eventoLugar, eventoTelefono, eventoParentesco, eventoAdulto, eventoContacto, eventoNiños, eventoProtagonista, eventoNombre
            $table->dropColumn('eventoMontaje');
            $table->dropColumn('eventoLocalidad');
            $table->dropColumn('eventoLugar');
            $table->dropColumn('eventoTelefono');
            $table->dropColumn('eventoParentesco');
            $table->dropColumn('eventoAdulto');
            $table->dropColumn('eventoContacto');
            $table->dropColumn('eventoNiños');
            $table->dropColumn('eventoProtagonista');
            $table->dropColumn('eventoNombre');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
