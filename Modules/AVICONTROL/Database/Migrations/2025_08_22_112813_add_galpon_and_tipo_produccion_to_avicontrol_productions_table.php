<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGalponAndTipoProduccionToAvicontrolProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avicontrol_productions', function (Blueprint $table) {
            // Agregar campo para tipo de producción (huevos o carne)
            $table->enum('tipo_produccion', ['huevos', 'carne'])->default('huevos')->after('tipo');
            
            // Agregar campo para galpón
            $table->unsignedBigInteger('galpon_id')->nullable()->after('tipo_produccion');
            $table->foreign('galpon_id')->references('id')->on('avicontrol_poultry_facilities')->onDelete('set null');
            
            // Agregar campos específicos para carne
            $table->decimal('peso_promedio', 8, 2)->nullable()->after('cantidad'); // Peso promedio en kg
            $table->decimal('peso_total', 10, 2)->nullable()->after('peso_promedio'); // Peso total en kg
            
            // Agregar campos específicos para huevos
            $table->integer('huevos_rotos')->default(0)->after('peso_total'); // Huevos rotos
            $table->integer('huevos_sucios')->default(0)->after('huevos_rotos'); // Huevos sucios
            
            // Índices para optimizar consultas
            $table->index(['tipo_produccion', 'fecha']);
            $table->index(['galpon_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avicontrol_productions', function (Blueprint $table) {
            $table->dropForeign(['galpon_id']);
            $table->dropIndex(['tipo_produccion', 'fecha']);
            $table->dropIndex(['galpon_id', 'fecha']);
            $table->dropColumn([
                'tipo_produccion',
                'galpon_id',
                'peso_promedio',
                'peso_total',
                'huevos_rotos',
                'huevos_sucios'
            ]);
        });
    }
}
