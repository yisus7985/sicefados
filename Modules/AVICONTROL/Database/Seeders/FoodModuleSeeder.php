<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\AVICONTROL\Entities\FoodConsumption;
use Modules\AVICONTROL\Entities\FoodConversion;
use Modules\AVICONTROL\Entities\FoodWaste;
use Modules\AVICONTROL\Entities\Galpon;
use Modules\AVICONTROL\Entities\InventoryProduct;

class FoodModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Crear galpones de prueba si no existen
        $galpones = Galpon::firstOrCreate(
            ['name' => 'Galpón A'],
            [
                'length' => 50.0,
                'width' => 10.0,
                'height' => 3.0,
                'capacity' => 5000,
                'description' => 'Galpón principal para ponedoras',
                'status' => 'active',
                'creation_date' => now()->subMonths(6)
            ]
        );

        $galpon2 = Galpon::firstOrCreate(
            ['name' => 'Galpón B'],
            [
                'length' => 45.0,
                'width' => 12.0,
                'height' => 3.2,
                'capacity' => 6000,
                'description' => 'Galpón secundario para ponedoras',
                'status' => 'active',
                'creation_date' => now()->subMonths(4)
            ]
        );

        // Crear productos de inventario si no existen
        $producto1 = InventoryProduct::firstOrCreate(
            ['name' => 'Concentrado Inicial'],
            [
                'description' => 'Alimento para pollitas de 0-8 semanas',
                'category' => 'alimentos',
                'unit_measure' => 'kg',
                'unit_price' => 1.20,
                'current_stock' => 5000,
                'minimum_stock' => 500,
                'status' => 'active',
                'code' => 'INV000001'
            ]
        );

        $producto2 = InventoryProduct::firstOrCreate(
            ['name' => 'Concentrado Crecimiento'],
            [
                'description' => 'Alimento para pollitas de 8-20 semanas',
                'category' => 'alimentos',
                'unit_measure' => 'kg',
                'unit_price' => 1.15,
                'current_stock' => 3000,
                'minimum_stock' => 300,
                'status' => 'active',
                'code' => 'INV000002'
            ]
        );

        $producto3 = InventoryProduct::firstOrCreate(
            ['name' => 'Concentrado Postura'],
            [
                'description' => 'Alimento para gallinas ponedoras',
                'category' => 'alimentos',
                'unit_measure' => 'kg',
                'unit_price' => 1.30,
                'current_stock' => 8000,
                'minimum_stock' => 800,
                'status' => 'active',
                'code' => 'INV000003'
            ]
        );

        // Crear registros de consumo de alimento
        for ($i = 1; $i <= 10; $i++) {
            FoodConsumption::firstOrCreate(
                [
                    'fecha_registro' => now()->subDays($i),
                    'galpon_id' => $galpones->id,
                    'producto_id' => $producto3->id
                ],
                [
                    'cantidad_kg' => rand(800, 1200),
                    'cantidad_bultos' => rand(16, 24),
                    'peso_por_bulto' => 50.0,
                    'consumo_promedio_por_ave' => rand(120, 150) / 1000, // g por ave
                    'numero_aves' => 4800,
                    'observaciones' => 'Consumo normal del día',
                    'responsable' => 'Juan Pérez',
                    'estado' => 'active'
                ]
            );
        }

        // Crear registros de conversión alimenticia
        FoodConversion::firstOrCreate(
            [
                'galpon_id' => $galpones->id,
                'fecha_inicio' => now()->subDays(30),
                'fecha_fin' => now()->subDays(1),
                'periodo_tipo' => 'mensual',
                'tipo_produccion' => 'huevo'
            ],
            [
                'total_alimento_consumido' => 35000,
                'total_producto_obtenido' => 140000, // huevos en kg
                'conversion_alimenticia' => 2.5,
                'observaciones' => 'Conversión mensual - período normal',
                'estado' => 'active'
            ]
        );

        FoodConversion::firstOrCreate(
            [
                'galpon_id' => $galpon2->id,
                'fecha_inicio' => now()->subDays(30),
                'fecha_fin' => now()->subDays(1),
                'periodo_tipo' => 'mensual',
                'tipo_produccion' => 'huevo'
            ],
            [
                'total_alimento_consumido' => 42000,
                'total_producto_obtenido' => 168000, // huevos en kg
                'conversion_alimenticia' => 2.4,
                'observaciones' => 'Conversión mensual - buen rendimiento',
                'estado' => 'active'
            ]
        );

        // Crear registros de mermas
        $causas = ['derrame', 'contaminacion', 'roedores', 'empaque_roto', 'humedad', 'plagas', 'vencimiento', 'manejo_incorrecto', 'otros'];
        
        for ($i = 1; $i <= 5; $i++) {
            FoodWaste::firstOrCreate(
                [
                    'fecha_registro' => now()->subDays($i * 3),
                    'galpon_id' => $galpones->id,
                    'producto_id' => $producto3->id
                ],
                [
                    'cantidad_perdida' => rand(50, 200),
                    'causa_merma' => $causas[array_rand($causas)],
                    'responsable' => 'María González',
                    'observaciones' => 'Merma registrada durante el proceso',
                    'costo_perdida' => rand(50, 200) * 1.30,
                    'estado' => 'active'
                ]
            );
        }

        // Crear más registros de mermas para el segundo galpón
        for ($i = 1; $i <= 3; $i++) {
            FoodWaste::firstOrCreate(
                [
                    'fecha_registro' => now()->subDays($i * 5),
                    'galpon_id' => $galpon2->id,
                    'producto_id' => $producto3->id
                ],
                [
                    'cantidad_perdida' => rand(30, 150),
                    'causa_merma' => $causas[array_rand($causas)],
                    'responsable' => 'Carlos López',
                    'observaciones' => 'Merma menor registrada',
                    'costo_perdida' => rand(30, 150) * 1.30,
                    'estado' => 'active'
                ]
            );
        }

        $this->command->info('Datos de prueba del módulo de alimentación creados exitosamente.');
    }
}
