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
        //add foreign to membresia_id
        Schema::table('socios', function (Blueprint $table) {
            $table->foreign('membresia_id')->references('id')->on('membresias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop foreign to membresia_id
        Schema::table('socios', function (Blueprint $table) {
            $table->dropForeign(['membresia_id']);
        });
    }
};
