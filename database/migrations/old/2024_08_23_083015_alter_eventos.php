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
        //add nombre, fecha_inicio, fecha_fin
        Schema::table('eventos', function (Blueprint $table) {
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop column nombre, fecha_inicio, fecha_fin
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('nombre');
            $table->dropColumn('fecha_inicio');
            $table->dropColumn('fecha_fin');
            
        });
    }
};
