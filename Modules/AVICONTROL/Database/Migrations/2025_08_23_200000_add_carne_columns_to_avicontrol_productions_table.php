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
        Schema::table('avicontrol_productions', function (Blueprint $table) {
            // Agregar columnas específicas para carne
            if (!Schema::hasColumn('avicontrol_productions', 'fecha_sacrificio')) {
                $table->date('fecha_sacrificio')->nullable()->after('peso_total');
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'responsable_sacrificio')) {
                $table->string('responsable_sacrificio', 100)->nullable()->after('fecha_sacrificio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avicontrol_productions', function (Blueprint $table) {
            // Remover columnas específicas de carne
            if (Schema::hasColumn('avicontrol_productions', 'fecha_sacrificio')) {
                $table->dropColumn('fecha_sacrificio');
            }
            
            if (Schema::hasColumn('avicontrol_productions', 'responsable_sacrificio')) {
                $table->dropColumn('responsable_sacrificio');
            }
        });
    }
};
