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
        //add column grupo to torneos_duos
        Schema::table('torneos_duos', function (Blueprint $table) {
            $table->string('grupo')->nullable();
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
            $table->dropColumn('grupo');
        });
    }
};
