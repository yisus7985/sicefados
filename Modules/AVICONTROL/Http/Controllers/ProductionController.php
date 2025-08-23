<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\AVICONTROL\Entities\Production;
use Modules\AVICONTROL\Entities\Galpon;
use Modules\AVICONTROL\Entities\Bird;
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
        if ($request->filled('tipo_produccion')) {
            $query->where('tipo_produccion', $request->tipo_produccion);
        }

        if ($request->filled('galpon_id')) {
            $query->where('galpon_id', $request->galpon_id);
        }

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

        $productions = $query->with('galpon')
            ->orderBy('fecha', 'desc')
            ->orderBy('tipo', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Obtener semanas disponibles para el filtro
        $semanas = Production::distinct()
            ->whereNotNull('semana_produccion')
            ->pluck('semana_produccion')
            ->sort()
            ->values();

        // Obtener galpones disponibles para el filtro
        $galpones = Galpon::activo()->orderBy('name')->get();

        // Estadísticas generales
        $stats = Production::getEstadisticasGenerales($request->semana_produccion, $request->tipo_produccion);

        return view('avicontrol::admin.production.index', compact(
            'productions',
            'semanas',
            'galpones',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $tipos = ['A', 'AA', 'B', 'C', 'D'];
        $semanas = Production::distinct()
            ->whereNotNull('semana_produccion')
            ->pluck('semana_produccion')
            ->sort()
            ->values();

        // Obtener galpones según el tipo de producción
        $tipoProduccion = $request->get('tipo_produccion', 'huevos');
        $galpones = Galpon::activo()
            ->when($tipoProduccion === 'huevos', function($query) {
                return $query->gallinasPonedoras();
            })
            ->when($tipoProduccion === 'carne', function($query) {
                return $query->pollosEngorde();
            })
            ->orderBy('name')
            ->get();

        return view('avicontrol::admin.production.create', compact('tipos', 'semanas', 'galpones', 'tipoProduccion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date|before_or_equal:today',
            'tipo_produccion' => 'required|in:huevos,carne',
            'galpon_id' => 'required|exists:avicontrol_poultry_facilities,id',
            'tipo' => 'required|in:A,AA,B,C,D',
            'cantidad' => 'required|integer|min:1',
            'mortalidad_aves' => 'nullable|integer|min:0',
            'peso_promedio' => 'nullable|numeric|min:0',
            'peso_total' => 'nullable|numeric|min:0',
            'huevos_rotos' => 'nullable|integer|min:0',
            'huevos_sucios' => 'nullable|integer|min:0',
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

            // Validar que el galpón corresponda al tipo de producción
            $galpon = Galpon::find($request->galpon_id);
            if (!$galpon) {
                throw new \Exception('Galpón no encontrado');
            }

            $tipoProduccionEsperado = $galpon->tipo === 'gallinas_ponedoras' ? 'huevos' : 'carne';
            if ($request->tipo_produccion !== $tipoProduccionEsperado) {
                throw new \Exception('El tipo de producción no corresponde al tipo de galpón seleccionado');
            }

            $production = Production::create([
                'fecha' => $request->fecha,
                'tipo_produccion' => $request->tipo_produccion,
                'galpon_id' => $request->galpon_id,
                'tipo' => $request->tipo,
                'cantidad' => $request->cantidad,
                'mortalidad_aves' => $request->mortalidad_aves ?? 0,
                'peso_promedio' => $request->peso_promedio,
                'peso_total' => $request->peso_total,
                'huevos_rotos' => $request->huevos_rotos ?? 0,
                'huevos_sucios' => $request->huevos_sucios ?? 0,
                'valor_unidad' => $request->valor_unidad,
                'valor_total' => $request->cantidad * $request->valor_unidad,
                'destino' => $request->destino,
                'observaciones' => $request->observaciones,
                'firma_recibido' => $request->firma_recibido,
                'semana_produccion' => $request->semana_produccion,
                'firma_lider' => $request->firma_lider,
                'estado' => 'activo'
            ]);

            // Actualizar automáticamente el galpón si hay mortalidad
            if ($request->mortalidad_aves > 0) {
                $this->actualizarMortalidadGalpon($request->galpon_id, $request->mortalidad_aves);
            }

            DB::commit();

            return redirect()->route('avicontrol.admin.production.index')
                ->with('success', 'Registro de producción creado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el registro: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $production = Production::with('galpon')->findOrFail($id);
        
        return view('avicontrol::admin.production.show', compact('production'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $production = Production::with('galpon')->findOrFail($id);
        $tipos = ['A', 'AA', 'B', 'C', 'D'];
        $semanas = Production::distinct()
            ->whereNotNull('semana_produccion')
            ->pluck('semana_produccion')
            ->sort()
            ->values();

        // Obtener galpones según el tipo de producción
        $galpones = Galpon::activo()
            ->when($production->tipo_produccion === 'huevos', function($query) {
                return $query->gallinasPonedoras();
            })
            ->when($production->tipo_produccion === 'carne', function($query) {
                return $query->pollosEngorde();
            })
            ->orderBy('name')
            ->get();

        return view('avicontrol::admin.production.edit', compact('production', 'tipos', 'semanas', 'galpones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $production = Production::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date|before_or_equal:today',
            'tipo_produccion' => 'required|in:huevos,carne',
            'galpon_id' => 'required|exists:avicontrol_poultry_facilities,id',
            'tipo' => 'required|in:A,AA,B,C,D',
            'cantidad' => 'required|integer|min:1',
            'peso_promedio' => 'nullable|numeric|min:0',
            'peso_total' => 'nullable|numeric|min:0',
            'huevos_rotos' => 'nullable|integer|min:0',
            'huevos_sucios' => 'nullable|integer|min:0',
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

            // Validar que el galpón corresponda al tipo de producción
            $galpon = Galpon::find($request->galpon_id);
            if (!$galpon) {
                throw new \Exception('Galpón no encontrado');
            }

            $tipoProduccionEsperado = $galpon->tipo === 'gallinas_ponedoras' ? 'huevos' : 'carne';
            if ($request->tipo_produccion !== $tipoProduccionEsperado) {
                throw new \Exception('El tipo de producción no corresponde al tipo de galpón seleccionado');
            }

            $production->update([
                'fecha' => $request->fecha,
                'tipo_produccion' => $request->tipo_produccion,
                'galpon_id' => $request->galpon_id,
                'tipo' => $request->tipo,
                'cantidad' => $request->cantidad,
                'peso_promedio' => $request->peso_promedio,
                'peso_total' => $request->peso_total,
                'huevos_rotos' => $request->huevos_rotos ?? 0,
                'huevos_sucios' => $request->huevos_sucios ?? 0,
                'valor_unidad' => $request->valor_unidad,
                'valor_total' => $request->cantidad * $request->valor_unidad,
                'destino' => $request->destino,
                'observaciones' => $request->observaciones,
                'firma_recibido' => $request->firma_recibido,
                'semana_produccion' => $request->semana_produccion,
                'firma_lider' => $request->firma_lider
            ]);

            DB::commit();

            return redirect()->route('avicontrol.admin.production.index')
                ->with('success', 'Registro de producción actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Error al actualizar el registro: ' . $e->getMessage()])
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
                ->with('success', 'Registro de producción eliminado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al eliminar el registro: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle status of production record
     */
    public function toggleStatus($id)
    {
        try {
            $production = Production::findOrFail($id);
            $production->estado = $production->estado === 'activo' ? 'inactivo' : 'activo';
            $production->save();

            return redirect()->back()
                ->with('success', 'Estado del registro actualizado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al cambiar el estado: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk actions for production records
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $ids = explode(',', $request->ids);
        $action = $request->action;

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'activate':
                    Production::whereIn('id', $ids)->update(['estado' => 'activo']);
                    $message = 'Registros activados exitosamente';
                    break;
                case 'deactivate':
                    Production::whereIn('id', $ids)->update(['estado' => 'inactivo']);
                    $message = 'Registros desactivados exitosamente';
                    break;
                case 'delete':
                    Production::whereIn('id', $ids)->delete();
                    $message = 'Registros eliminados exitosamente';
                    break;
            }

            DB::commit();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'Error en la acción masiva: ' . $e->getMessage()]);
        }
    }

    /**
     * Show dashboard
     */
    public function dashboard(Request $request)
    {
        $tipoProduccion = $request->get('tipo_produccion', 'huevos');
        
        // Estadísticas por tipo de producción
        $stats = Production::getEstadisticasGenerales(null, $tipoProduccion);
        
        // Datos para gráficos
        $produccionPorSemana = Production::selectRaw('semana_produccion, SUM(cantidad) as total')
            ->where('tipo_produccion', $tipoProduccion)
            ->where('estado', 'activo')
            ->whereNotNull('semana_produccion')
            ->groupBy('semana_produccion')
            ->orderBy('semana_produccion')
            ->get();

        $produccionPorTipo = Production::selectRaw('tipo, SUM(cantidad) as total')
            ->where('tipo_produccion', $tipoProduccion)
            ->where('estado', 'activo')
            ->groupBy('tipo')
            ->orderBy('tipo')
            ->get();

        return view('avicontrol::admin.production.dashboard', compact(
            'stats',
            'produccionPorSemana',
            'produccionPorTipo',
            'tipoProduccion'
        ));
    }

    /**
     * Generate PDF report
     */
    public function report(Request $request)
    {
        $query = Production::query();

        // Aplicar filtros
        if ($request->filled('tipo_produccion')) {
            $query->where('tipo_produccion', $request->tipo_produccion);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        $productions = $query->with('galpon')
            ->orderBy('fecha', 'desc')
            ->get();

        $pdf = PDF::loadView('avicontrol::admin.production.report', compact('productions'));
        
        return $pdf->download('reporte_produccion_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Actualiza automáticamente la mortalidad en el galpón
     * @param int $galponId
     * @param int $mortalidad
     */
    private function actualizarMortalidadGalpon($galponId, $mortalidad)
    {
        try {
            // Buscar el galpón
            $galpon = Galpon::find($galponId);
            if (!$galpon) {
                \Log::warning("No se pudo encontrar el galpón ID: {$galponId} para actualizar mortalidad");
                return;
            }

            // Buscar el registro de aves asociado al galpón
            $bird = Bird::where('galpon_id', $galponId)
                       ->orWhere('poultry_facility_id', $galponId)
                       ->where('status', 'active')
                       ->first();

            if ($bird) {
                // Actualizar la cantidad de aves restando la mortalidad
                $nuevaCantidad = max(0, $bird->quantity - $mortalidad);
                $bird->update([
                    'quantity' => $nuevaCantidad,
                    'mortality_rate' => $bird->mortality_rate + $mortalidad
                ]);

                \Log::info("Mortalidad actualizada en galpón {$galponId}: {$mortalidad} aves. Nueva cantidad: {$nuevaCantidad}");
            } else {
                \Log::warning("No se encontró registro de aves activas para el galpón ID: {$galponId}");
            }

        } catch (\Exception $e) {
            \Log::error("Error al actualizar mortalidad en galpón {$galponId}: " . $e->getMessage());
        }
    }
}