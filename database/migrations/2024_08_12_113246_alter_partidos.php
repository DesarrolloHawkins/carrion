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
        //add column finalizado to partidos
        Schema::table('partidos', function (Blueprint $table) {
            $table->boolean('finalizado')->default(false);
            //add column bloqueado
            $table->boolean('bloqueado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop column finalizado
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropColumn('finalizado');
            //drop column bloqueado
            $table->dropColumn('bloqueado');
        });
    }
};
