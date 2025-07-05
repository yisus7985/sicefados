<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AVICONTROL\Entities\InventoryProduct;
use Modules\AVICONTROL\Entities\InventoryMovement;
use Illuminate\Support\Facades\DB;

class InventoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample inventory products
        $products = [
            [
                'name' => 'Concentrado para Pollos de Engorde',
                'category' => 'alimentos',
                'description' => 'Alimento balanceado para pollos de engorde, rico en proteínas y nutrientes',
                'unit_measure' => 'kg',
                'unit_price' => 2.50,
                'supplier' => 'Alimentos SENA',
                'expiration_date' => now()->addMonths(6),
                'minimum_stock' => 100,
                'current_stock' => 150,
                'status' => 'active'
            ],
            [
                'name' => 'Concentrado para Gallinas Ponedoras',
                'category' => 'alimentos',
                'description' => 'Alimento especializado para gallinas ponedoras con alto contenido de calcio',
                'unit_measure' => 'kg',
                'unit_price' => 3.20,
                'supplier' => 'Alimentos SENA',
                'expiration_date' => now()->addMonths(4),
                'minimum_stock' => 80,
                'current_stock' => 60,
                'status' => 'active'
            ],
            [
                'name' => 'Vacuna Newcastle',
                'category' => 'biologicos',
                'description' => 'Vacuna contra la enfermedad de Newcastle para aves',
                'unit_measure' => 'viales',
                'unit_price' => 15.00,
                'supplier' => 'Laboratorios Veterinarios',
                'expiration_date' => now()->addMonths(2),
                'minimum_stock' => 10,
                'current_stock' => 8,
                'status' => 'active'
            ],
            [
                'name' => 'Antibiótico Amoxicilina',
                'category' => 'medicamentos',
                'description' => 'Antibiótico de amplio espectro para tratamiento de infecciones',
                'unit_measure' => 'unidades',
                'unit_price' => 25.00,
                'supplier' => 'Farmacia Veterinaria',
                'expiration_date' => now()->addMonths(3),
                'minimum_stock' => 5,
                'current_stock' => 3,
                'status' => 'active'
            ],
            [
                'name' => 'Desinfectante Clorhexidina',
                'category' => 'desinfectantes',
                'description' => 'Desinfectante para limpieza de instalaciones avícolas',
                'unit_measure' => 'l',
                'unit_price' => 45.00,
                'supplier' => 'Productos de Limpieza SENA',
                'expiration_date' => now()->addMonths(12),
                'minimum_stock' => 20,
                'current_stock' => 25,
                'status' => 'active'
            ],
            [
                'name' => 'Cubetas para Huevos',
                'category' => 'embalajes',
                'description' => 'Cubetas plásticas para almacenamiento y transporte de huevos',
                'unit_measure' => 'unidades',
                'unit_price' => 8.50,
                'supplier' => 'Embalajes SENA',
                'expiration_date' => null,
                'minimum_stock' => 50,
                'current_stock' => 45,
                'status' => 'active'
            ],
            [
                'name' => 'Cajas de Cartón',
                'category' => 'embalajes',
                'description' => 'Cajas de cartón para empaque de productos avícolas',
                'unit_measure' => 'unidades',
                'unit_price' => 2.00,
                'supplier' => 'Embalajes SENA',
                'expiration_date' => null,
                'minimum_stock' => 100,
                'current_stock' => 120,
                'status' => 'active'
            ],
            [
                'name' => 'Termómetro Digital',
                'category' => 'equipos',
                'description' => 'Termómetro digital para monitoreo de temperatura en galpones',
                'unit_measure' => 'unidades',
                'unit_price' => 35.00,
                'supplier' => 'Equipos SENA',
                'expiration_date' => null,
                'minimum_stock' => 3,
                'current_stock' => 2,
                'status' => 'active'
            ],
            [
                'name' => 'Vitaminas AD3E',
                'category' => 'medicamentos',
                'description' => 'Suplemento vitamínico para aves con vitaminas A, D3 y E',
                'unit_measure' => 'ml',
                'unit_price' => 12.00,
                'supplier' => 'Farmacia Veterinaria',
                'expiration_date' => now()->addMonths(1),
                'minimum_stock' => 15,
                'current_stock' => 5,
                'status' => 'active'
            ],
            [
                'name' => 'Piedra Caliza',
                'category' => 'alimentos',
                'description' => 'Suplemento de calcio para gallinas ponedoras',
                'unit_measure' => 'kg',
                'unit_price' => 1.80,
                'supplier' => 'Minerales SENA',
                'expiration_date' => now()->addMonths(24),
                'minimum_stock' => 50,
                'current_stock' => 30,
                'status' => 'active'
            ]
        ];

        foreach ($products as $productData) {
            $product = InventoryProduct::create([
                'name' => $productData['name'],
                'code' => InventoryProduct::generateCode(),
                'category' => $productData['category'],
                'description' => $productData['description'],
                'unit_measure' => $productData['unit_measure'],
                'unit_price' => $productData['unit_price'],
                'supplier' => $productData['supplier'],
                'expiration_date' => $productData['expiration_date'],
                'minimum_stock' => $productData['minimum_stock'],
                'current_stock' => $productData['current_stock'],
                'status' => $productData['status']
            ]);

            // Create initial movement for each product
            if ($productData['current_stock'] > 0) {
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => InventoryMovement::TYPE_ENTRY,
                    'quantity' => $productData['current_stock'],
                    'unit_price' => $productData['unit_price'],
                    'reference' => InventoryMovement::REFERENCE_PURCHASE,
                    'notes' => 'Stock inicial del producto',
                    'movement_date' => now()->subDays(rand(1, 30)),
                    'user_id' => \App\Models\User::first()->id ?? 1
                ]);
            }

            // Create some sample movements for variety
            $this->createSampleMovements($product);
        }
    }

    private function createSampleMovements($product)
    {
        $movementTypes = [
            InventoryMovement::TYPE_ENTRY => [
                InventoryMovement::REFERENCE_PURCHASE,
                InventoryMovement::REFERENCE_PRODUCTION
            ],
            InventoryMovement::TYPE_EXIT => [
                InventoryMovement::REFERENCE_CONSUMPTION,
                InventoryMovement::REFERENCE_SALE,
                InventoryMovement::REFERENCE_DISCARD
            ]
        ];

        // Create 2-5 random movements per product
        $numMovements = rand(2, 5);
        
        for ($i = 0; $i < $numMovements; $i++) {
            $movementType = array_rand($movementTypes);
            $reference = $movementTypes[$movementType][array_rand($movementTypes[$movementType])];
            
            $quantity = rand(1, 20);
            $unitPrice = $product->unit_price * (rand(80, 120) / 100); // ±20% variation
            
            InventoryMovement::create([
                'product_id' => $product->id,
                'movement_type' => $movementType,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'reference' => $reference,
                'notes' => $this->getRandomNote($reference),
                'movement_date' => now()->subDays(rand(1, 90)),
                                    'user_id' => \App\Models\User::first()->id ?? 1
            ]);
        }
    }

    private function getRandomNote($reference)
    {
        $notes = [
            InventoryMovement::REFERENCE_PURCHASE => [
                'Compra regular de proveedor',
                'Reabastecimiento de inventario',
                'Compra por volumen'
            ],
            InventoryMovement::REFERENCE_PRODUCTION => [
                'Producción interna',
                'Elaboración propia'
            ],
            InventoryMovement::REFERENCE_CONSUMPTION => [
                'Consumo diario',
                'Uso en producción',
                'Consumo regular'
            ],
            InventoryMovement::REFERENCE_SALE => [
                'Venta a cliente',
                'Venta al por mayor',
                'Venta directa'
            ],
            InventoryMovement::REFERENCE_DISCARD => [
                'Producto vencido',
                'Daño en almacenamiento',
                'Descarte por calidad'
            ]
        ];

        return $notes[$reference][array_rand($notes[$reference])];
    }
} 