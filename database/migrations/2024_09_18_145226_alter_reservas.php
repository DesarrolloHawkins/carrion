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
        //add column isCRM to table reservas nullable
        Schema::table('reservas', function (Blueprint $table) {
            $table->boolean('isCRM')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop column isCRM from table reservas
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('isCRM');
        });
    }
};
