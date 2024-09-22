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
        //add column torneo_id to reservas
        Schema::table('reservas', function (Blueprint $table) {
            $table->unsignedBigInteger('torneo_id')->nullable();
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
        //delete column torneo_id from reservas
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropForeign(['torneo_id']);
            $table->dropColumn('torneo_id');
        });
    }
};
