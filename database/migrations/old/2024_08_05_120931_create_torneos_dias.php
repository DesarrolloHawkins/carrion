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
        Schema::create('torneos_dias', function (Blueprint $table) {
            $table->id();
            //dia
            $table->string('dia');
            //hora inicio
            $table->string('hora_inicio');
            //hora fin
            $table->string('hora_fin');
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
        Schema::dropIfExists('torneos_dias');
    }
};
