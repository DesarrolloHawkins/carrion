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
        //add column DNI to clientes
        Schema::table('clientes', function (Blueprint $table) {
            //add dni unique and nullable
            $table->string('DNI')->unique()->nullable(); //add column DNI to clientes
            //add column telefono unique and nullable
            $table->string('telefono')->unique()->nullable()->after('DNI');
            //add nickname unique and nullable
            $table->string('nickName')->unique()->nullable()->after('telefono');
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
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('DNI');
            $table->dropColumn('telefono');
            $table->dropColumn('nickName');
        });
    }
};
