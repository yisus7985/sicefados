<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstadisticasController;

// Rutas para estadísticas - COMPLETAMENTE INDEPENDIENTES
Route::get('/estadisticas-mermas', [EstadisticasController::class, 'mermas'])
    ->name('estadisticas.mermas');

Route::get('/mermas-estadisticas', function() {
    return response()->file(public_path('estadisticas_mermas.html'));
})->name('mermas.estadisticas');

Route::get('/estadisticas-mermas-html', function() {
    return response()->file(public_path('estadisticas_mermas.html'));
})->name('estadisticas.mermas.html');
