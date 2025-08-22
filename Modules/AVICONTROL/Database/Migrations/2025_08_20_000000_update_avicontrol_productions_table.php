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
            // Agregar nuevas columnas
            $table->date('fecha')->nullable()->after('id');
            $table->enum('tipo', ['A', 'AA', 'B', 'C', 'D'])->nullable()->after('fecha');
            $table->integer('cantidad')->nullable()->after('tipo');
            $table->decimal('valor_unidad', 10, 2)->nullable()->after('cantidad');
            $table->decimal('valor_total', 12, 2)->nullable()->after('valor_unidad');
            $table->string('destino')->nullable()->after('valor_total');
            $table->string('observaciones')->nullable()->after('destino');
            $table->string('firma_recibido')->nullable()->after('observaciones');
            $table->string('semana_produccion')->nullable()->after('firma_recibido');
            $table->string('firma_lider')->nullable()->after('semana_produccion');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('firma_lider');
        });

        // Migrar datos existentes si los hay
        $this->migrateExistingData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avicontrol_productions', function (Blueprint $table) {
            // Eliminar nuevas columnas
            $table->dropColumn([
                'fecha', 'tipo', 'cantidad', 'valor_unidad', 'valor_total',
                'destino', 'observaciones', 'firma_recibido', 'semana_produccion',
                'firma_lider', 'estado'
            ]);
        });
    }

    /**
     * Migrar datos existentes a la nueva estructura
     */
    private function migrateExistingData()
    {
        // Solo si hay datos existentes
        if (DB::table('avicontrol_productions')->count() > 0) {
            DB::table('avicontrol_productions')->get()->each(function ($production) {
                // Calcular total de huevos
                $totalEggs = ($production->egg_type_a ?? 0) + 
                            ($production->egg_type_a ?? 0) + 
                            ($production->egg_type_b ?? 0) + 
                            ($production->egg_type_c ?? 0) + 
                            ($production->egg_type_d ?? 0);

                // Determinar tipo principal (el que tenga más huevos)
                $types = [
                    'A' => $production->egg_type_a ?? 0,
                    'AA' => $production->egg_type_aa ?? 0,
                    'B' => $production->egg_type_b ?? 0,
                    'C' => $production->egg_type_c ?? 0,
                    'D' => $production->egg_type_d ?? 0
                ];
                $mainType = array_keys($types, max($types))[0] ?? 'A';

                // Actualizar registro
                DB::table('avicontrol_productions')
                    ->where('id', $production->id)
                    ->update([
                        'fecha' => $production->production_date,
                        'tipo' => $mainType,
                        'cantidad' => $totalEggs,
                        'valor_unidad' => $production->unit_value ?? 0,
                        'valor_total' => $production->total_value ?? 0,
                        'destino' => $production->destination ?? 'Punto venta',
                        'observaciones' => $production->observations,
                        'firma_recibido' => $production->received_by ?? 'Usuario',
                        'semana_produccion' => $production->week_number,
                        'firma_lider' => 'Líder',
                        'estado' => $production->status === 'confirmed' ? 'activo' : 'inactivo'
                    ]);
            });
        }
    }
};