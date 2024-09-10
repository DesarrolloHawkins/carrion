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
        //add columns to clientes

        Schema::table('clientes', function (Blueprint $table) {
            // Verificar si la columna 'apellidos' no existe
            if (!Schema::hasColumn('clientes', 'apellidos')) {
                $table->string('apellidos')->nullable();
            }
            // Verificar si la columna 'nombre' no existe
            if (!Schema::hasColumn('clientes', 'nombre')) {
                $table->string('nombre');
            }
            // Verificar si la columna 'direccion' no existe
            if (!Schema::hasColumn('clientes', 'direccion')) {
                $table->string('direccion')->nullable();
            }
            // Verificar si la columna 'codigo_postal' no existe
            if (!Schema::hasColumn('clientes', 'codigo_postal')) {
                $table->string('codigo_postal')->nullable();
            }
            // Verificar si la columna 'poblacion' no existe
            if (!Schema::hasColumn('clientes', 'poblacion')) {
                $table->string('poblacion')->nullable();
            }
            // Verificar si la columna 'provincia' no existe
            if (!Schema::hasColumn('clientes', 'provincia')) {
                $table->string('provincia')->nullable();
            }
            // Verificar si la columna 'fijo' no existe
            if (!Schema::hasColumn('clientes', 'fijo')) {
                $table->string('fijo')->nullable();
            }
            // Verificar si la columna 'movil' no existe
            if (!Schema::hasColumn('clientes', 'movil')) {
                $table->string('movil')->nullable();
            }
            // Verificar si la columna 'DNI' no existe
            if (!Schema::hasColumn('clientes', 'DNI')) {
                $table->string('DNI');
            }
            // Verificar si la columna 'email' no existe
            if (!Schema::hasColumn('clientes', 'email')) {
                $table->string('email');
            }
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
