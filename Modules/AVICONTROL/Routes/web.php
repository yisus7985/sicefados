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
use Modules\AVICONTROL\Http\Controllers\FoodConsumptionController;
use Modules\AVICONTROL\Http\Controllers\ProductionController;
use Modules\AVICONTROL\Http\Controllers\ChatbotController;

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
        
        // Exportar dashboard a PDF
        Route::get('/admin/dashboard/export-pdf', [AVICONTROLController::class, 'exportDashboardPDF'])->name('avicontrol.admin.dashboard.export-pdf');
        
        // Chatbot AI Routes
        Route::get('/admin/chatbot/test', [ChatbotController::class, 'test'])->name('avicontrol.admin.chatbot.test');
        Route::post('/admin/chatbot/chat', [ChatbotController::class, 'chat'])->name('avicontrol.admin.chatbot.chat');
        Route::get('/admin/chatbot/history', [ChatbotController::class, 'getHistory'])->name('avicontrol.admin.chatbot.history');
        Route::delete('/admin/chatbot/history', [ChatbotController::class, 'clearHistory'])->name('avicontrol.admin.chatbot.clear');

        // Ruta específica de logout para AVICONTROL
        Route::post('/admin/logout', function () {
            Auth::logout();
            return redirect()->route('cefa.welcome');
        })->name('avicontrol.admin.logout');

        // Grupo de rutas administrativas
        Route::prefix('admin')->name('avicontrol.admin.')->middleware(['auth'])->group(function () {

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

            // ✅ Rutas para el módulo de información
            Route::prefix('information')->name('information.')->group(function () {
                Route::get('/', [InformationController::class, 'index'])->name('index');
                Route::get('/produccion', [InformationController::class, 'produccion'])->name('produccion');
                Route::get('/alimentos', [InformationController::class, 'alimentos'])->name('alimentos');
                Route::get('/seguimientos', [InformationController::class, 'seguimientos'])->name('seguimientos');
                Route::get('/costos-produccion', [InformationController::class, 'costosProduccion'])->name('costos_produccion');
                Route::get('/costos-produccion/exportar-pdf', [InformationController::class, 'exportarCostosProduccionPdf'])->name('costos_produccion.pdf');
                Route::get('/costos-produccion/exportar-excel', [InformationController::class, 'exportarCostosProduccionExcel'])->name('costos_produccion.excel');
                Route::get('/costos-produccion/{id}/exportar-pdf', [InformationController::class, 'exportarCostoProduccionPdf'])->name('costos_produccion.pdf_show');
                Route::get('/{id}', [InformationController::class, 'show'])->name('show');
                
                // Ruta para detalle del galpón
                Route::get('/galpon/{galpon_id}/detalle', [InformationController::class, 'detalleGalpon'])->name('galpon.detalle');
                
                // Ruta de prueba
                Route::get('/test-detalle', [InformationController::class, 'testDetalleGalpon'])->name('test.detalle');
                Route::get('/test-produccion', [InformationController::class, 'testProduccion'])->name('test.produccion');
                
                // Endpoints AJAX para datos filtrados
                Route::post('/produccion/filtrar', [InformationController::class, 'filtrarProduccion'])->name('produccion.filtrar');
                Route::post('/alimentos/filtrar', [InformationController::class, 'filtrarAlimentos'])->name('alimentos.filtrar');
                Route::post('/alimentos/filtrar-consumo', [InformationController::class, 'filtrarConsumoAlimentos'])->name('alimentos.filtrar.consumo');
                Route::post('/seguimientos/filtrar', [InformationController::class, 'filtrarSeguimientos'])->name('seguimientos.filtrar');
                
                // Exportaciones
                Route::get('/produccion/exportar-excel', [InformationController::class, 'exportarProduccionExcel'])->name('produccion.excel');
                Route::get('/produccion/exportar-pdf', [InformationController::class, 'exportarProduccionPdf'])->name('produccion.pdf');
                Route::get('/alimentos/exportar-excel', [InformationController::class, 'exportarAlimentosExcel'])->name('alimentos.excel');
                Route::get('/alimentos/exportar-pdf', [InformationController::class, 'exportarAlimentosPdf'])->name('alimentos.pdf');
                Route::get('/alimentos/{id}/exportar-pdf', [InformationController::class, 'exportarAlimentoIndividualPdf'])->name('alimentos.pdf_individual');
                Route::post('/alimentos/eliminar', [InformationController::class, 'eliminarAlimento'])->name('alimentos.eliminar');
                Route::get('/seguimientos/exportar-excel', [InformationController::class, 'exportarSeguimientosExcel'])->name('seguimientos.excel');
                
                // Actualización de informes
                Route::post('/alimentos/actualizar-informes', [InformationController::class, 'actualizarInformesAlimentos'])->name('alimentos.actualizar');
                Route::get('/seguimientos/exportar-pdf', [InformationController::class, 'exportarSeguimientosPdf'])->name('seguimientos.pdf');
            });
            
            // Endpoint AJAX para informes filtrados
   

            // ✅ Rutas para inventario
            Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
            
            // Ruta de prueba para debugging
            Route::get('inventory/test-movements', function() {
                return response()->json([
                    'message' => 'Test route works',
                    'timestamp' => now(),
                    'route_name' => 'avicontrol.admin.inventory.test-movements'
                ]);
            })->name('inventory.test-movements');
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
            Route::get('inventory/movements/stats', [InventoryController::class, 'getMovementStats'])->name('inventory.movements.stats');
            
            // Ruta de respaldo simple para debugging
            Route::get('inventory/movements-simple', function() {
                \Log::info('Simple movements route accessed');
                return view('avicontrol::admin.inventory.movements.simple');
            })->name('inventory.movements.simple');
            
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
            
            // AJAX para obtener lotes filtrados por galpón
            Route::post('production_costs/get-birds-by-facility', [ProductionCostController::class, 'getBirdsByFacility'])->name('production_costs.get_birds_by_facility');

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

            // ✅ Rutas para consumo de alimento (RF-010)
            Route::get('food_consumption', [FoodConsumptionController::class, 'index'])->name('food_consumption.index');
            Route::get('food_consumption/create', [FoodConsumptionController::class, 'create'])->name('food_consumption.create');
            Route::post('food_consumption', [FoodConsumptionController::class, 'store'])->name('food_consumption.store');
            Route::get('food_consumption/{id}', [FoodConsumptionController::class, 'show'])->name('food_consumption.show');
            Route::get('food_consumption/{id}/edit', [FoodConsumptionController::class, 'edit'])->name('food_consumption.edit');
            Route::put('food_consumption/{id}', [FoodConsumptionController::class, 'update'])->name('food_consumption.update');
            Route::delete('food_consumption/{id}', [FoodConsumptionController::class, 'destroy'])->name('food_consumption.destroy');
            Route::get('food_consumption/estadisticas', [FoodConsumptionController::class, 'estadisticas'])->name('food_consumption.estadisticas');
            Route::post('food_consumption/exportar', [FoodConsumptionController::class, 'exportar'])->name('food_consumption.exportar');
            
            // AJAX para obtener número de aves por galpón
            Route::post('food_consumption/get-birds-count', [FoodConsumptionController::class, 'getBirdsCount'])->name('food_consumption.get_birds_count');

            // ✅ Ruta para alimentación (redirige directamente a consumo de alimento)
            Route::get('food', function() {
                return redirect()->route('avicontrol.admin.food_consumption.index');
            })->name('food.index');




            // Routes for Production management
            Route::get('production', [ProductionController::class, 'index'])->name('production.index');
            Route::get('production/create', [ProductionController::class, 'create'])->name('production.create');
            Route::post('production', [ProductionController::class, 'store'])->name('production.store');
            Route::get('production/{id}', [ProductionController::class, 'show'])->name('production.show');
            Route::get('production/{id}/edit', [ProductionController::class, 'edit'])->name('production.edit');
            Route::put('production/{id}', [ProductionController::class, 'update'])->name('production.update');
            Route::delete('production/{id}', [ProductionController::class, 'destroy'])->name('production.destroy');
            
            // Ruta para crear nueva semana de producción
            Route::post('production/store-week', [ProductionController::class, 'storeWeek'])->name('production.store-week');
            
            // Rutas adicionales de producción
            Route::get('production/dashboard', [ProductionController::class, 'dashboard'])->name('production.dashboard');
            Route::get('production/report', [ProductionController::class, 'report'])->name('production.report');
            Route::get('production/test-pdf', [ProductionController::class, 'testPdf'])->name('production.test_pdf');
            Route::post('production/bulk-action', [ProductionController::class, 'bulkAction'])->name('production.bulk-action');
            Route::get('production/stats', [ProductionController::class, 'getStats'])->name('production.stats');
            Route::get('production/trend', [ProductionController::class, 'getTrend'])->name('production.trend');
            Route::get('production/batches-by-facility', [ProductionController::class, 'getBatchesByFacility'])->name('production.batches-by-facility');
        });

    });

});