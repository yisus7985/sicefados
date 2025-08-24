<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AVICONTROL\Entities\FoodConsumption;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Modules\AVICONTROL\Entities\InventoryProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FoodConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FoodConsumption::with(['galpon', 'producto']);

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

        $consumos = $query->orderBy('fecha_registro', 'desc')->paginate(15);
        $galpones = PoultryFacility::all();
        $productos = InventoryProduct::where('category', 'alimentos')->active()->get();

        return view('avicontrol::admin.food_consumption.index', compact('consumos', 'galpones', 'productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $galpones = PoultryFacility::with('activeBirds')->get();
        $productos = InventoryProduct::where('category', 'alimentos')->active()->get();
        
        return view('avicontrol::admin.food_consumption.create', compact('galpones', 'productos'));
    }

    /**
     * Obtener el número de aves activas de un galpón específico
     */
    public function getBirdsCount(Request $request)
    {
        $request->validate([
            'galpon_id' => 'required|exists:avicontrol_poultry_facilities,id'
        ]);

        $galpon = PoultryFacility::with('activeBirds')->find($request->galpon_id);
        $totalBirds = $galpon->total_active_birds;

        return response()->json([
            'success' => true,
            'total_birds' => $totalBirds,
            'message' => "El galpón {$galpon->name} tiene {$totalBirds} aves activas"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha_registro' => 'required|date',
            'galpon_id' => 'required|exists:avicontrol_poultry_facilities,id',
            'producto_id' => 'required|exists:avicontrol_inventory_products,id',
            'cantidad_kg' => 'nullable|numeric|min:0',
            'cantidad_bultos' => 'nullable|integer|min:0',
            'peso_por_bulto' => 'nullable|numeric|min:0',
            'numero_aves' => 'required|integer|min:1',
            'observaciones' => 'nullable|string|max:500'
        ]);

        // Validar que se proporcione al menos una forma de cantidad
        if (!$request->cantidad_kg && (!$request->cantidad_bultos || !$request->peso_por_bulto)) {
            return back()->withErrors(['cantidad' => 'Debe proporcionar la cantidad en kg o en bultos con peso por bulto.']);
        }

        // Validar stock disponible
        $producto = InventoryProduct::find($request->producto_id);
        $cantidadTotal = $request->cantidad_kg ?? ($request->cantidad_bultos * $request->peso_por_bulto);
        
        if ($producto->current_stock < $cantidadTotal) {
            return back()->withErrors(['stock' => 'Stock insuficiente. Disponible: ' . $producto->current_stock . ' kg']);
        }

        DB::beginTransaction();
        try {
            $consumo = FoodConsumption::create([
                'fecha_registro' => $request->fecha_registro,
                'galpon_id' => $request->galpon_id,
                'producto_id' => $request->producto_id,
                'cantidad_kg' => $request->cantidad_kg,
                'cantidad_bultos' => $request->cantidad_bultos,
                'peso_por_bulto' => $request->peso_por_bulto,
                'numero_aves' => $request->numero_aves,
                'observaciones' => $request->observaciones,
                'responsable' => Auth::user()->name ?? 'Sistema',
                'estado' => FoodConsumption::STATUS_ACTIVE
            ]);

            // Calcular consumo promedio por ave
            $consumo->calcularConsumoPromedio();

            // Actualizar inventario
            $consumo->actualizarInventario();

            DB::commit();

            return redirect()->route('avicontrol.admin.food_consumption.index')
                ->with('success', 'Consumo de alimento registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al registrar el consumo: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $consumo = FoodConsumption::with(['galpon', 'producto'])->findOrFail($id);
        
        return view('avicontrol::admin.food_consumption.show', compact('consumo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $consumo = FoodConsumption::findOrFail($id);
        $galpones = PoultryFacility::all();
        $productos = InventoryProduct::where('category', 'alimentos')->active()->get();
        
        return view('avicontrol::admin.food_consumption.edit', compact('consumo', 'galpones', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $consumo = FoodConsumption::findOrFail($id);
        
        $request->validate([
            'fecha_registro' => 'required|date',
            'galpon_id' => 'required|exists:avicontrol_poultry_facilities,id',
            'producto_id' => 'required|exists:avicontrol_inventory_products,id',
            'cantidad_kg' => 'nullable|numeric|min:0',
            'cantidad_bultos' => 'nullable|integer|min:0',
            'peso_por_bulto' => 'nullable|numeric|min:0',
            'numero_aves' => 'required|integer|min:1',
            'observaciones' => 'nullable|string|max:500'
        ]);

        // Validar que se proporcione al menos una forma de cantidad
        if (!$request->cantidad_kg && (!$request->cantidad_bultos || !$request->peso_por_bulto)) {
            return back()->withErrors(['cantidad' => 'Debe proporcionar la cantidad en kg o en bultos con peso por bulto.']);
        }

        DB::beginTransaction();
        try {
            // Revertir inventario anterior si es necesario
            if ($consumo->estado === FoodConsumption::STATUS_ACTIVE) {
                $consumo->revertirInventario();
            }

            $consumo->update([
                'fecha_registro' => $request->fecha_registro,
                'galpon_id' => $request->galpon_id,
                'producto_id' => $request->producto_id,
                'cantidad_kg' => $request->cantidad_kg,
                'cantidad_bultos' => $request->cantidad_bultos,
                'peso_por_bulto' => $request->peso_por_bulto,
                'numero_aves' => $request->numero_aves,
                'observaciones' => $request->observaciones
            ]);

            // Recalcular consumo promedio por ave
            $consumo->calcularConsumoPromedio();

            // Actualizar inventario con nuevos valores
            $consumo->actualizarInventario();

            DB::commit();

            return redirect()->route('avicontrol.admin.food_consumption.index')
                ->with('success', 'Consumo de alimento actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al actualizar el consumo: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $consumo = FoodConsumption::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Revertir inventario
            $consumo->revertirInventario();
            
            // Cambiar estado a cancelado en lugar de eliminar
            $consumo->update(['estado' => FoodConsumption::STATUS_CANCELLED]);
            
            DB::commit();

            return redirect()->route('avicontrol.admin.food_consumption.index')
                ->with('success', 'Consumo de alimento cancelado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al cancelar el consumo: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtener estadísticas de consumo
     */
    public function estadisticas(Request $request)
    {
        $query = FoodConsumption::with(['galpon', 'producto'])->active();

        if ($request->filled('galpon_id')) {
            $query->where('galpon_id', $request->galpon_id);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_registro', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $estadisticas = [
            'total_consumo' => $query->sum('cantidad_kg'),
            'promedio_por_ave' => $query->avg('consumo_promedio_por_ave'),
            'total_costo' => $query->join('avicontrol_inventory_products', 'food_consumption.producto_id', '=', 'avicontrol_inventory_products.id')
                ->selectRaw('SUM(food_consumption.cantidad_kg * avicontrol_inventory_products.unit_price) as total_costo')
                ->value('total_costo') ?? 0,
            'consumo_por_galpon' => $query->selectRaw('galpon_id, SUM(cantidad_kg) as total_consumo')
                ->groupBy('galpon_id')
                ->with('galpon')
                ->get()
        ];

        return response()->json($estadisticas);
    }

    /**
     * Exportar reporte de consumo
     */
    public function exportar(Request $request)
    {
        $query = FoodConsumption::with(['galpon', 'producto'])->active();

        if ($request->filled('galpon_id')) {
            $query->where('galpon_id', $request->galpon_id);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_registro', [$request->fecha_inicio, $request->fecha_fin]);
        }

        $consumos = $query->orderBy('fecha_registro', 'desc')->get();

        // Aquí se implementaría la lógica de exportación (PDF, Excel, etc.)
        return response()->json(['message' => 'Exportación implementada', 'data' => $consumos]);
    }
}
