<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AVICONTROL\Entities\Bird;
use Modules\AVICONTROL\Entities\InventoryProduct;
use Carbon\Carbon;

class AlertTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear aves con datos que generen alertas
        $this->createBirdsWithAlerts();
        
        // Crear productos de inventario con alertas
        $this->createInventoryWithAlerts();
    }

    private function createBirdsWithAlerts()
    {
        $timestamp = substr(time(), -6); // Solo los últimos 6 dígitos
        
        // Ave con mortalidad alta
        Bird::create([
            'poultry_facility_id' => 1,
            'batch_code' => "ALERT-LOTE-{$timestamp}-001",
            'batch_name' => 'Lote Ponedoras Alertas 001',
            'bird_type' => 'laying_hens',
            'quantity' => 950,
            'initial_quantity' => 1000,
            'entry_date' => Carbon::now()->subWeeks(25),
            'age_weeks' => 25,
            'breed' => 'Lohmann Brown',
            'average_weight' => 1.8,
            'status' => 'active',
            'mortality_rate' => 0.08, // 8% - alta mortalidad
            'feed_consumption' => 120,
            'laying_rate' => 65, // Baja tasa de postura
            'purchase_price' => 15.00,
            'supplier' => 'Granja Modelo'
        ]);

        // Ave con consumo anormal de alimento
        Bird::create([
            'poultry_facility_id' => 1,
            'batch_code' => "ALERT-LOTE-{$timestamp}-002",
            'batch_name' => 'Lote Ponedoras Alertas 002',
            'bird_type' => 'laying_hens',
            'quantity' => 980,
            'initial_quantity' => 1000,
            'entry_date' => Carbon::now()->subWeeks(30),
            'age_weeks' => 30,
            'breed' => 'Hy-Line',
            'average_weight' => 1.9,
            'status' => 'active',
            'mortality_rate' => 0.02, // 2% - normal
            'feed_consumption' => 180, // Alto consumo
            'laying_rate' => 85, // Normal
            'purchase_price' => 16.00,
            'supplier' => 'Granja Modelo'
        ]);

        // Ave con bajo consumo de alimento
        Bird::create([
            'poultry_facility_id' => 1,
            'batch_code' => "ALERT-LOTE-{$timestamp}-003",
            'batch_name' => 'Lote Ponedoras Alertas 003',
            'bird_type' => 'laying_hens',
            'quantity' => 990,
            'initial_quantity' => 1000,
            'entry_date' => Carbon::now()->subWeeks(35),
            'age_weeks' => 35,
            'breed' => 'Isa Brown',
            'average_weight' => 1.85,
            'status' => 'active',
            'mortality_rate' => 0.01, // 1% - normal
            'feed_consumption' => 60, // Bajo consumo
            'laying_rate' => 80, // Normal
            'purchase_price' => 15.50,
            'supplier' => 'Granja Modelo'
        ]);
    }

    private function createInventoryWithAlerts()
    {
        $timestamp = substr(time(), -6); // Solo los últimos 6 dígitos
        
        // Producto con stock bajo
        InventoryProduct::create([
            'name' => 'Vacuna Newcastle',
            'description' => 'Vacuna contra la enfermedad de Newcastle',
            'category' => 'biologicos',
            'current_stock' => 5,
            'minimum_stock' => 10,
            'unit_price' => 25.00,
            'unit_measure' => 'ml',
            'supplier' => 'Laboratorio Veterinario',
            'expiration_date' => Carbon::now()->addMonths(6),
            'status' => 'active',
            'code' => "VAC-{$timestamp}"
        ]);

        // Producto próximo a vencer
        InventoryProduct::create([
            'name' => 'Antibiótico Amoxicilina',
            'description' => 'Antibiótico de amplio espectro',
            'category' => 'medicamentos',
            'current_stock' => 15,
            'minimum_stock' => 5,
            'unit_price' => 45.00,
            'unit_measure' => 'g',
            'supplier' => 'Laboratorio Veterinario',
            'expiration_date' => Carbon::now()->addDays(15), // Próximo a vencer
            'status' => 'active',
            'code' => "ANT-{$timestamp}"
        ]);

        // Producto vencido
        InventoryProduct::create([
            'name' => 'Vitaminas AD3E',
            'description' => 'Complejo vitamínico para aves',
            'category' => 'medicamentos',
            'current_stock' => 8,
            'minimum_stock' => 3,
            'unit_price' => 30.00,
            'unit_measure' => 'ml',
            'supplier' => 'Laboratorio Veterinario',
            'expiration_date' => Carbon::now()->subDays(5), // Vencido
            'status' => 'active',
            'code' => "VIT-{$timestamp}"
        ]);

        // Producto con stock crítico
        InventoryProduct::create([
            'name' => 'Alimento Concentrado',
            'description' => 'Alimento balanceado para ponedoras',
            'category' => 'alimentos',
            'current_stock' => 2,
            'minimum_stock' => 20,
            'unit_price' => 120.00,
            'unit_measure' => 'kg',
            'supplier' => 'Molino Central',
            'expiration_date' => Carbon::now()->addMonths(3),
            'status' => 'active',
            'code' => "ALI-{$timestamp}"
        ]);
    }
} 