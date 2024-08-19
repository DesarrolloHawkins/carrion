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
        Schema::table('servicio_presupuesto', function (Blueprint $table) {
            $table->string('concepto')->nullable();
            $table->integer('visible')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicio_presupuesto', function (Blueprint $table) {
            $table->dropColumn('concepto');
            $table->dropColumn('visible');
        });
    }
};
