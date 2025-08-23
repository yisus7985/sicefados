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
        Schema::table('avicontrol_poultry_facilities', function (Blueprint $table) {
            // Agregar campo para tipo de galpón
            $table->enum('tipo', ['gallinas_ponedoras', 'pollos_engorde'])->default('gallinas_ponedoras')->after('name');
            
            // Índice para optimizar consultas
            $table->index(['tipo', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avicontrol_poultry_facilities', function (Blueprint $table) {
            $table->dropIndex(['tipo', 'status']);
            $table->dropColumn('tipo');
        });
    }
};
