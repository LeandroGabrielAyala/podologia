<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla conceptos
     */
    public function up(): void
    {
        Schema::create('conceptos', function (Blueprint $table) {

            $table->id();

            /**
             * Nombre del concepto
             * Ej: Pacientes, Alquiler, etc.
             */
            $table->string('nombre');

            /**
             * Tipo del concepto
             */
            $table->enum('tipo', [
                'ingreso',
                'egreso'
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conceptos');
    }
};