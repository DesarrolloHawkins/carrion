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
        Schema::create('status-app', function (Blueprint $table) {
            $table->id(); // ID único del pedido
            $table->tinyInteger('estado')->nullable(); // El número de orden del pago en Redsys o cualquier otra pasarela
            $table->timestamps();
            $table->softDeletes();

            // Relación con la tabla 'clientes' (si tienes un modelo de cliente)
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
