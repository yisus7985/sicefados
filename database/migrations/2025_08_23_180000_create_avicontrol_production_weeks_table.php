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
        Schema::create('avicontrol_production_weeks', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['activa', 'inactiva'])->default('activa');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['estado', 'fecha_inicio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avicontrol_production_weeks');
    }
};
