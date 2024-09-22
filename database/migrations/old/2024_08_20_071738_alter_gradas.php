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
        //change column numero to string on gradas

        Schema::table('gradas', function (Blueprint $table) {
            $table->string('numero')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //change to integer
        Schema::table('gradas', function (Blueprint $table) {
            $table->integer('numero')->change();
        });
    }
};
