<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncar la tabla antes de insertar
        DB::table('avicontrol_productions')->truncate();

        $productions = [
            // Semana 43
            [
                'fecha' => '2025-03-17',
                'tipo' => 'A',
                'cantidad' => 360,
                'valor_unidad' => 427,
                'valor_total' => 153600,
                'destino' => 'Punto venta',
                'observaciones' => 'Semana 43',
                'firma_recibido' => 'Natalia Losada',
                'semana_produccion' => 'Semana 43',
                'firma_lider' => 'Carlos Mendoza',
                'estado' => 'activo',
            ],
            [
                'fecha' => '2025-03-17',
                'tipo' => 'AA',
                'cantidad' => 210,
                'valor_unidad' => 450,
                'valor_total' => 94500,
                'destino' => 'Punto venta',
                'observaciones' => 'Semana 43',
                'firma_recibido' => 'Natalia Losada',
                'semana_produccion' => 'Semana 43',
                'firma_lider' => 'Carlos Mendoza',
                'estado' => 'activo',
            ],
            [
                'fecha' => '2025-03-17',
                'tipo' => 'B',
                'cantidad' => 30,
                'valor_unidad' => 383,
                'valor_total' => 11500,
                'destino' => 'Punto venta',
                'observaciones' => 'Semana 43',
                'firma_recibido' => 'Natalia Losada',
                'semana_produccion' => 'Semana 43',
                'firma_lider' => 'Carlos Mendoza',
                'estado' => 'activo',
            ],
            // Semana 44
            [
                'fecha' => '2025-03-24',
                'tipo' => 'A',
                'cantidad' => 200,
                'valor_unidad' => 430,
                'valor_total' => 86000,
                'destino' => 'Punto venta',
                'observaciones' => 'Semana 44',
                'firma_recibido' => 'María González',
                'semana_produccion' => 'Semana 44',
                'firma_lider' => 'Carlos Mendoza',
                'estado' => 'activo',
            ],
            [
                'fecha' => '2025-03-24',
                'tipo' => 'AA',
                'cantidad' => 180,
                'valor_unidad' => 455,
                'valor_total' => 81900,
                'destino' => 'Punto venta',
                'observaciones' => 'Semana 44',
                'firma_recibido' => 'María González',
                'semana_produccion' => 'Semana 44',
                'firma_lider' => 'Carlos Mendoza',
                'estado' => 'activo',
            ],
        ];

        foreach ($productions as $production) {
            DB::table('avicontrol_productions')->insert($production);
        }

        $this->command->info('Producción de huevos sembrada exitosamente.');
    }
}