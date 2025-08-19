<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstadisticasController;

// RUTAS COMPLETAMENTE INDEPENDIENTES PARA AVICONTROL
Route::prefix('avicontrol/admin')->group(function () {
    // Estadísticas de mermas
    Route::get('/food_waste/estadisticas', [EstadisticasController::class, 'mermas'])
        ->name('avicontrol.admin.food_waste.estadisticas');
    
    // Reporte de mermas
    Route::get('/food_waste/reporte', [EstadisticasController::class, 'reporte'])
        ->name('avicontrol.admin.food_waste.reporte');
    
    // Ruta alternativa para estadísticas
    Route::get('/estadisticas-mermas', [EstadisticasController::class, 'mermas'])
        ->name('estadisticas.mermas');
    
    // Ruta alternativa para reporte
    Route::get('/reporte-mermas', [EstadisticasController::class, 'reporte'])
        ->name('reporte.mermas');
});
