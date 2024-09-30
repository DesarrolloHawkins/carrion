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
        Schema::create('deudas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->string('concepto')->nullable();
            $table->decimal('cantidad', 10, 2)->nullable();
            $table->date('fecha')->nullable();
            $table->boolean('pagada')->default(false);
            $table->dateTime('fecha_pago')->nullable(); // Fecha en que se pagó
            $table->timestamps();
            $table->softDeletes();
        
            // Clave foránea para asociar con clientes
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
