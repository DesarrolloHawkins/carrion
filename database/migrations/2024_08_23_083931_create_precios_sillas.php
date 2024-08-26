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
        Schema::create('precios_sillas', function (Blueprint $table) {
            $table->id();
            
            // Tipo de asiento (palco, grada, etc.)
            $table->enum('tipo_asiento', ['palco', 'grada']);

            // Condición adicional (como la fila para gradas)
            $table->integer('fila_inicio')->nullable(); // Para gradas: fila de inicio
            $table->integer('fila_fin')->nullable(); // Para gradas: fila de fin

            // Precio
            $table->decimal('precio', 8, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('precios_sillas');
    }
};
