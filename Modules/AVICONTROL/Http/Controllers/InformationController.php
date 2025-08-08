<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Modules\AVICONTROL\Entities\Bird;

class InformationController extends Controller
{
    public function index()
    {
        try {
            // Verificar si las tablas existen
            if (!\Schema::hasTable('avicontrol_poultry_facilities')) {
                return view('avicontrol::admin.information.index', [
                    'galpones' => collect([]),
                    'error' => 'Las tablas de instalaciones no están creadas. Por favor ejecute las migraciones.'
                ]);
            }

            $galpones = PoultryFacility::all();
            return view('avicontrol::admin.information.index', compact('galpones'));
        } catch (\Exception $e) {
            return view('avicontrol::admin.information.index', [
                'galpones' => collect([]),
                'error' => 'Error al cargar los informes: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $galpon = PoultryFacility::with('birds')->findOrFail($id);
        return view('avicontrol::admin.information.show', compact('galpon'));
    }

    public function inventory()
    {
        $birds = Bird::with('poultryFacility')->get();
        return view('avicontrol::admin.information.inventory', compact('birds'));
    }
}