<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQualityColumnsToAvicontrolProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avicontrol_productions', function (Blueprint $table) {
            // Solo añadir las columnas que no existen
            if (!Schema::hasColumn('avicontrol_productions', 'huevos_rotos')) {
                $table->integer('huevos_rotos')->default(0)->after('mortalidad_aves');
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'huevos_sucios')) {
                $table->integer('huevos_sucios')->default(0)->after('huevos_rotos');
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'peso_promedio')) {
                $table->decimal('peso_promedio', 8, 2)->nullable()->after('huevos_sucios');
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'peso_total')) {
                $table->decimal('peso_total', 10, 2)->nullable()->after('peso_promedio');
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'fecha_sacrificio')) {
                $table->date('fecha_sacrificio')->nullable()->after('peso_total');
            }
            
            if (!Schema::hasColumn('avicontrol_productions', 'responsable_sacrificio')) {
                $table->string('responsable_sacrificio')->nullable()->after('fecha_sacrificio');
            }
            
            // Añadir columna galpon_id si no existe
            if (!Schema::hasColumn('avicontrol_productions', 'galpon_id')) {
                $table->unsignedBigInteger('galpon_id')->nullable()->after('tipo');
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
        Schema::table('avicontrol_productions', function (Blueprint $table) {
            // Eliminar las columnas añadidas
            $table->dropColumn([
                'mortalidad_aves',
                'huevos_rotos', 
                'huevos_sucios',
                'peso_promedio',
                'peso_total',
                'fecha_sacrificio',
                'responsable_sacrificio'
            ]);
        });
    }
}
