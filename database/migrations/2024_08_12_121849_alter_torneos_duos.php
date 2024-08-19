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
        //add column estado to torneos_duos
        Schema::table('torneos_duos', function (Blueprint $table) {
            //boolean estado
            $table->boolean('estado')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop
        Schema::table('torneos_duos', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
