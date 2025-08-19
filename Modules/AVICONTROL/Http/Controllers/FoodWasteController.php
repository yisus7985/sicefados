<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AVICONTROL\Entities\FoodWaste;
use Modules\AVICONTROL\Entities\FoodConsumption;
use Modules\AVICONTROL\Entities\FoodConversion;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Modules\AVICONTROL\Entities\InventoryProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FoodWasteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FoodWaste::with(['galpon', 'producto']);

        // Filtros
        if ($request->filled('galpon_id')) {
            $query->where('galpon_id', $request->galpon_id);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_registro', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled('producto_id')) {
            $query->where('producto_id', $request->producto_id);
        }

        if ($request->filled('causa_merma')) {
            $query->where('causa_merma', $request->causa_merma);
        }

        $mermas = $query->orderBy('fecha_registro', 'desc')->paginate(15);
        $galpones = PoultryFacility::all();
        $productos = InventoryProduct::where('category', 'alimentos')->active()->get();
        $causasMerma = FoodWaste::CAUSAS_MERMA;

        return view('avicontrol::admin.food_waste.index', compact('mermas', 'galpones', 'productos', 'causasMerma'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $galpones = PoultryFacility::all();
        $productos = InventoryProduct::where('category', 'alimentos')->active()->get();
        $causasMerma = FoodWaste::CAUSAS_MERMA;
        
        return view('avicontrol::admin.food_waste.create', compact('galpones', 'productos', 'causasMerma'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha_registro' => 'required|date',
            'galpon_id' => 'nullable|exists:avicontrol_poultry_facilities,id',
            'producto_id' => 'required|exists:avicontrol_inventory_products,id',
            'cantidad_perdida' => 'required|numeric|min:0',
            'causa_merma' => 'required|in:' . implode(',', array_keys(FoodWaste::CAUSAS_MERMA)),
            'observaciones' => 'nullable|string|max:500'
        ]);

        // Validar stock disponible
        $producto = InventoryProduct::find($request->producto_id);
        if ($producto->current_stock < $request->cantidad_perdida) {
            return back()->withErrors(['stock' => 'Stock insuficiente. Disponible: ' . $producto->current_stock . ' kg']);
        }

        DB::beginTransaction();
        try {
            $merma = FoodWaste::create([
                'fecha_registro' => $request->fecha_registro,
                'galpon_id' => $request->galpon_id,
                'producto_id' => $request->producto_id,
                'cantidad_perdida' => $request->cantidad_perdida,
                'causa_merma' => $request->causa_merma,
                'observaciones' => $request->observaciones,
                'responsable' => Auth::user()->name ?? 'Sistema',
                'estado' => FoodWaste::STATUS_ACTIVE
            ]);

            // Actualizar inventario
            $merma->actualizarInventario();

            DB::commit();

            return redirect()->route('avicontrol.admin.food_waste.index')
                ->with('success', 'Merma registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al registrar la merma: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $merma = FoodWaste::with(['galpon', 'producto'])->findOrFail($id);
        
        return view('avicontrol::admin.food_waste.show', compact('merma'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $merma = FoodWaste::findOrFail($id);
        $galpones = PoultryFacility::all();
        $productos = InventoryProduct::where('category', 'alimentos')->active()->get();
        $causasMerma = FoodWaste::CAUSAS_MERMA;
        
        return view('avicontrol::admin.food_waste.edit', compact('merma', 'galpones', 'productos', 'causasMerma'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $merma = FoodWaste::findOrFail($id);
        
        $request->validate([
            'fecha_registro' => 'required|date',
            'galpon_id' => 'nullable|exists:avicontrol_poultry_facilities,id',
            'producto_id' => 'required|exists:avicontrol_inventory_products,id',
            'cantidad_perdida' => 'required|numeric|min:0',
            'causa_merma' => 'required|in:' . implode(',', array_keys(FoodWaste::CAUSAS_MERMA)),
            'observaciones' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Revertir inventario anterior si es necesario
            if ($merma->estado === FoodWaste::STATUS_ACTIVE) {
                $merma->revertirInventario();
            }

            $merma->update([
                'fecha_registro' => $request->fecha_registro,
                'galpon_id' => $request->galpon_id,
                'producto_id' => $request->producto_id,
                'cantidad_perdida' => $request->cantidad_perdida,
                'causa_merma' => $request->causa_merma,
                'observaciones' => $request->observaciones
            ]);

            // Actualizar inventario con nuevos valores
            $merma->actualizarInventario();

            DB::commit();

            return redirect()->route('avicontrol.admin.food_waste.index')
                ->with('success', 'Merma actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al actualizar la merma: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $merma = FoodWaste::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Revertir inventario
            $merma->revertirInventario();
            
            // Cambiar estado a cancelado en lugar de eliminar
            $merma->update(['estado' => FoodWaste::STATUS_CANCELLED]);
            
            DB::commit();

            return redirect()->route('avicontrol.admin.food_waste.index')
                ->with('success', 'Merma cancelada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al cancelar la merma: ' . $e->getMessage()]);
        }
    }

    /**
     * Generar reporte de mermas
     */
    public function reporte(Request $request)
    {
        $query = FoodWaste::with(['galpon', 'producto']);

        // Aplicar filtros
        if ($request->filled('galpon_id')) {
            $query->where('galpon_id', $request->galpon_id);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_registro', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled('causa_merma')) {
            $query->where('causa_merma', $request->causa_merma);
        }

        $mermas = $query->orderBy('fecha_registro', 'desc')->get();
        $galpones = PoultryFacility::all();
        $causasMerma = FoodWaste::CAUSAS_MERMA;

        // Calcular estadísticas para el reporte
        $totalMermas = $mermas->sum('cantidad_perdida');
        $costoTotal = $mermas->sum('costo_perdida');
        $totalRegistros = $mermas->count();
        $promedioDiario = $totalRegistros > 0 ? $totalMermas / $totalRegistros : 0;

        // Análisis por causa
        $mermasPorCausa = $mermas->groupBy('causa_merma')->map(function ($group) use ($totalMermas) {
            $cantidadTotal = $group->sum('cantidad_perdida');
            return [
                'causa_merma' => $group->first()->causa_merma,
                'cantidad_total' => $cantidadTotal,
                'porcentaje' => $totalMermas > 0 ? ($cantidadTotal / $totalMermas) * 100 : 0,
                'costo_total' => $group->sum('costo_perdida'),
                'total_registros' => $group->count()
            ];
        })->values();

        // Análisis por galpón
        $mermasPorGalpon = $mermas->groupBy('galpon_id')->map(function ($group) use ($totalMermas) {
            $cantidadTotal = $group->sum('cantidad_perdida');
            return [
                'galpon_id' => $group->first()->galpon_id,
                'galpon' => $group->first()->galpon,
                'cantidad_total' => $cantidadTotal,
                'porcentaje' => $totalMermas > 0 ? ($cantidadTotal / $totalMermas) * 100 : 0,
                'total_registros' => $group->count(),
                'costo_total' => $group->sum('costo_perdida')
            ];
        })->values();

        $data = [
            'mermas' => $mermas,
            'total_mermas' => $totalMermas,
            'costo_total' => $costoTotal,
            'total_registros' => $totalRegistros,
            'promedio_diario' => $promedioDiario,
            'mermas_por_causa' => $mermasPorCausa,
            'mermas_por_galpon' => $mermasPorGalpon
        ];

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('avicontrol::admin.food_waste.reporte', compact('mermas', 'galpones', 'causasMerma', 'data'));
    }

    /**
     * Obtener estadísticas de mermas
     */
    public function estadisticas(Request $request)
    {
        $query = FoodWaste::with(['galpon', 'producto']);

        // Aplicar filtros
        if ($request->filled('galpon_id')) {
            $query->where('galpon_id', $request->galpon_id);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_registro', [$request->fecha_inicio, $request->fecha_fin]);
        }

        // Obtener datos base
        $mermas = $query->get();

        // Calcular estadísticas
        $estadisticas = [
            'total_mermas' => $mermas->sum('cantidad_perdida'),
            'costo_total_mermas' => $mermas->sum('costo_perdida'),
            'total_registros' => $mermas->count(),
            'promedio_diario' => $mermas->count() > 0 ? $mermas->sum('cantidad_perdida') / $mermas->count() : 0,
            'porcentaje_mermas' => 0, // Se calculará si hay datos de consumo
            'mermas_por_causa' => $mermas->groupBy('causa_merma')->map(function ($group) {
                return [
                    'causa_merma' => $group->first()->causa_merma,
                    'cantidad_total' => $group->sum('cantidad_perdida'),
                    'costo_total' => $group->sum('costo_perdida'),
                    'total_registros' => $group->count()
                ];
            })->values(),
            'mermas_por_galpon' => $mermas->groupBy('galpon_id')->map(function ($group) {
                return [
                    'galpon_id' => $group->first()->galpon_id,
                    'galpon' => $group->first()->galpon,
                    'cantidad_total' => $group->sum('cantidad_perdida'),
                    'costo_total' => $group->sum('costo_perdida'),
                    'total_registros' => $group->count()
                ];
            })->values(),
            'mermas_por_producto' => $mermas->groupBy('producto_id')->map(function ($group) {
                return [
                    'producto_id' => $group->first()->producto_id,
                    'producto' => $group->first()->producto,
                    'cantidad_total' => $group->sum('cantidad_perdida'),
                    'costo_total' => $group->sum('costo_perdida'),
                    'total_registros' => $group->count()
                ];
            })->values(),
            'evolucion_temporal' => $mermas->groupBy(function ($merma) {
                return \Carbon\Carbon::parse($merma->fecha_registro)->format('Y-m');
            })->map(function ($group) {
                return [
                    'fecha' => $group->first()->fecha_registro ? \Carbon\Carbon::parse($group->first()->fecha_registro)->format('M Y') : 'N/A',
                    'cantidad' => $group->sum('cantidad_perdida'),
                    'costo' => $group->sum('costo_perdida'),
                    'registros' => $group->count()
                ];
            })->values()
        ];

        // Calcular porcentaje de mermas si hay datos de consumo
        if ($estadisticas['total_mermas'] > 0) {
            $totalConsumo = FoodConsumption::sum('cantidad_kg');
            if ($totalConsumo > 0) {
                $estadisticas['porcentaje_mermas'] = ($estadisticas['total_mermas'] / $totalConsumo) * 100;
            }
        }

        // Si es una petición AJAX, devolver JSON
        if ($request->wantsJson()) {
            return response()->json($estadisticas);
        }

        // Si no es AJAX, devolver la vista con datos iniciales
        $galpones = PoultryFacility::all();
        return view('avicontrol::admin.food_waste.estadisticas', compact('estadisticas', 'galpones'));
    }

    /**
     * Exportar reporte de mermas
     */
    public function exportar(Request $request)
    {
        $galponId = $request->get('galpon_id');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $causa = $request->get('causa_merma');

        $mermas = FoodWaste::generarReporteMermas($galponId, $fechaInicio, $fechaFin, $causa);

        // Aquí se implementaría la lógica de exportación (PDF, Excel, etc.)
        return response()->json(['message' => 'Exportación implementada', 'data' => $mermas]);
    }

    /**
     * Mostrar el panel de control del módulo de alimentación
     */
    public function dashboard()
    {
        // Estadísticas de consumo
        $totalConsumo = FoodConsumption::sum('cantidad_kg');
        $promedioConsumoDiario = FoodConsumption::where('fecha_registro', '>=', now()->subDays(30))->avg('cantidad_kg');
        $consumoEsteMes = FoodConsumption::where('fecha_registro', '>=', now()->startOfMonth())->sum('cantidad_kg');
        
        // Estadísticas de conversión
        $mejorConversion = FoodConversion::orderBy('conversion_alimenticia', 'asc')->first();
        $promedioConversion = FoodConversion::avg('conversion_alimenticia');
        $conversionesEsteMes = FoodConversion::where('fecha_inicio', '>=', now()->startOfMonth())->count();
        
        // Estadísticas de mermas
        $totalMermas = FoodWaste::sum('cantidad_perdida');
        $costoTotalMermas = FoodWaste::sum('costo_perdida');
        $porcentajeMermas = $totalConsumo > 0 ? ($totalMermas / $totalConsumo) * 100 : 0;
        
        // Mermas por causa
        $mermasPorCausa = FoodWaste::selectRaw('causa_merma, COUNT(*) as total, SUM(cantidad_perdida) as cantidad_total')
            ->groupBy('causa_merma')
            ->orderBy('cantidad_total', 'desc')
            ->get();
        
        // Consumo por galpón
        $consumoPorGalpon = FoodConsumption::selectRaw('galpon_id, SUM(cantidad_kg) as total_consumo')
            ->with('galpon')
            ->groupBy('galpon_id')
            ->orderBy('total_consumo', 'desc')
            ->get();
        
        // Últimos registros
        $ultimosConsumos = FoodConsumption::with(['galpon', 'producto'])
            ->orderBy('fecha_registro', 'desc')
            ->limit(5)
            ->get();
            
        $ultimasMermas = FoodWaste::with(['galpon', 'producto'])
            ->orderBy('fecha_registro', 'desc')
            ->limit(5)
            ->get();
        
        // Alertas
        $alertas = [];
        
        // Alerta por alto consumo
        if ($promedioConsumoDiario > 1000) {
            $alertas[] = [
                'tipo' => 'warning',
                'mensaje' => 'El consumo diario promedio está por encima del límite recomendado.',
                'icono' => 'fas fa-exclamation-triangle'
            ];
        }
        
        // Alerta por alta merma
        if ($porcentajeMermas > 5) {
            $alertas[] = [
                'tipo' => 'danger',
                'mensaje' => 'El porcentaje de mermas está por encima del 5%.',
                'icono' => 'fas fa-times-circle'
            ];
        }
        
        // Alerta por baja conversión
        if ($promedioConversion > 3) {
            $alertas[] = [
                'tipo' => 'warning',
                'mensaje' => 'La conversión alimenticia está por encima del valor óptimo.',
                'icono' => 'fas fa-chart-line'
            ];
        }

        return view('avicontrol::admin.food.dashboard', compact(
            'totalConsumo',
            'promedioConsumoDiario',
            'consumoEsteMes',
            'mejorConversion',
            'promedioConversion',
            'conversionesEsteMes',
            'totalMermas',
            'costoTotalMermas',
            'porcentajeMermas',
            'mermasPorCausa',
            'consumoPorGalpon',
            'ultimosConsumos',
            'ultimasMermas',
            'alertas'
        ));
    }
}
