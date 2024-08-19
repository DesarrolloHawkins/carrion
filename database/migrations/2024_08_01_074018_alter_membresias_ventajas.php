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
        //add foreign key to zona_id
        Schema::table('membresias_ventajas', function (Blueprint $table) {
            $table->foreign('zona_id')->references('id')->on('zonas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop foreign key to zona_id
        Schema::table('membresias_ventajas', function (Blueprint $table) {
            $table->dropForeign(['zona_id']);
        });
    }
};
