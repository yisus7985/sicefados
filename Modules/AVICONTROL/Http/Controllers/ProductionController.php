<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\AVICONTROL\Entities\Production;
use Carbon\Carbon;
use Dompdf\Facade as PDF;

class ProductionController extends Controller
{
    /**
     * Constructor to bypass authorization for testing
     */
    public function __construct()
    {
        // Uncomment this line if you want to bypass authorization checks
        // $this->middleware('auth')->except(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Production::query();

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('semana_produccion')) {
            $query->where('semana_produccion', $request->semana_produccion);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        $productions = $query->orderBy('fecha', 'desc')
            ->orderBy('tipo', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Obtener semanas disponibles para el filtro
        $semanas = Production::distinct()
            ->whereNotNull('semana_produccion')
            ->pluck('semana_produccion')
            ->sort()
            ->values();

        // Estadísticas generales
        $stats = Production::getEstadisticasGenerales($request->semana_produccion);

        return view('avicontrol::admin.production.index', compact(
            'productions',
            'semanas',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = ['A', 'AA', 'B', 'C', 'D'];
        $semanas = Production::distinct()
            ->whereNotNull('semana_produccion')
            ->pluck('semana_produccion')
            ->sort()
            ->values();

        return view('avicontrol::admin.production.create', compact('tipos', 'semanas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date|before_or_equal:today',
            'tipo' => 'required|in:A,AA,B,C,D',
            'cantidad' => 'required|integer|min:1',
            'valor_unidad' => 'required|numeric|min:0',
            'destino' => 'required|string|max:100',
            'observaciones' => 'nullable|string|max:500',
            'firma_recibido' => 'required|string|max:100',
            'semana_produccion' => 'nullable|string|max:50',
            'firma_lider' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $production = Production::create([
                // Nuevas columnas
                'fecha' => $request->fecha,
                'tipo' => $request->tipo,
                'cantidad' => $request->cantidad,
                'valor_unidad' => $request->valor_unidad,
                'valor_total' => $request->cantidad * $request->valor_unidad,
                'destino' => $request->destino,
                'observaciones' => $request->observaciones,
                'firma_recibido' => $request->firma_recibido,
                'semana_produccion' => $request->semana_produccion,
                'firma_lider' => $request->firma_lider,
                'estado' => 'activo',
                
                // Columnas antiguas requeridas (valores por defecto)
                'poultry_facility_id' => 1, // ID por defecto
                'bird_id' => 1, // ID por defecto
                'batch_code' => 'BATCH-' . date('Ymd-His'),
                'production_date' => $request->fecha,
                'egg_type_a' => $request->tipo === 'A' ? $request->cantidad : 0,
                'egg_type_aa' => $request->tipo === 'AA' ? $request->cantidad : 0,
                'egg_type_b' => $request->tipo === 'B' ? $request->cantidad : 0,
                'egg_type_c' => $request->tipo === 'C' ? $request->cantidad : 0,
                'egg_type_d' => $request->tipo === 'D' ? $request->cantidad : 0,
                'egg_count_total' => $request->cantidad,
                'egg_weight_total' => 0,
                'egg_weight_average' => 0,
                'unit_value' => $request->valor_unidad,
                'total_value' => $request->cantidad * $request->valor_unidad,
                'destination' => $request->destino,
                'week_number' => $request->semana_produccion,
                'observations' => $request->observaciones,
                'received_by' => $request->firma_recibido,
                'status' => 'confirmed',
                'confirmed_by' => auth()->id() ?? 4,
                'confirmed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('avicontrol.admin.production.index')
                ->with('success', 'Registro de producción creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al crear el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $production = Production::findOrFail($id);
        return view('avicontrol::admin.production.show', compact('production'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $production = Production::findOrFail($id);
        $tipos = ['A', 'AA', 'B', 'C', 'D'];
        $semanas = Production::distinct()
            ->whereNotNull('semana_produccion')
            ->pluck('semana_produccion')
            ->sort()
            ->values();

        return view('avicontrol::admin.production.edit', compact('production', 'tipos', 'semanas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $production = Production::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date|before_or_equal:today',
            'tipo' => 'required|in:A,AA,B,C,D',
            'cantidad' => 'required|integer|min:1',
            'valor_unidad' => 'required|numeric|min:0',
            'destino' => 'required|string|max:100',
            'observaciones' => 'nullable|string|max:500',
            'firma_recibido' => 'required|string|max:100',
            'semana_produccion' => 'nullable|string|max:50',
            'firma_lider' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $production->update([
                // Nuevas columnas
                'fecha' => $request->fecha,
                'tipo' => $request->tipo,
                'cantidad' => $request->cantidad,
                'valor_unidad' => $request->valor_unidad,
                'valor_total' => $request->cantidad * $request->valor_unidad,
                'destino' => $request->destino,
                'observaciones' => $request->observaciones,
                'firma_recibido' => $request->firma_recibido,
                'semana_produccion' => $request->semana_produccion,
                'firma_lider' => $request->firma_lider,
                
                // Actualizar también columnas antiguas
                'production_date' => $request->fecha,
                'egg_type_a' => $request->tipo === 'A' ? $request->cantidad : 0,
                'egg_type_aa' => $request->tipo === 'AA' ? $request->cantidad : 0,
                'egg_type_b' => $request->tipo === 'B' ? $request->cantidad : 0,
                'egg_type_c' => $request->tipo === 'C' ? $request->cantidad : 0,
                'egg_type_d' => $request->tipo === 'D' ? $request->cantidad : 0,
                'egg_count_total' => $request->cantidad,
                'unit_value' => $request->valor_unidad,
                'total_value' => $request->cantidad * $request->valor_unidad,
                'destination' => $request->destino,
                'week_number' => $request->semana_produccion,
                'observations' => $request->observaciones,
                'received_by' => $request->firma_recibido
            ]);

            DB::commit();

            return redirect()->route('avicontrol.admin.production.index')
                ->with('success', 'Registro de producción actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al actualizar el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $production = Production::findOrFail($id);
            $production->delete();

            return redirect()->route('avicontrol.admin.production.index')
                ->with('success', 'Registro de producción eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el registro: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status of the production record
     */
    public function toggleStatus($id)
    {
        try {
            $production = Production::findOrFail($id);
            $production->estado = $production->estado === 'activo' ? 'inactivo' : 'activo';
            $production->save();

            $status = $production->estado === 'activo' ? 'activado' : 'desactivado';

            return redirect()->back()
                ->with('success', "Registro {$status} exitosamente.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard view with statistics
     */
    public function dashboard(Request $request)
    {
        $semana = $request->get('semana', null);
        
        // Estadísticas por semana
        $stats = Production::getEstadisticasGenerales($semana);
        
        // Totales por tipo de huevo
        $totalesPorTipo = [];
        $tipos = ['A', 'AA', 'B', 'C', 'D'];
        
        foreach ($tipos as $tipo) {
            $totalesPorTipo[$tipo] = Production::getTotalByTipo($tipo, $semana);
        }

        // Producción por fecha (últimos 30 días)
        $produccionPorFecha = Production::where('estado', 'activo')
            ->when($semana, function($query) use ($semana) {
                return $query->where('semana_produccion', $semana);
            })
            ->where('fecha', '>=', Carbon::now()->subDays(30))
            ->selectRaw('fecha, SUM(cantidad) as total_huevos, SUM(valor_total) as valor_total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Semanas disponibles
        $semanas = Production::distinct()
            ->whereNotNull('semana_produccion')
            ->pluck('semana_produccion')
            ->sort()
            ->values();

        return view('avicontrol::admin.production.dashboard', compact(
            'stats',
            'totalesPorTipo',
            'produccionPorFecha',
            'semanas',
            'semana'
        ));
    }

    /**
     * Generate PDF report
     */
    public function report(Request $request)
    {
        try {
            $semana = $request->get('semana', null);
            
            $productions = Production::where('estado', 'activo')
                ->when($semana, function($query) use ($semana) {
                    return $query->where('semana_produccion', $semana);
                })
                ->orderBy('fecha', 'asc')
                ->orderBy('tipo', 'asc')
                ->get();

            $stats = Production::getEstadisticasGenerales($semana);

            // Verificar si la clase PDF está disponible
            if (!class_exists('Dompdf\Facade')) {
                throw new \Exception('La clase DomPDF no está disponible. Verifique que esté configurado correctamente.');
            }

            $pdf = PDF::loadView('avicontrol::admin.production.report', compact('productions', 'stats', 'semana'));
            
            $filename = $semana ? "reporte_produccion_{$semana}.pdf" : "reporte_produccion_general.pdf";
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error generando PDF: ' . $e->getMessage());
            
            // Retornar error en lugar de 404
            return response()->json([
                'error' => 'Error generando PDF: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Test method to check if PDF generation works
     */
    public function testPdf()
    {
        try {
            // Verificar si la clase PDF está disponible
            if (!class_exists('Dompdf\Facade')) {
                return response()->json([
                    'error' => 'La clase DomPDF no está disponible',
                    'class_exists' => false
                ], 500);
            }

            // Crear un PDF simple de prueba
            $pdf = PDF::loadView('avicontrol::admin.production.report', [
                'productions' => collect([]),
                'stats' => [
                    'total_huevos' => 0,
                    'valor_total' => 0,
                    'promedio_por_dia' => 0,
                    'tipos_disponibles' => 0
                ],
                'semana' => null
            ]);
            
            return $pdf->download('test.pdf');
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error en prueba PDF: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;

        if (!$ids || !$action) {
            return redirect()->back()->with('error', 'Seleccione registros y una acción.');
        }

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'activate':
                    Production::whereIn('id', $ids)->update(['estado' => 'activo']);
                    $message = 'Registros activados exitosamente.';
                    break;
                    
                case 'deactivate':
                    Production::whereIn('id', $ids)->update(['estado' => 'inactivo']);
                    $message = 'Registros desactivados exitosamente.';
                    break;
                    
                case 'delete':
                    Production::whereIn('id', $ids)->delete();
                    $message = 'Registros eliminados exitosamente.';
                    break;
                    
                default:
                    throw new \Exception('Acción no válida.');
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error en la operación: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for AJAX requests
     */
    public function getStats(Request $request)
    {
        $semana = $request->get('semana', null);
        $stats = Production::getEstadisticasGenerales($semana);
        
        return response()->json($stats);
    }

    /**
     * Get trend data for charts
     */
    public function getTrend(Request $request)
    {
        $semana = $request->get('semana', null);
        
        $trend = Production::where('estado', 'activo')
            ->when($semana, function($query) use ($semana) {
                return $query->where('semana_produccion', $semana);
            })
            ->where('fecha', '>=', Carbon::now()->subDays(30))
            ->selectRaw('fecha, SUM(cantidad) as total_huevos, SUM(valor_total) as valor_total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
            
        return response()->json($trend);
    }

    /**
     * Get batches by facility for AJAX requests
     */
    public function getBatchesByFacility(Request $request)
    {
        $facilityId = $request->get('facility_id');
        
        if (!$facilityId) {
            return response()->json([]);
        }
        
        $batches = Production::where('poultry_facility_id', $facilityId)
            ->distinct()
            ->pluck('batch_code')
            ->filter()
            ->values();
            
        return response()->json($batches);
    }
}