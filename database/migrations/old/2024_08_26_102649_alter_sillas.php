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
        //add clm id_palco
        Schema::table('sillas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_palco')->nullable();
            $table->foreign('id_palco')->references('id')->on('palcos');
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
