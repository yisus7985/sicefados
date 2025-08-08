<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Modules\AVICONTROL\Http\Controllers\InformationController;
use Modules\AVICONTROL\Http\Controllers\InventoryController;
use Modules\AVICONTROL\Http\Controllers\AVICONTROLController;
use Modules\AVICONTROL\Http\Controllers\BirdController;
use Modules\AVICONTROL\Http\Controllers\PoultryFacilityController;
use Modules\AVICONTROL\Http\Controllers\ProductionCostController;
use Modules\AVICONTROL\Http\Controllers\ProfitabilityAnalysisController;
use Modules\AVICONTROL\Http\Controllers\AlertController;

Route::middleware(['web', 'lang'])->group(function () {

    Route::prefix('avicontrol')->group(function () {

        // Ruta para servir imágenes estáticas del módulo
        Route::get('/img/{folder}/{filename}', function ($folder, $filename) {
            $path = module_path('AVICONTROL', 'img/' . $folder . '/' . $filename);
            if (file_exists($path)) {
                return response()->file($path);
            }
            abort(404);
        })->where(['folder' => '.*', 'filename' => '.*']);

        // Página principal
        Route::get('/index', [AVICONTROLController::class, 'index'])->name('cefa.avicontrol.index');

        // Vista de bienvenida para administradores
        Route::get('/admin/welcome', [AVICONTROLController::class, 'admin'])->name('avicontrol.admin.welcome');

        // Ruta específica de logout para AVICONTROL
        Route::post('/admin/logout', function () {
            Auth::logout();
            return redirect()->route('cefa.welcome');
        })->name('avicontrol.admin.logout');

        // Grupo de rutas administrativas
        Route::prefix('admin')->name('avicontrol.admin.')->group(function () {

            // Rutas de galpones (poultry facilities)
            Route::get('poultry_facilities', [PoultryFacilityController::class, 'index'])->name('poultry_facilities.index');
            Route::get('poultry_facilities/create', [PoultryFacilityController::class, 'create'])->name('poultry_facilities.create');
            Route::post('poultry_facilities', [PoultryFacilityController::class, 'store'])->name('poultry_facilities.store');
            Route::get('poultry_facilities/{id}', [PoultryFacilityController::class, 'show'])->name('poultry_facilities.show');
            Route::get('poultry_facilities/{id}/edit', [PoultryFacilityController::class, 'edit'])->name('poultry_facilities.edit');
            Route::put('poultry_facilities/{id}', [PoultryFacilityController::class, 'update'])->name('poultry_facilities.update');
            Route::delete('poultry_facilities/{id}', [PoultryFacilityController::class, 'destroy'])->name('poultry_facilities.destroy');

            // Alias de poultry_houses (se usan las mismas acciones que poultry_facilities)
            Route::get('poultry_houses', [PoultryFacilityController::class, 'index'])->name('poultry_houses.index');
            Route::get('poultry_houses/create', [PoultryFacilityController::class, 'create'])->name('poultry_houses.create');
            Route::post('poultry_houses', [PoultryFacilityController::class, 'store'])->name('poultry_houses.store');
            Route::get('poultry_houses/{id}', [PoultryFacilityController::class, 'show'])->name('poultry_houses.show');
            Route::get('poultry_houses/{id}/edit', [PoultryFacilityController::class, 'edit'])->name('poultry_houses.edit');
            Route::put('poultry_houses/{id}', [PoultryFacilityController::class, 'update'])->name('poultry_houses.update');
            Route::delete('poultry_houses/{id}', [PoultryFacilityController::class, 'destroy'])->name('poultry_houses.destroy');

            // Rutas para aves (birds)
            Route::get('birds', [BirdController::class, 'index'])->name('birds.index');
            Route::get('birds/create', [BirdController::class, 'create'])->name('birds.create');
            Route::post('birds', [BirdController::class, 'store'])->name('birds.store');
            Route::get('birds/{id}', [BirdController::class, 'show'])->name('birds.show');
            Route::get('birds/{id}/edit', [BirdController::class, 'edit'])->name('birds.edit');
            Route::put('birds/{id}', [BirdController::class, 'update'])->name('birds.update');
            Route::delete('birds/{id}', [BirdController::class, 'destroy'])->name('birds.destroy');

            // AJAX para obtener capacidad del galpón
            Route::get('facilities/{id}/capacity', [BirdController::class, 'getFacilityCapacity'])->name('facilities.capacity');

            // ✅ Nuevas rutas para informes
            Route::get('information', [InformationController::class, 'index'])->name('information.index');
            Route::get('information/inventory', [InformationController::class, 'inventory'])->name('information.inventory');
            Route::get('information/{id}', [InformationController::class, 'show'])->name('information.show');
            // Endpoint AJAX para informes filtrados
            Route::post('information/report-data', [InformationController::class, 'getReportData'])->name('information.report_data');
            // Endpoint para exportar PDF de informes
            Route::post('information/export-pdf', [InformationController::class, 'exportPdf'])->name('information.export_pdf');

            // ✅ Rutas para inventario
            Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
            Route::get('inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
            Route::post('inventory', [InventoryController::class, 'store'])->name('inventory.store');
            Route::get('inventory/{id}', [InventoryController::class, 'show'])->name('inventory.show');
            Route::get('inventory/{id}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
            Route::put('inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
            Route::delete('inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
            
            // Rutas para movimientos de inventario
            Route::get('inventory/{id}/movements/create', [InventoryController::class, 'createMovement'])->name('inventory.movements.create');
            Route::post('inventory/{id}/movements', [InventoryController::class, 'storeMovement'])->name('inventory.movements.store');
            Route::get('inventory/movements', [InventoryController::class, 'movements'])->name('inventory.movements.index');
            
            // Rutas para alertas
            Route::get('inventory/alerts/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low_stock');
            Route::get('inventory/alerts/expiring', [InventoryController::class, 'expiring'])->name('inventory.expiring');

            // ✅ Rutas para costos de producción (RF-013)
            Route::get('production_costs', [ProductionCostController::class, 'index'])->name('production_costs.index');
            Route::get('production_costs/create', [ProductionCostController::class, 'create'])->name('production_costs.create');
            Route::post('production_costs', [ProductionCostController::class, 'store'])->name('production_costs.store');
            Route::get('production_costs/{id}', [ProductionCostController::class, 'show'])->name('production_costs.show');
            Route::get('production_costs/{id}/edit', [ProductionCostController::class, 'edit'])->name('production_costs.edit');
            Route::put('production_costs/{id}', [ProductionCostController::class, 'update'])->name('production_costs.update');
            Route::delete('production_costs/{id}', [ProductionCostController::class, 'destroy'])->name('production_costs.destroy');
            Route::post('production_costs/{id}/confirm', [ProductionCostController::class, 'confirm'])->name('production_costs.confirm');
            
            // AJAX para calcular costos desde inventario
            Route::post('production_costs/calculate-from-inventory', [ProductionCostController::class, 'calculateFromInventory'])->name('production_costs.calculate_from_inventory');
            Route::get('production_costs/summary', [ProductionCostController::class, 'getCostSummary'])->name('production_costs.summary');

            // ✅ Rutas para análisis de rentabilidad (RF-014)
            Route::get('profitability_analysis', [ProfitabilityAnalysisController::class, 'index'])->name('profitability_analysis.index');
            Route::get('profitability_analysis/create', [ProfitabilityAnalysisController::class, 'create'])->name('profitability_analysis.create');
            Route::post('profitability_analysis', [ProfitabilityAnalysisController::class, 'store'])->name('profitability_analysis.store');
            Route::get('profitability_analysis/{id}', [ProfitabilityAnalysisController::class, 'show'])->name('profitability_analysis.show');
            Route::get('profitability_analysis/{id}/edit', [ProfitabilityAnalysisController::class, 'edit'])->name('profitability_analysis.edit');
            Route::put('profitability_analysis/{id}', [ProfitabilityAnalysisController::class, 'update'])->name('profitability_analysis.update');
            Route::delete('profitability_analysis/{id}', [ProfitabilityAnalysisController::class, 'destroy'])->name('profitability_analysis.destroy');
            Route::post('profitability_analysis/{id}/confirm', [ProfitabilityAnalysisController::class, 'confirm'])->name('profitability_analysis.confirm');
            
            // Rutas para reportes de rentabilidad
            Route::get('profitability_analysis/report/generate', [ProfitabilityAnalysisController::class, 'generateReport'])->name('profitability_analysis.report');
            Route::post('profitability_analysis/report', [ProfitabilityAnalysisController::class, 'generateReport'])->name('profitability_analysis.generate_report');
            Route::get('profitability_analysis/{id}/export-pdf', [ProfitabilityAnalysisController::class, 'exportPdf'])->name('profitability_analysis.export_pdf');
            Route::get('profitability_analysis/summary', [ProfitabilityAnalysisController::class, 'getProfitabilitySummary'])->name('profitability_analysis.summary');

            // ✅ Rutas para alertas (RF-020 y RF-021)
            Route::get('alerts', [AlertController::class, 'index'])->name('alerts.index');
            Route::post('alerts/mark-read', [AlertController::class, 'markAsRead'])->name('alerts.mark_read');
            Route::get('alerts/stats', [AlertController::class, 'getStats'])->name('alerts.stats');

        });

    });

});