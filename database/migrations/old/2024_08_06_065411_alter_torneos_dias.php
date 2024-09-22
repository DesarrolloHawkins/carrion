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
        //add torneo_id to torneos_dias
        Schema::table('torneos_dias', function (Blueprint $table) {
            $table->unsignedBigInteger('torneo_id');
            $table->foreign('torneo_id')->references('id')->on('torneos');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop torneo_id from torneos_dias
        Schema::table('torneos_dias', function (Blueprint $table) {
            $table->dropForeign(['torneo_id']);
            $table->dropColumn('torneo_id');
        });
    }
};
