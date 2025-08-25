<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Modules\AVICONTROL\Entities\Production;
use Modules\AVICONTROL\Entities\Bird;
use Modules\AVICONTROL\Entities\ProductionCost;
use Modules\AVICONTROL\Entities\FoodConsumption;
use Modules\AVICONTROL\Entities\InventoryProduct;
use Carbon\Carbon;
use DB;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->command->info('🚀 Iniciando creación de datos de prueba AVICONTROL...');
        
        // Limpiar datos existentes para evitar duplicados
        $this->cleanExistingData();
        
        // Crear datos en orden de dependencias
        $this->createPoultryFacilities();
        $this->createBirds();
        $this->createInventoryProducts();
        $this->createFoodConsumption();
        $this->createProductionData();
        $this->createProductionCosts();
        
        $this->command->info('✅ Datos de prueba creados exitosamente!');
        $this->showSummary();
    }

    /**
     * Limpiar datos existentes
     */
    private function cleanExistingData()
    {
        $this->command->info('🧹 Limpiando datos existentes...');
        
        // Desactivar verificación de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Limpiar tablas en orden inverso de dependencias
        DB::table('avicontrol_food_consumption')->truncate();
        DB::table('avicontrol_production_costs')->truncate();
        DB::table('avicontrol_productions')->truncate();
        DB::table('avicontrol_birds')->truncate();
        DB::table('avicontrol_inventory_products')->truncate();
        DB::table('avicontrol_poultry_facilities')->truncate();
        
        // Reactivar verificación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Crear 10 galpones de prueba
     */
    private function createPoultryFacilities()
    {
        $this->command->info('🏠 Creando 10 galpones...');
        
        $galpones = [
            [
                'name' => 'Galpón Norte A',
                'tipo' => 'gallinas_ponedoras',
                'length' => 50.0,
                'width' => 12.0,
                'height' => 3.5,
                'capacity' => 2000,
                'description' => 'Galpón principal para gallinas ponedoras, equipado con sistema de ventilación automática',
                'status' => 'active',
                'creation_date' => Carbon::now()->subDays(180)
            ],
            [
                'name' => 'Galpón Norte B',
                'tipo' => 'gallinas_ponedoras',
                'length' => 45.0,
                'width' => 10.0,
                'height' => 3.2,
                'capacity' => 1800,
                'description' => 'Galpón secundario para gallinas ponedoras con sistema de recolección automatizado',
                'status' => 'active',
                'creation_date' => Carbon::now()->subDays(175)
            ],
            [
                'name' => 'Galpón Sur A',
                'tipo' => 'pollos_engorde',
                'length' => 60.0,
                'width' => 15.0,
                'height' => 4.0,
                'capacity' => 3000,
                'description' => 'Galpón para pollos de engorde con tecnología de climatización avanzada',
                'status' => 'active',
                'creation_date' => Carbon::now()->subDays(170)
            ],
            [
                'name' => 'Galpón Sur B',
                'tipo' => 'pollos_engorde',
                'length' => 55.0,
                'width' => 14.0,
                'height' => 3.8,
                'capacity' => 2800,
                'description' => 'Galpón moderno para pollos de engorde con sistema de alimentación automático',
                'status' => 'active',
                'creation_date' => Carbon::now()->subDays(165)
            ],
            [
                'name' => 'Galpón Este',
                'tipo' => 'gallinas_ponedoras',
                'length' => 40.0,
                'width' => 8.0,
                'height' => 3.0,
                'capacity' => 1500,
                'description' => 'Galpón experimental para nuevas razas de gallinas ponedoras',
                'status' => 'active',
                'creation_date' => Carbon::now()->subDays(160)
            ],
            [
                'name' => 'Galpón Oeste',
                'tipo' => 'pollos_engorde',
                'length' => 48.0,
                'width' => 12.0,
                'height' => 3.6,
                'capacity' => 2400,
                'description' => 'Galpón con tecnología IoT para monitoreo en tiempo real',
                'status' => 'active',
                'creation_date' => Carbon::now()->subDays(155)
            ],
            [
                'name' => 'Galpón Central 1',
                'tipo' => 'gallinas_ponedoras',
                'length' => 52.0,
                'width' => 13.0,
                'height' => 3.7,
                'capacity' => 2200,
                'description' => 'Galpón central con capacidad ampliada y sistema de iluminación LED',
                'status' => 'active',
                'creation_date' => Carbon::now()->subDays(150)
            ],
            [
                'name' => 'Galpón Central 2',
                'tipo' => 'pollos_engorde',
                'length' => 58.0,
                'width' => 16.0,
                'height' => 4.2,
                'capacity' => 3200,
                'description' => 'Galpón de alta capacidad para producción intensiva de pollos',
                'status' => 'active',
                'creation_date' => Carbon::now()->subDays(145)
            ],
            [
                'name' => 'Galpón Experimental',
                'tipo' => 'gallinas_ponedoras',
                'length' => 35.0,
                'width' => 10.0,
                'height' => 3.0,
                'capacity' => 1200,
                'description' => 'Galpón para pruebas de nuevas tecnologías y métodos de crianza',
                'status' => 'active',
                'creation_date' => Carbon::now()->subDays(140)
            ],
            [
                'name' => 'Galpón Reserva',
                'tipo' => 'pollos_engorde',
                'length' => 42.0,
                'width' => 11.0,
                'height' => 3.4,
                'capacity' => 2000,
                'description' => 'Galpón de reserva para expansión y emergencias',
                'status' => 'active',
                'creation_date' => Carbon::now()->subDays(135)
            ]
        ];

        foreach ($galpones as $galpon) {
            PoultryFacility::create($galpon);
        }
        
        $this->command->info('✅ 10 galpones creados exitosamente');
    }

    /**
     * Crear datos de aves para cada galpón
     */
    private function createBirds()
    {
        $this->command->info('🐔 Creando datos de aves...');
        
        $galpones = PoultryFacility::all();
        $razas = [
            'gallinas_ponedoras' => ['Leghorn', 'Rhode Island Red', 'New Hampshire', 'Plymouth Rock', 'Sussex'],
            'pollos_engorde' => ['Cobb 500', 'Ross 308', 'Cornish Cross', 'Australorp']
        ];

        foreach ($galpones as $galpon) {
            $tipoAve = $galpon->tipo === 'gallinas_ponedoras' ? 'laying_hens' : 'broilers';
            $razasDisponibles = $razas[$galpon->tipo];
            
            // Crear 2-3 lotes por galpón
            $numLotes = rand(2, 3);
            
            for ($i = 1; $i <= $numLotes; $i++) {
                $cantidadInicial = rand(800, $galpon->capacity);
                $cantidadActual = $cantidadInicial - rand(0, (int)($cantidadInicial * 0.05)); // Máximo 5% mortalidad
                
                Bird::create([
                    'poultry_facility_id' => $galpon->id,
                    'batch_code' => 'LOTE-' . str_pad($galpon->id, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'bird_type' => $tipoAve,
                    'quantity' => $cantidadActual,
                    'initial_quantity' => $cantidadInicial,
                    'entry_date' => Carbon::now()->subDays(rand(30, 120)),
                    'age_weeks' => rand(4, 24),
                    'breed' => $razasDisponibles[array_rand($razasDisponibles)],
                    'average_weight' => $tipoAve === 'laying_hens' ? rand(1500, 2200) : rand(1800, 3000),
                    'status' => 'active',
                    'notes' => 'Lote en buenas condiciones, seguimiento regular',
                    'purchase_price' => rand(8000, 15000),
                    'supplier' => 'Proveedor ' . chr(65 + rand(0, 4)) // Proveedores A, B, C, D, E
                ]);
            }
        }
        
        $this->command->info('✅ Datos de aves creados exitosamente');
    }

    /**
     * Crear productos de inventario
     */
    private function createInventoryProducts()
    {
        $this->command->info('📦 Creando productos de inventario...');
        
        $productos = [
            [
                'name' => 'Concentrado Ponedoras Premium',
                'description' => 'Alimento balanceado para gallinas ponedoras con alto contenido proteico',
                'category' => 'alimentos',
                'unit_measure' => 'kg',
                'unit_price' => 85000,
                'current_stock' => 500,
                'minimum_stock' => 50,
                'supplier' => 'Alimentos Concentrados SA',
                'status' => 'active',
                'code' => 'INV000001'
            ],
            [
                'name' => 'Concentrado Pollos Engorde',
                'description' => 'Alimento especial para pollos de engorde con promotores de crecimiento',
                'category' => 'alimentos',
                'unit_measure' => 'kg',
                'unit_price' => 78000,
                'current_stock' => 800,
                'minimum_stock' => 80,
                'supplier' => 'Nutrición Avícola Ltda',
                'status' => 'active',
                'code' => 'INV000002'
            ],
            [
                'name' => 'Premezcla Vitamínica',
                'description' => 'Suplemento vitamínico para aves de corral',
                'category' => 'medicamentos',
                'unit_measure' => 'kg',
                'unit_price' => 125000,
                'current_stock' => 100,
                'minimum_stock' => 10,
                'supplier' => 'Vitaminas y Minerales SAS',
                'status' => 'active',
                'code' => 'INV000003'
            ],
            [
                'name' => 'Maíz Molido',
                'description' => 'Maíz molido para complemento alimenticio',
                'category' => 'alimentos',
                'unit_measure' => 'kg',
                'unit_price' => 45000,
                'current_stock' => 1200,
                'minimum_stock' => 200,
                'supplier' => 'Granos del Valle',
                'status' => 'active',
                'code' => 'INV000004'
            ],
            [
                'name' => 'Torta de Soya',
                'description' => 'Fuente de proteína vegetal para aves',
                'category' => 'alimentos',
                'unit_measure' => 'kg',
                'unit_price' => 92000,
                'current_stock' => 300,
                'minimum_stock' => 30,
                'supplier' => 'Oleaginosas del Cauca',
                'status' => 'active',
                'code' => 'INV000005'
            ]
        ];

        foreach ($productos as $producto) {
            InventoryProduct::create($producto);
        }
        
        $this->command->info('✅ Productos de inventario creados exitosamente');
    }

    /**
     * Crear registros de consumo de alimentos
     */
    private function createFoodConsumption()
    {
        $this->command->info('🍽️ Creando registros de consumo de alimentos...');
        
        $galpones = PoultryFacility::all();
        $productos = InventoryProduct::all();
        
        foreach ($galpones as $galpon) {
            // Crear varios registros de consumo por galpón
            $numRegistros = rand(15, 25);
            
            for ($i = 0; $i < $numRegistros; $i++) {
                $producto = $productos->random();
                $cantidadKg = rand(50, 200);
                $numeroAves = Bird::where('poultry_facility_id', $galpon->id)->sum('quantity');
                
                FoodConsumption::create([
                    'galpon_id' => $galpon->id,
                    'producto_id' => $producto->id,
                    'fecha_registro' => Carbon::now()->subDays(rand(1, 60)),
                    'cantidad_kg' => $cantidadKg,
                    'cantidad_bultos' => ceil($cantidadKg / 40), // Asumiendo bultos de 40kg
                    'peso_por_bulto' => 40,
                    'numero_aves' => $numeroAves ?: rand(800, 2000),
                    'responsable' => 'Operario ' . chr(65 + rand(0, 4)),
                    'observaciones' => rand(0, 1) ? 'Consumo normal según programación' : 'Ajuste por condiciones climáticas',
                    'estado' => 'active'
                ]);
            }
        }
        
        $this->command->info('✅ Registros de consumo de alimentos creados exitosamente');
    }

    /**
     * Crear 10 producciones de carne y 10 de huevos
     */
    private function createProductionData()
    {
        $this->command->info('🥚 Creando registros de producción...');
        
        $galponesHuevos = PoultryFacility::where('tipo', 'gallinas_ponedoras')->get();
        $galponesCarne = PoultryFacility::where('tipo', 'pollos_engorde')->get();
        
        // Crear 10 producciones de huevos
        $this->command->info('🥚 Creando 10 producciones de huevos...');
        for ($i = 0; $i < 10; $i++) {
            $galpon = $galponesHuevos->random();
            $cantidad = rand(800, 1500);
            $huevosRotos = rand(5, 25);
            $huevosSucios = rand(10, 40);
            $valorUnidad = rand(450, 650);
            
            Production::create([
                'fecha' => Carbon::now()->subDays(rand(1, 30)),
                'tipo' => ['A', 'AA', 'B', 'C'][rand(0, 3)],
                'tipo_produccion' => 'huevos',
                'galpon_id' => $galpon->id,
                'cantidad' => $cantidad,
                'mortalidad_aves' => rand(0, 5),
                'huevos_rotos' => $huevosRotos,
                'huevos_sucios' => $huevosSucios,
                'valor_unidad' => $valorUnidad,
                'valor_total' => $cantidad * $valorUnidad,
                'destino' => ['Mercado Local', 'Distribuidora Central', 'Exportación', 'Venta Directa'][rand(0, 3)],
                'firma_recibido' => 'Recibido por ' . chr(65 + rand(0, 4)),
                'semana_produccion' => 'Semana ' . rand(1, 52),
                'firma_lider' => 'Líder ' . chr(65 + rand(0, 2)),
                'estado' => 'activo',
                'observaciones' => 'Producción dentro de parámetros normales'
            ]);
        }
        
        // Crear 10 producciones de carne
        $this->command->info('🍗 Creando 10 producciones de carne...');
        for ($i = 0; $i < 10; $i++) {
            $galpon = $galponesCarne->random();
            $cantidad = rand(200, 500); // Número de pollos
            $pesoPromedio = rand(2.2, 3.8);
            $valorUnidad = rand(12000, 18000);
            
            Production::create([
                'fecha' => Carbon::now()->subDays(rand(1, 30)),
                'tipo' => 'A', // Debe ser uno de los tipos válidos de la enum
                'tipo_produccion' => 'carne',
                'galpon_id' => $galpon->id,
                'cantidad' => $cantidad,
                'mortalidad_aves' => rand(0, 8),
                'peso_promedio' => $pesoPromedio,
                'peso_total' => $cantidad * $pesoPromedio,
                'valor_unidad' => $valorUnidad,
                'valor_total' => $cantidad * $valorUnidad,
                'destino' => ['Frigorífico Central', 'Carnicería Local', 'Restaurantes', 'Supermercados'][rand(0, 3)],
                'firma_recibido' => 'Recibido por ' . chr(65 + rand(0, 4)),
                'semana_produccion' => 'Semana ' . rand(1, 52),
                'firma_lider' => 'Líder ' . chr(65 + rand(0, 2)),
                'estado' => 'activo',
                'observaciones' => 'Lote procesado según estándares de calidad'
            ]);
        }
        
        $this->command->info('✅ Registros de producción creados exitosamente');
    }

    /**
     * Crear 5-10 registros de costos de producción
     */
    private function createProductionCosts()
    {
        $this->command->info('💰 Creando registros de costos de producción...');
        
        $galpones = PoultryFacility::all();
        $numCostos = rand(7, 10);
        
        for ($i = 0; $i < $numCostos; $i++) {
            $galpon = $galpones->random();
            $costoAlimento = rand(800000, 1500000);
            $costoManoObra = rand(300000, 600000);
            $costoVeterinario = rand(100000, 300000);
            $costoMantenimiento = rand(150000, 400000);
            $otrosCostos = rand(50000, 200000);
            
            $totalCost = $costoAlimento + $costoManoObra + $costoVeterinario + $costoMantenimiento + $otrosCostos;
            
            ProductionCost::create([
                'poultry_facility_id' => $galpon->id,
                'period_start' => Carbon::now()->subDays(rand(30, 90)),
                'period_end' => Carbon::now()->subDays(rand(1, 29)),
                'cost_type' => 'period',
                'concentrate_cost' => $costoAlimento,
                'water_cost' => rand(50000, 150000),
                'energy_cost' => rand(100000, 300000),
                'medication_cost' => $costoVeterinario,
                'biological_cost' => rand(80000, 200000),
                'packaging_cost' => rand(30000, 100000),
                'labor_cost' => $costoManoObra,
                'maintenance_cost' => $costoMantenimiento,
                'other_costs' => $otrosCostos,
                'total_cost' => $totalCost,
                'cost_per_unit' => $totalCost / rand(1000, 3000), // Costo por unidad
                'unit_type' => $galpon->tipo === 'gallinas_ponedoras' ? 'egg' : 'kg_meat',
                'notes' => 'Costos calculados para período de producción estándar',
                'status' => 'confirmed'
            ]);
        }
        
        $this->command->info('✅ Registros de costos de producción creados exitosamente');
    }

    /**
     * Mostrar resumen de datos creados
     */
    private function showSummary()
    {
        $this->command->info("\n📊 RESUMEN DE DATOS CREADOS:");
        $this->command->info("🏠 Galpones: " . PoultryFacility::count());
        $this->command->info("🐔 Lotes de Aves: " . Bird::count());
        $this->command->info("📦 Productos de Inventario: " . InventoryProduct::count());
        $this->command->info("🍽️ Registros de Consumo de Alimentos: " . FoodConsumption::count());
        $this->command->info("🥚 Registros de Producción: " . Production::count());
        $this->command->info("   - Huevos: " . Production::where('tipo_produccion', 'huevos')->count());
        $this->command->info("   - Carne: " . Production::where('tipo_produccion', 'carne')->count());
        $this->command->info("💰 Registros de Costos: " . ProductionCost::count());
        
        $this->command->info("\n🎉 ¡Todos los datos de prueba han sido creados exitosamente!");
        $this->command->info("🔍 Ahora puedes probar los informes de producción con datos realistas.");
    }
}
