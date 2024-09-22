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
        //add colunn comentario to partidos
        Schema::table('partidos', function (Blueprint $table) {
            $table->string('comentario')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop column comentario
        Schema::table('partidos', function (Blueprint $table) {
            $table->dropColumn('comentario');
        });
    }
};
