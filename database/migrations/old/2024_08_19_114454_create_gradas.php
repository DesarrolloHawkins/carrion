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
        Schema::create('gradas', function (Blueprint $table) {
            $table->id();
            //numero
            $table->integer('numero');
            //zona id
            $table->bigInteger('id_zona')->unsigned();
            $table->foreign('id_zona')->references('id')->on('zonas')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gradas');
    }
};
