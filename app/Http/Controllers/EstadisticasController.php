<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EstadisticasController extends Controller
{
    public function mermas()
    {
        return response()->file(public_path('estadisticas_mermas.html'));
    }
    
    public function reporte()
    {
        return response()->file(public_path('estadisticas_mermas.html'));
    }
}
