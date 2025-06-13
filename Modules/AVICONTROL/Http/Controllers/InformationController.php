<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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

}