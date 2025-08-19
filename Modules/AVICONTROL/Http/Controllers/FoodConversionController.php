<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AVICONTROL\Entities\FoodConversion;
use Modules\AVICONTROL\Entities\FoodConsumption;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Illuminate\Support\Facades\DB;

class FoodConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FoodConversion::with(['galpon']);

        // Filtros
        if ($request->filled('galpon_id')) {
            $query->where('galpon_id', $request->galpon_id);
        }

        if ($request->filled('periodo_tipo')) {
            $query->where('periodo_tipo', $request->periodo_tipo);
        }

        if ($request->filled('tipo_produccion')) {
            $query->where('tipo_produccion', $request->tipo_produccion);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $conversiones = $query->orderBy('fecha_inicio', 'desc')->paginate(15);
        $galpones = PoultryFacility::all();

        return view('avicontrol::admin.food_conversion.index', compact('conversiones', 'galpones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $galpones = PoultryFacility::all();
        
        return view('avicontrol::admin.food_conversion.create', compact('galpones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'galpon_id' => 'required|exists:avicontrol_poultry_facilities,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'periodo_tipo' => 'required|in:diario,semanal,mensual,acumulado',
            'total_alimento_consumido' => 'required|numeric|min:0',
            'total_producto_obtenido' => 'required|numeric|min:0',
            'tipo_produccion' => 'required|in:huevo,carne',
            'observaciones' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $conversion = FoodConversion::create([
                'galpon_id' => $request->galpon_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'periodo_tipo' => $request->periodo_tipo,
                'total_alimento_consumido' => $request->total_alimento_consumido,
                'total_producto_obtenido' => $request->total_producto_obtenido,
                'tipo_produccion' => $request->tipo_produccion,
                'observaciones' => $request->observaciones,
                'estado' => FoodConversion::STATUS_ACTIVE
            ]);

            // Calcular conversión alimenticia
            $conversion->calcularConversionAlimenticia();

            DB::commit();

            return redirect()->route('avicontrol.admin.food_conversion.index')
                ->with('success', 'Conversión alimenticia registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al registrar la conversión: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $conversion = FoodConversion::with(['galpon'])->findOrFail($id);
        
        return view('avicontrol::admin.food_conversion.show', compact('conversion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $conversion = FoodConversion::findOrFail($id);
        $galpones = PoultryFacility::all();
        
        return view('avicontrol::admin.food_conversion.edit', compact('conversion', 'galpones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $conversion = FoodConversion::findOrFail($id);
        
        $request->validate([
            'galpon_id' => 'required|exists:avicontrol_poultry_facilities,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'periodo_tipo' => 'required|in:diario,semanal,mensual,acumulado',
            'total_alimento_consumido' => 'required|numeric|min:0',
            'total_producto_obtenido' => 'required|numeric|min:0',
            'tipo_produccion' => 'required|in:huevo,carne',
            'observaciones' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $conversion->update([
                'galpon_id' => $request->galpon_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'periodo_tipo' => $request->periodo_tipo,
                'total_alimento_consumido' => $request->total_alimento_consumido,
                'total_producto_obtenido' => $request->total_producto_obtenido,
                'tipo_produccion' => $request->tipo_produccion,
                'observaciones' => $request->observaciones
            ]);

            // Recalcular conversión alimenticia
            $conversion->calcularConversionAlimenticia();

            DB::commit();

            return redirect()->route('avicontrol.admin.food_conversion.index')
                ->with('success', 'Conversión alimenticia actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al actualizar la conversión: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $conversion = FoodConversion::findOrFail($id);
        
        $conversion->update(['estado' => FoodConversion::STATUS_CANCELLED]);

        return redirect()->route('avicontrol.admin.food_conversion.index')
            ->with('success', 'Conversión alimenticia cancelada exitosamente.');
    }

    /**
     * Calcular conversión alimenticia automáticamente desde consumos
     */
    public function calcularAutomaticamente(Request $request)
    {
        $request->validate([
            'galpon_id' => 'required|exists:avicontrol_poultry_facilities,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_produccion' => 'required|in:huevo,carne'
        ]);

        try {
            $conversion = FoodConversion::calcularDesdeConsumos(
                $request->galpon_id,
                $request->fecha_inicio,
                $request->fecha_fin,
                $request->tipo_produccion
            );

            if ($conversion) {
                return redirect()->route('avicontrol.admin.food_conversion.index')
                    ->with('success', 'Conversión alimenticia calculada automáticamente.');
            } else {
                return back()->withErrors(['error' => 'No se pudo calcular la conversión. Verifique que existan datos de consumo y producción.']);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al calcular la conversión: ' . $e->getMessage()]);
        }
    }

    /**
     * Generar reporte de conversión alimenticia
     */
    public function reporte(Request $request)
    {
        $query = FoodConversion::with(['galpon']);

        // Aplicar filtros
        if ($request->filled('galpon_id')) {
            $query->where('galpon_id', $request->galpon_id);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled('periodo_tipo')) {
            $query->where('periodo_tipo', $request->periodo_tipo);
        }

        $conversiones = $query->orderBy('fecha_inicio', 'desc')->get();
        $galpones = PoultryFacility::all();

        // Calcular estadísticas para el reporte
        $totalRegistros = $conversiones->count();
        $promedioConversion = $conversiones->avg('conversion_alimenticia');
        $totalAlimento = $conversiones->sum('total_alimento_consumido');
        $totalProducto = $conversiones->sum('total_producto_obtenido');

        // Análisis por galpón
        $conversionPorGalpon = $conversiones->groupBy('galpon_id')->map(function ($group) {
            return [
                'galpon_id' => $group->first()->galpon_id,
                'galpon' => $group->first()->galpon,
                'total_registros' => $group->count(),
                'promedio_conversion' => $group->avg('conversion_alimenticia'),
                'mejor_conversion' => $group->min('conversion_alimenticia'),
                'peor_conversion' => $group->max('conversion_alimenticia'),
                'total_alimento' => $group->sum('total_alimento_consumido'),
                'total_producto' => $group->sum('total_producto_obtenido')
            ];
        })->values();

        $data = [
            'conversiones' => $conversiones,
            'total_registros' => $totalRegistros,
            'promedio_conversion' => $promedioConversion,
            'total_alimento' => $totalAlimento,
            'total_producto' => $totalProducto,
            'conversion_por_galpon' => $conversionPorGalpon
        ];

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('avicontrol::admin.food_conversion.reporte', compact('conversiones', 'galpones', 'data'));
    }

    /**
     * Obtener estadísticas de conversión alimenticia
     */
    public function estadisticas(Request $request)
    {
        $query = FoodConversion::with(['galpon']);

        // Aplicar filtros
        if ($request->filled('galpon_id')) {
            $query->where('galpon_id', $request->galpon_id);
        }

        if ($request->filled('tipo_produccion')) {
            $query->where('tipo_produccion', $request->tipo_produccion);
        }

        if ($request->filled('periodo_tipo')) {
            $query->where('periodo_tipo', $request->periodo_tipo);
        }

        // Obtener datos base
        $conversiones = $query->get();

        // Calcular estadísticas
        $estadisticas = [
            'promedio_conversion' => $conversiones->avg('conversion_alimenticia'),
            'mejor_conversion' => $conversiones->min('conversion_alimenticia'),
            'peor_conversion' => $conversiones->max('conversion_alimenticia'),
            'total_registros' => $conversiones->count(),
            'conversion_por_galpon' => $conversiones->groupBy('galpon_id')->map(function ($group) {
                return [
                    'galpon_id' => $group->first()->galpon_id,
                    'galpon' => $group->first()->galpon,
                    'promedio_conversion' => $group->avg('conversion_alimenticia'),
                    'total_registros' => $group->count(),
                    'mejor_conversion' => $group->min('conversion_alimenticia'),
                    'peor_conversion' => $group->max('conversion_alimenticia'),
                    'total_alimento' => $group->sum('total_alimento_consumido'),
                    'total_producto' => $group->sum('total_producto_obtenido')
                ];
            })->values()
        ];

        // Si es una petición AJAX, devolver JSON
        if ($request->wantsJson()) {
            return response()->json($estadisticas);
        }

        // Si no es AJAX, devolver la vista con datos iniciales
        $galpones = PoultryFacility::all();
        return view('avicontrol::admin.food_conversion.estadisticas', compact('estadisticas', 'galpones'));
    }

    /**
     * Exportar reporte de conversión alimenticia
     */
    public function exportar(Request $request)
    {
        $galponId = $request->get('galpon_id');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $periodo = $request->get('periodo_tipo', FoodConversion::PERIODO_ACUMULADO);

        $conversiones = FoodConversion::generarReporte($galponId, $fechaInicio, $fechaFin, $periodo);

        // Aquí se implementaría la lógica de exportación (PDF, Excel, etc.)
        return response()->json(['message' => 'Exportación implementada', 'data' => $conversiones]);
    }
}
