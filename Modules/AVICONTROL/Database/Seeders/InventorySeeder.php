<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\AVICONTROL\Entities\InventoryProduct;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Crear productos de prueba
        $products = [
            [
                'name' => 'Alimento Concentrado Premium',
                'code' => 'ACP001',
                'category' => 'alimentos',
                'description' => 'Alimento concentrado de alta calidad para aves ponedoras',
                'unit_measure' => 'kg',
                'current_stock' => 500,
                'minimum_stock' => 100,
                'unit_price' => 2.50,
                'supplier' => 'Proveedor A',
                'expiration_date' => now()->addMonths(6),
                'status' => 'active'
            ],
            [
                'name' => 'Vacuna Newcastle',
                'code' => 'VNC001',
                'category' => 'biologicos',
                'description' => 'Vacuna contra la enfermedad de Newcastle',
                'unit_measure' => 'dosis',
                'current_stock' => 1000,
                'minimum_stock' => 200,
                'unit_price' => 0.85,
                'supplier' => 'Proveedor B',
                'expiration_date' => now()->addMonths(12),
                'status' => 'active'
            ],
            [
                'name' => 'Desinfectante Avícola',
                'code' => 'DA001',
                'category' => 'desinfectantes',
                'description' => 'Desinfectante especializado para instalaciones avícolas',
                'unit_measure' => 'litros',
                'current_stock' => 50,
                'minimum_stock' => 10,
                'unit_price' => 15.00,
                'supplier' => 'Proveedor C',
                'expiration_date' => now()->addMonths(24),
                'status' => 'active'
            ],
            [
                'name' => 'Cubetas de Huevos',
                'code' => 'CH001',
                'category' => 'embalajes',
                'description' => 'Cubetas plásticas para recolección de huevos',
                'unit_measure' => 'unidades',
                'current_stock' => 200,
                'minimum_stock' => 50,
                'unit_price' => 2.00,
                'supplier' => 'Proveedor D',
                'expiration_date' => null,
                'status' => 'active'
            ],
            [
                'name' => 'Antibiótico Avícola',
                'code' => 'AA001',
                'category' => 'medicamentos',
                'description' => 'Antibiótico para tratamiento de infecciones avícolas',
                'unit_measure' => 'gramos',
                'current_stock' => 25,
                'minimum_stock' => 5,
                'unit_price' => 45.00,
                'supplier' => 'Proveedor E',
                'expiration_date' => now()->addMonths(18),
                'status' => 'active'
            ]
        ];

        foreach ($products as $productData) {
            InventoryProduct::create($productData);
        }

        $this->command->info('Productos de inventario creados exitosamente!');
    }
}
