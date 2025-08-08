<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "Ejecutando migraciones de AVICONTROL...\n";

try {
    // Ejecutar migraciones
    $output = Artisan::call('migrate', [
        '--path' => 'Modules/AVICONTROL/Database/Migrations',
        '--force' => true
    ]);
    
    echo "✅ Migraciones ejecutadas exitosamente\n";
    echo "Salida: " . Artisan::output() . "\n";
    
} catch (Exception $e) {
    echo "❌ Error ejecutando migraciones: " . $e->getMessage() . "\n";
}

echo "\nVerificando tablas...\n";

$tables = [
    'avicontrol_production_costs',
    'avicontrol_cost_components', 
    'avicontrol_profitability_analysis',
    'avicontrol_inventory_products',
    'avicontrol_inventory_movements',
    'avicontrol_poultry_facilities',
    'avicontrol_birds'
];

foreach ($tables as $table) {
    if (\Schema::hasTable($table)) {
        echo "✅ Tabla {$table} existe\n";
    } else {
        echo "❌ Tabla {$table} NO existe\n";
    }
} 