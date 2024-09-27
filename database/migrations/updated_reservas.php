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
        Schema::table('reservas', function (Blueprint $table) {
            // Añadir la columna order_id para relacionar las reservas con la tabla orders
            $table->unsignedBigInteger('order_id')->nullable()->after('id_cliente'); // Relaciona la reserva con un pedido
            // Crear la clave foránea que vincula order_id con la tabla orders
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

};
