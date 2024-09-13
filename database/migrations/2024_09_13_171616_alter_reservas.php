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
        //add colum to table reservas
        Schema::table('reservas', function (Blueprint $table) {
            //type text transaction
            $table->text('transaction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop column transaction
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn('transaction');
        });
    }
};
