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
        //add partido_id
        Schema::table('reservas', function (Blueprint $table) {
            $table->unsignedBigInteger('partido_id')->nullable();
            $table->foreign('partido_id')->references('id')->on('partidos');

            //add softDeletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop partido_id
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropForeign('reservas_partido_id_foreign');
            $table->dropColumn('partido_id');

            //drop softDeletes
            $table->dropSoftDeletes();
        });
    }
};
