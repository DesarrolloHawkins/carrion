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
        //add column procesanso to reservas 

        Schema::table('reservas', function (Blueprint $table) {
            $table->boolean('procesando')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop column procesanso to reservas
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('procesando');
        });
    }
};
