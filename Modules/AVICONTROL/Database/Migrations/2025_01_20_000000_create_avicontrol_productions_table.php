<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('avicontrol_productions', function (Blueprint $table) {
            $table->id();
            $table->date('fecha'); // FECHA - Fecha de recolección
            $table->enum('tipo', ['A', 'AA', 'B', 'C', 'D']); // TIPO - Tipo de huevo
            $table->integer('cantidad'); // CANTIDAD - Número de huevos
            $table->decimal('valor_unidad', 10, 2); // VALOR UNIDAD - Precio por unidad
            $table->decimal('valor_total', 12, 2); // VALOR TOTAL - Valor total calculado
            $table->string('destino')->default('Punto venta'); // DESTINO - Destino de los huevos
            $table->string('observaciones')->nullable(); // OBSERVACIONES - Semana de producción
            $table->string('firma_recibido'); // FIRMA RECIBIDO - Quien recibe
            $table->string('semana_produccion')->nullable(); // SEMANA DE PRODUCCIÓN
            $table->string('firma_lider')->nullable(); // FIRMA LÍDER - Firma del líder
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();
            $table->softDeletes();

            // Índices para optimizar consultas
            $table->index(['fecha', 'tipo']);
            $table->index(['semana_produccion', 'fecha']);
            $table->index(['estado', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avicontrol_productions');
    }
};