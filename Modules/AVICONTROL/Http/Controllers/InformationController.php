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
        $galpones = PoultryFacility::all();
        return view('avicontrol::admin.information.index', compact('galpones'));
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