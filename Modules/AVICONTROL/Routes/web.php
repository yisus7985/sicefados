<?php

use Illuminate\Support\Facades\Route;
use Modules\AVICONTROL\Http\Controllers\InformationController;
use Modules\AVICONTROL\Http\Controllers\InventoryController;
use Modules\AVICONTROL\Http\Controllers\AVICONTROLController;
use Modules\AVICONTROL\Http\Controllers\BirdController;
use Modules\AVICONTROL\Http\Controllers\PoultryFacilityController;

Route::middleware(['web', 'lang'])->group(function () {

    Route::prefix('avicontrol')->group(function () {

        // Página principal
        Route::get('/index', 'AVICONTROLController@index')->name('cefa.avicontrol.index');

        // Vista de bienvenida para administradores
        Route::get('/admin/welcome', [AVICONTROLController::class, 'admin'])->name('avicontrol.admin.welcome');

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
            Route::get('poultry_houses', 'PoultryFacilityController@index')->name('poultry_houses.index');
            Route::get('poultry_houses/create', 'PoultryFacilityController@create')->name('poultry_houses.create');
            Route::post('poultry_houses', 'PoultryFacilityController@store')->name('poultry_houses.store');
            Route::get('poultry_houses/{id}', 'PoultryFacilityController@show')->name('poultry_houses.show');
            Route::get('poultry_houses/{id}/edit', 'PoultryFacilityController@edit')->name('poultry_houses.edit');
            Route::put('poultry_houses/{id}', 'PoultryFacilityController@update')->name('poultry_houses.update');
            Route::delete('poultry_houses/{id}', 'PoultryFacilityController@destroy')->name('poultry_houses.destroy');

            // Rutas para aves (birds)
            Route::get('birds', [BirdController::class, 'index'])->name('birds.index');
            Route::get('birds/create', [BirdController::class, 'create'])->name('birds.create');
            Route::post('birds', [BirdController::class, 'store'])->name('birds.store');
            Route::get('birds/{id}', [BirdController::class, 'show'])->name('birds.show');
            Route::get('birds/{id}/edit', [BirdController::class, 'edit'])->name('birds.edit');
            Route::put('birds/{id}', [BirdController::class, 'update'])->name('birds.update');
            Route::delete('birds/{id}', [BirdController::class, 'destroy'])->name('birds.destroy');

            // AJAX para obtener capacidad del galpón
            Route::get('facilities/{id}/capacity', 'BirdController@getFacilityCapacity')->name('facilities.capacity');

            // ✅ Nuevas rutas para informes
            Route::get('information', [InformationController::class, 'index'])->name('information.index');
            Route::get('information/{id}', [InformationController::class, 'show'])->name('information.show');

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

        });

    });

});