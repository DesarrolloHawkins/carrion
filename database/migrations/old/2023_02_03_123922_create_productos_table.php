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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_categoria')->nullable();
            $table->integer('id_tipo_iva')->nullable();
            $table->string('cod_producto')->unique();
            $table->string('nombre')->nullable();
            $table->string('descripcion')->nullable();
            $table->decimal('precio')->nullable();
            $table->integer('stock')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
