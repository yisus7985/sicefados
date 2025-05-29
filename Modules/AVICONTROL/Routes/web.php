<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'lang'])->group(function() {
    Route::prefix('avicontrol')->group(function() {
        Route::get('/index', 'AVICONTROLController@index')->name('cefa.avicontrol.index');
        Route::get('/admin/welcome', 'AVICONTROLController@admin')->name('avicontrol.admin.welcome');
        
        // Routes for poultry facilities management
        Route::prefix('admin')->name('avicontrol.admin.')->group(function() {
            // Explicit routes for poultry_facilities
            Route::get('poultry_facilities', 'PoultryFacilityController@index')->name('poultry_facilities.index');
            Route::get('poultry_facilities/create', 'PoultryFacilityController@create')->name('poultry_facilities.create');
            Route::post('poultry_facilities', 'PoultryFacilityController@store')->name('poultry_facilities.store');
            Route::get('poultry_facilities/{id}', 'PoultryFacilityController@show')->name('poultry_facilities.show');
            Route::get('poultry_facilities/{id}/edit', 'PoultryFacilityController@edit')->name('poultry_facilities.edit');
            Route::put('poultry_facilities/{id}', 'PoultryFacilityController@update')->name('poultry_facilities.update');
            Route::delete('poultry_facilities/{id}', 'PoultryFacilityController@destroy')->name('poultry_facilities.destroy');
            
            // Explicit routes for poultry_houses (alias for poultry_facilities)
            Route::get('poultry_houses', 'PoultryFacilityController@index')->name('poultry_houses.index');
            Route::get('poultry_houses/create', 'PoultryFacilityController@create')->name('poultry_houses.create');
            Route::post('poultry_houses', 'PoultryFacilityController@store')->name('poultry_houses.store');
            Route::get('poultry_houses/{id}', 'PoultryFacilityController@show')->name('poultry_houses.show');
            Route::get('poultry_houses/{id}/edit', 'PoultryFacilityController@edit')->name('poultry_houses.edit');
            Route::put('poultry_houses/{id}', 'PoultryFacilityController@update')->name('poultry_houses.update');
            Route::delete('poultry_houses/{id}', 'PoultryFacilityController@destroy')->name('poultry_houses.destroy');

            // Routes for birds management
            Route::get('birds', 'BirdController@index')->name('birds.index');
            Route::get('birds/create', 'BirdController@create')->name('birds.create');
            Route::post('birds', 'BirdController@store')->name('birds.store');
            Route::get('birds/{id}', 'BirdController@show')->name('birds.show');
            Route::get('birds/{id}/edit', 'BirdController@edit')->name('birds.edit');
            Route::put('birds/{id}', 'BirdController@update')->name('birds.update');
            Route::delete('birds/{id}', 'BirdController@destroy')->name('birds.destroy');

            // AJAX route for facility capacity
            Route::get('facilities/{id}/capacity', 'BirdController@getFacilityCapacity')->name('facilities.capacity');
        });
    });
});
