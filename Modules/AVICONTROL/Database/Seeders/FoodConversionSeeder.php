<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\AVICONTROL\Entities\FoodConversion;
use Modules\AVICONTROL\Entities\PoultryFacility;

class FoodConversionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Obtener galpones existentes
        $galpones = PoultryFacility::all();
        
        if ($galpones->isEmpty()) {
            $this->command->info('No hay galpones disponibles. Creando galpón de prueba...');
            
            // Crear un galpón de prueba si no existe
            $galpon = PoultryFacility::create([
                'name' => 'Galpón Principal',
                'code' => 'GP001',
                'capacity' => 5000,
                'dimensions' => '100m x 20m',
                'location' => 'Zona A',
                'status' => 'active'
            ]);
            
            $galpones = collect([$galpon]);
        }

        // Crear registros de conversión alimenticia de prueba
        $conversiones = [
            [
                'galpon_id' => $galpones->first()->id,
                'fecha_inicio' => now()->subDays(30),
                'fecha_fin' => now()->subDays(23),
                'periodo_tipo' => 'semanal',
                'total_alimento_consumido' => 2500.50,
                'total_producto_obtenido' => 1200.25,
                'conversion_alimenticia' => 2.08,
                'tipo_produccion' => 'huevo',
                'observaciones' => 'Primera semana de producción - conversión estable',
                'estado' => 'active'
            ],
            [
                'galpon_id' => $galpones->first()->id,
                'fecha_inicio' => now()->subDays(22),
                'fecha_fin' => now()->subDays(15),
                'periodo_tipo' => 'semanal',
                'total_alimento_consumido' => 2650.75,
                'total_producto_obtenido' => 1280.50,
                'conversion_alimenticia' => 2.07,
                'tipo_produccion' => 'huevo',
                'observaciones' => 'Segunda semana - mejora en conversión',
                'estado' => 'active'
            ],
            [
                'galpon_id' => $galpones->first()->id,
                'fecha_inicio' => now()->subDays(14),
                'fecha_fin' => now()->subDays(7),
                'periodo_tipo' => 'semanal',
                'total_alimento_consumido' => 2580.25,
                'total_producto_obtenido' => 1250.75,
                'conversion_alimenticia' => 2.06,
                'tipo_produccion' => 'huevo',
                'observaciones' => 'Tercera semana - conversión óptima',
                'estado' => 'active'
            ],
            [
                'galpon_id' => $galpones->first()->id,
                'fecha_inicio' => now()->subDays(6),
                'fecha_fin' => now(),
                'periodo_tipo' => 'semanal',
                'total_alimento_consumido' => 2700.00,
                'total_producto_obtenido' => 1300.00,
                'conversion_alimenticia' => 2.08,
                'tipo_produccion' => 'huevo',
                'observaciones' => 'Semana actual - producción consistente',
                'estado' => 'active'
            ],
            [
                'galpon_id' => $galpones->first()->id,
                'fecha_inicio' => now()->subDays(60),
                'fecha_fin' => now()->subDays(31),
                'periodo_tipo' => 'mensual',
                'total_alimento_consumido' => 10200.50,
                'total_producto_obtenido' => 4950.25,
                'conversion_alimenticia' => 2.06,
                'tipo_produccion' => 'huevo',
                'observaciones' => 'Mes anterior - excelente conversión mensual',
                'estado' => 'active'
            ]
        ];

        foreach ($conversiones as $conversionData) {
            FoodConversion::create($conversionData);
        }

        // Si hay más de un galpón, crear datos para el segundo
        if ($galpones->count() > 1) {
            $segundoGalpon = $galpones->get(1);
            
            $conversionesGalpon2 = [
                [
                    'galpon_id' => $segundoGalpon->id,
                    'fecha_inicio' => now()->subDays(30),
                    'fecha_fin' => now()->subDays(23),
                    'periodo_tipo' => 'semanal',
                    'total_alimento_consumido' => 1800.25,
                    'total_producto_obtenido' => 850.50,
                    'conversion_alimenticia' => 2.12,
                    'tipo_produccion' => 'carne',
                    'observaciones' => 'Producción de carne - conversión aceptable',
                    'estado' => 'active'
                ],
                [
                    'galpon_id' => $segundoGalpon->id,
                    'fecha_inicio' => now()->subDays(22),
                    'fecha_fin' => now()->subDays(15),
                    'periodo_tipo' => 'semanal',
                    'total_alimento_consumido' => 1750.75,
                    'total_producto_obtenido' => 820.25,
                    'conversion_alimenticia' => 2.13,
                    'tipo_produccion' => 'carne',
                    'observaciones' => 'Segunda semana carne - conversión estable',
                    'estado' => 'active'
                ]
            ];

            foreach ($conversionesGalpon2 as $conversionData) {
                FoodConversion::create($conversionData);
            }
        }

        $this->command->info('Datos de conversión alimenticia creados exitosamente!');
        $this->command->info('Total de registros creados: ' . FoodConversion::count());
    }
}
