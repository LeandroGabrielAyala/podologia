<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     */
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {

            $table->id();

            $table->enum('tipo', [
                'ingreso',
                'egreso'
            ]);

            /**
             * Relación con conceptos
             */
            $table->foreignId('concepto_id')
                ->constrained('conceptos')
                ->cascadeOnDelete();

            $table->decimal('monto', 12, 2);

            $table->date('fecha');

            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};