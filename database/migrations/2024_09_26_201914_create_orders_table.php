<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // ID único del pedido
            $table->unsignedBigInteger('cliente_id'); // Relaciona el pedido con el cliente
            $table->string('payment_order_id')->nullable(); // El número de orden del pago en Redsys o cualquier otra pasarela
            $table->decimal('total', 8, 2)->nullable(); // Monto total del pedido
            $table->string('status')->default('pending'); // Estado del pedido (pending, paid, failed)
            $table->timestamps();

            // Relación con la tabla 'clientes' (si tienes un modelo de cliente)
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
