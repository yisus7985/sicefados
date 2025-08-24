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
            // Agregar columnas faltantes
            if (!Schema::hasColumn('avicontrol_productions', 'tipo_produccion')) {
                $table->enum('tipo_produccion', ['huevos', 'carne'])->after('tipo')->default('huevos');
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'galpon_id')) {
                $table->unsignedBigInteger('galpon_id')->after('tipo_produccion')->nullable();
                $table->foreign('galpon_id')->references('id')->on('avicontrol_poultry_facilities')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'mortalidad_aves')) {
                $table->integer('mortalidad_aves')->after('cantidad')->default(0);
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'peso_promedio')) {
                $table->decimal('peso_promedio', 8, 2)->after('mortalidad_aves')->nullable();
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'peso_total')) {
                $table->decimal('peso_total', 10, 2)->after('peso_promedio')->nullable();
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'huevos_rotos')) {
                $table->integer('huevos_rotos')->after('peso_total')->default(0);
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'huevos_sucios')) {
                $table->integer('huevos_sucios')->after('huevos_rotos')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avicontrol_productions', function (Blueprint $table) {
            // Eliminar columnas agregadas
            $table->dropForeign(['galpon_id']);
            $table->dropColumn([
                'tipo_produccion',
                'galpon_id',
                'mortalidad_aves',
                'peso_promedio',
                'peso_total',
                'huevos_rotos',
                'huevos_sucios'
            ]);
        });
    }
};
