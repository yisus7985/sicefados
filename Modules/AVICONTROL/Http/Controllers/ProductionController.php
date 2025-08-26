<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Modules\AVICONTROL\Entities\Production;
use Modules\AVICONTROL\Entities\Galpon;
use Modules\AVICONTROL\Entities\Bird;
use Modules\AVICONTROL\Entities\ProductionWeek;
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
        $semanas = ProductionWeek::activa()
            ->orderBy('fecha_inicio', 'desc')
            ->pluck('nombre')
            ->values();

        // Obtener galpones disponibles para el filtro (TODOS para mostrar en filtros)
        try {
            $galpones = Galpon::orderBy('name')->get();
        } catch (\Exception $e) {
            \Log::error('Error en ProductionController@index: ' . $e->getMessage());
            
            // Fallback: obtener todos los galpones sin filtrar por status
            $galpones = Galpon::whereNull('deleted_at')->orderBy('name')->get();
        }

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
        $semanas = ProductionWeek::activa()
            ->orderBy('fecha_inicio', 'desc')
            ->pluck('nombre')
            ->values();

        // Obtener galpones según el tipo de producción
        $tipoProduccion = $request->get('tipo_produccion', 'huevos');
        
        try {
            // Verificar primero que la columna existe
            $hasColumn = \Schema::hasColumn('avicontrol_poultry_facilities', 'tipo');
            if (!$hasColumn) {
                throw new \Exception('La columna tipo no existe en la tabla avicontrol_poultry_facilities');
            }
            
            // Obtener galpones del tipo correcto (sin filtrar por status para mostrar todos)
            if ($tipoProduccion === 'huevos') {
                $galpones = Galpon::where('tipo', 'gallinas_ponedoras')->orderBy('name')->get();
            } elseif ($tipoProduccion === 'carne') {
                $galpones = Galpon::where('tipo', 'pollos_engorde')->orderBy('name')->get();
            } else {
                // Fallback: obtener todos los galpones
                $galpones = Galpon::orderBy('name')->get();
            }
            
            // Si no hay galpones, crear uno de prueba
            if ($galpones->count() == 0) {
                \Log::info('No hay galpones. Creando galpón de prueba...');
                
                $galponPrueba = Galpon::create([
                    'name' => 'Galpón Prueba - ' . ucfirst($tipoProduccion),
                    'tipo' => $tipoProduccion === 'huevos' ? 'gallinas_ponedoras' : 'pollos_engorde',
                    'status' => 'active',
                    'length' => 50,
                    'width' => 10,
                    'capacity' => 1000
                ]);
                
                $galpones = collect([$galponPrueba]);
                \Log::info('Galpón de prueba creado:', ['id' => $galponPrueba->id, 'nombre' => $galponPrueba->name]);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error en ProductionController@create:', ['error' => $e->getMessage()]);
            
            // Fallback: obtener todos los galpones sin filtrar
            $galpones = Galpon::whereNull('deleted_at')->orderBy('name')->get();
            
            // Si aún no hay galpones, crear uno básico
            if ($galpones->count() == 0) {
                try {
                    $galponPrueba = Galpon::create([
                        'name' => 'Galpón Prueba',
                        'tipo' => 'gallinas_ponedoras',
                        'status' => 'active',
                        'length' => 50,
                        'width' => 10,
                        'capacity' => 1000
                    ]);
                    $galpones = collect([$galponPrueba]);
                    \Log::info('Galpón de prueba básico creado:', ['id' => $galponPrueba->id]);
                } catch (\Exception $e2) {
                    \Log::error('Error creando galpón de prueba:', ['error' => $e2->getMessage()]);
                }
            }
        }

        return view('avicontrol::admin.production.create', compact('tipos', 'semanas', 'galpones', 'tipoProduccion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log de inicio del método
        \Log::info('=== INICIO DEL MÉTODO STORE ===');
        \Log::info('Datos recibidos en store:', $request->all());
        \Log::info('Método HTTP:', ['method' => $request->method()]);
        \Log::info('URL:', ['url' => $request->url()]);
        \Log::info('Token CSRF presente:', ['has_token' => $request->has('_token')]);
        
        // PRUEBA SIMPLE: Crear registro básico sin validación compleja
        try {
            \Log::info('Iniciando prueba simple de creación...');
            
            DB::beginTransaction();
            
            // Datos mínimos para la prueba
            $dataToCreate = [
                'fecha' => $request->fecha ?? now()->toDateString(),
                'tipo_produccion' => $request->tipo_produccion ?? 'huevos',
                'galpon_id' => $request->galpon_id ?? 1, // Usar ID 1 como prueba
                'tipo' => $request->tipo ?? 'A',
                'cantidad' => $request->cantidad ?? 1,
                'mortalidad_aves' => $request->mortalidad_aves ?? 0,
                'huevos_rotos' => $request->huevos_rotos ?? 0,
                'huevos_sucios' => $request->huevos_sucios ?? 0,
                'valor_unidad' => $request->valor_unidad ?? 100,
                'valor_total' => ($request->cantidad ?? 1) * ($request->valor_unidad ?? 100),
                'destino' => $request->destino ?? 'Prueba',
                'firma_recibido' => $request->firma_recibido ?? 'Prueba',
                'semana_produccion' => $request->semana_produccion, // ¡AQUÍ ESTABA EL PROBLEMA!
                'firma_lider' => $request->firma_lider,
                'observaciones' => $request->observaciones,
                'estado' => 'activo'
            ];
            
            \Log::info('Datos a crear (prueba simple):', $dataToCreate);
            
            $production = Production::create($dataToCreate);
            \Log::info('Production::create() ejecutado exitosamente. ID:', ['id' => $production->id]);
            
            DB::commit();
            \Log::info('Transacción completada exitosamente');
            
            return redirect()->route('avicontrol.admin.production.index')
                ->with('success', 'Registro de producción creado exitosamente (PRUEBA)');
                
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error en store (prueba simple):', ['error' => $e->getMessage()]);
            \Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el registro: ' . $e->getMessage()])
                ->withInput();
        }
        
        /* CÓDIGO ORIGINAL COMENTADO PARA PRUEBA
        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date|before_or_equal:today',
            'tipo_produccion' => 'required|in:huevos,carne',
            'galpon_id' => 'required|exists:avicontrol_poultry_facilities,id',
            'tipo' => $request->tipo_produccion === 'huevos' ? 'required|in:A,AA,B,C,D' : 'nullable',
            'cantidad' => 'required|integer|min:1',
            'mortalidad_aves' => $request->tipo_produccion === 'huevos' ? 'nullable|integer|min:0' : 'nullable',
            'huevos_rotos' => $request->tipo_produccion === 'huevos' ? 'nullable|integer|min:0' : 'nullable',
            'huevos_sucios' => $request->tipo_produccion === 'huevos' ? 'nullable|integer|min:0' : 'nullable',
            'peso_promedio' => $request->tipo_produccion === 'carne' ? 'nullable|numeric|min:0' : 'nullable',
            'peso_total' => $request->tipo_produccion === 'carne' ? 'nullable|numeric|min:0' : 'nullable',
            'fecha_sacrificio' => $request->tipo_produccion === 'carne' ? 'nullable|date' : 'nullable',
            'responsable_sacrificio' => $request->tipo_produccion === 'carne' ? 'nullable|string|max:100' : 'nullable',
            'valor_unidad' => 'required|numeric|min:0',
            'destino' => 'required|string|max:100',
            'observaciones' => 'nullable|string|max:500',
            'firma_recibido' => 'required|string|max:100',
            'semana_produccion' => $request->tipo_produccion === 'huevos' ? 'nullable|string|max:50' : 'nullable',
            'firma_lider' => $request->tipo_produccion === 'huevos' ? 'nullable|string|max:100' : 'nullable'
        ]);

        \Log::info('Validación completada. Errores:', $validator->errors()->toArray());

        if ($validator->fails()) {
            \Log::warning('Validación falló. Redirigiendo con errores...');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        \Log::info('Validación exitosa. Iniciando creación...');

        try {
            // Debug: Log de los datos recibidos
            \Log::info('Datos recibidos en store:', $request->all());
            
            // Debug: Verificar validación
            \Log::info('Iniciando validación...');
            
            DB::beginTransaction();

            // Debug: Verificar galpón
            \Log::info('Buscando galpón ID:', ['galpon_id' => $request->galpon_id]);
            
            // Validar que el galpón corresponda al tipo de producción
            $galpon = Galpon::find($request->galpon_id);
            if (!$galpon) {
                throw new \Exception('Galpón no encontrado');
            }
            
            \Log::info('Galpón encontrado:', ['id' => $galpon->id, 'nombre' => $galpon->name, 'tipo' => $galpon->tipo]);

            $tipoProduccionEsperado = $galpon->tipo === 'gallinas_ponedoras' ? 'huevos' : 'carne';
            if ($request->tipo_produccion !== $tipoProduccionEsperado) {
                throw new \Exception('El tipo de producción no corresponde al tipo de galpón seleccionado');
            }
            
            \Log::info('Validación de tipo de producción exitosa');

            \Log::info('Iniciando creación del registro de producción...');
            
            // Debug: Log de los datos que se van a crear
            $dataToCreate = [
                'fecha' => $request->fecha,
                'tipo_produccion' => $request->tipo_produccion,
                'galpon_id' => $request->galpon_id,
                'tipo' => $request->tipo,
                'cantidad' => $request->cantidad,
                'mortalidad_aves' => $request->mortalidad_aves ?? 0,
                'huevos_rotos' => $request->huevos_rotos ?? 0,
                'huevos_sucios' => $request->huevos_sucios ?? 0,
                'peso_promedio' => $request->peso_promedio,
                'peso_total' => $request->peso_total,
                'fecha_sacrificio' => $request->fecha_sacrificio,
                'responsable_sacrificio' => $request->responsable_sacrificio,
                'valor_unidad' => $request->valor_unidad,
                'valor_total' => $request->cantidad * $request->valor_unidad,
                'destino' => $request->destino,
                'observaciones' => $request->observaciones,
                'firma_recibido' => $request->firma_recibido,
                'semana_produccion' => $request->semana_produccion,
                'firma_lider' => $request->firma_lider,
                'estado' => 'activo'
            ];
            
            try {
                $production = Production::create($dataToCreate);
                \Log::info('Production::create() ejecutado exitosamente');
                
            } catch (\Exception $e) {
                \Log::error('Error en Production::create():', ['error' => $e->getMessage()]);
                \Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
                throw $e;
            }
            
            \Log::info('Registro de producción creado exitosamente:', ['id' => $production->id, 'fecha' => $production->fecha]);

            // Actualizar automáticamente el galpón si hay mortalidad
            if ($request->mortalidad_aves > 0) {
                $this->actualizarMortalidadGalpon($request->galpon_id, $request->mortalidad_aves);
            }

            DB::commit();
            
            \Log::info('Transacción completada exitosamente');

            return redirect()->route('avicontrol.admin.production.index')
                ->with('success', 'Registro de producción creado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error en store:', ['error' => $e->getMessage()]);
            \Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear el registro: ' . $e->getMessage()])
                ->withInput();
        }
        */
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
        $semanas = ProductionWeek::activa()
            ->orderBy('fecha_inicio', 'desc')
            ->pluck('nombre')
            ->values();

        // Obtener galpones según el tipo de producción
        try {
            // Obtener galpones del tipo correcto (sin filtrar por status para mostrar todos)
            if ($production->tipo_produccion === 'huevos') {
                $galpones = Galpon::where('tipo', 'gallinas_ponedoras')->orderBy('name')->get();
            } elseif ($production->tipo_produccion === 'carne') {
                $galpones = Galpon::where('tipo', 'pollos_engorde')->orderBy('name')->get();
            } else {
                // Fallback: obtener todos los galpones
                $galpones = Galpon::orderBy('name')->get();
            }
            
        } catch (\Exception $e) {
            \Log::error('Error en ProductionController@edit: ' . $e->getMessage());
            
            // Fallback: obtener todos los galpones sin filtrar
            $galpones = Galpon::whereNull('deleted_at')->orderBy('name')->get();
        }

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
            'tipo' => $request->tipo_produccion === 'huevos' ? 'required|in:A,AA,B,C,D' : 'nullable',
            'cantidad' => 'required|integer|min:1',
            'mortalidad_aves' => $request->tipo_produccion === 'huevos' ? 'nullable|integer|min:0' : 'nullable',
            'huevos_rotos' => $request->tipo_produccion === 'huevos' ? 'nullable|integer|min:0' : 'nullable',
            'huevos_sucios' => $request->tipo_produccion === 'huevos' ? 'nullable|integer|min:0' : 'nullable',
            'peso_promedio' => $request->tipo_produccion === 'carne' ? 'nullable|numeric|min:0' : 'nullable',
            'peso_total' => $request->tipo_produccion === 'carne' ? 'nullable|numeric|min:0' : 'nullable',
            'fecha_sacrificio' => $request->tipo_produccion === 'carne' ? 'nullable|date' : 'nullable',
            'responsable_sacrificio' => $request->tipo_produccion === 'carne' ? 'nullable|string|max:100' : 'nullable',
            'valor_unidad' => 'required|numeric|min:0',
            'destino' => 'required|string|max:100',
            'observaciones' => 'nullable|string|max:500',
            'firma_recibido' => 'required|string|max:100',
            'semana_produccion' => $request->tipo_produccion === 'huevos' ? 'nullable|string|max:50' : 'nullable',
            'firma_lider' => $request->tipo_produccion === 'huevos' ? 'nullable|string|max:100' : 'nullable'
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
                'mortalidad_aves' => $request->mortalidad_aves ?? 0,
                'huevos_rotos' => $request->huevos_rotos ?? 0,
                'huevos_sucios' => $request->huevos_sucios ?? 0,
                'peso_promedio' => $request->peso_promedio,
                'peso_total' => $request->peso_total,
                'fecha_sacrificio' => $request->fecha_sacrificio,
                'responsable_sacrificio' => $request->responsable_sacrificio,
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
            // Usar forceDelete() para eliminar físicamente el registro
            $production->forceDelete();

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
                    // Usar forceDelete() para eliminar físicamente los registros
                    Production::whereIn('id', $ids)->forceDelete();
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



    /**
     * Crear una nueva semana de producción
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeWeek(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:100',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'descripcion' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar si ya existe una semana con el mismo nombre
            $semanaExistente = ProductionWeek::where('nombre', $request->nombre)->first();
            if ($semanaExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe una semana de producción con ese nombre'
                ], 409);
            }

            // Crear la semana de producción en la nueva tabla
            $semana = ProductionWeek::create([
                'nombre' => $request->nombre,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'descripcion' => $request->descripcion,
                'estado' => 'activa'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Semana de producción creada correctamente',
                'semana' => $semana
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al crear semana de producción: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor al crear la semana de producción'
            ], 500);
        }
    }
}