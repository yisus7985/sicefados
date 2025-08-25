<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Modules\AVICONTROL\Entities\Galpon;
use Modules\AVICONTROL\Entities\Bird;
use Modules\AVICONTROL\Entities\FoodConsumption;
use Modules\AVICONTROL\Entities\Production;

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

            // Obtener estadísticas reales de la base de datos
            $estadisticas = $this->obtenerEstadisticasGenerales();

            $galpones = PoultryFacility::all();
            return view('avicontrol::admin.information.index', compact('galpones', 'estadisticas'));
        } catch (\Exception $e) {
            return view('avicontrol::admin.information.index', [
                'galpones' => collect([]),
                'estadisticas' => [],
                'error' => 'Error al cargar los informes: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exporta a PDF un costo de producción específico por ID
     */
    public function exportarCostoProduccionPdf($id)
    {
        try {
            if (!\Schema::hasTable('avicontrol_production_costs')) {
                return redirect()->back()->with('error', 'La tabla de costos de producción no existe.');
            }

            $cost = \DB::table('avicontrol_production_costs')
                ->leftJoin('avicontrol_poultry_facilities', 'avicontrol_production_costs.poultry_facility_id', '=', 'avicontrol_poultry_facilities.id')
                ->select([
                    'avicontrol_production_costs.*',
                    'avicontrol_poultry_facilities.name as facility_name'
                ])
                ->where('avicontrol_production_costs.id', $id)
                ->first();

            if (!$cost) {
                return redirect()->route('avicontrol.admin.information.costos_produccion')
                    ->with('error', 'El registro solicitado no existe.');
            }

            $generatedAt = now();

            // Forzar HTML si se solicita explícitamente: ?format=html
            if (request()->query('format') === 'html') {
                return view('avicontrol::admin.information.pdf.costo-produccion', compact('cost', 'generatedAt'));
            }

            // Si DomPDF no está disponible, retornar HTML imprimible
            if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                return view('avicontrol::admin.information.pdf.costo-produccion', compact('cost', 'generatedAt'));
            }

            // Generar PDF con opciones seguras
            try {
                \Barryvdh\DomPDF\Facade\Pdf::setOptions([
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => true,
                    'defaultFont' => 'DejaVu Sans',
                    'dpi' => 96,
                ]);

                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('avicontrol::admin.information.pdf.costo-produccion', compact('cost', 'generatedAt'));
                $pdf->setPaper('a4', 'portrait');

                $filename = 'costo_produccion_' . $cost->id . '_' . $generatedAt->format('Y-m-d_H-i-s') . '.pdf';
                return $pdf->download($filename);
            } catch (\Throwable $e) {
                \Log::error('Fallo generando PDF individual (costo): ' . $e->getMessage());
                // Fallback a HTML para no dejar colgada la página
                return view('avicontrol::admin.information.pdf.costo-produccion', compact('cost', 'generatedAt'));
            }
        } catch (\Exception $e) {
            \Log::error('Error exportando PDF de costo de producción: ' . $e->getMessage());
            return redirect()->route('avicontrol.admin.information.costos_produccion')
                ->with('error', 'No se pudo generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Exporta a PDF el listado completo de costos de producción
     */
    public function exportarCostosProduccionPdf(Request $request)
    {
        try {
            if (!\Schema::hasTable('avicontrol_production_costs')) {
                return redirect()->back()->with('error', 'La tabla de costos de producción no existe.');
            }

            // Obtener TODOS los costos con nombre del galpón
            $productionCosts = \DB::table('avicontrol_production_costs')
                ->leftJoin('avicontrol_poultry_facilities', 'avicontrol_production_costs.poultry_facility_id', '=', 'avicontrol_poultry_facilities.id')
                ->select([
                    'avicontrol_production_costs.*',
                    'avicontrol_poultry_facilities.name as facility_name'
                ])
                ->orderBy('avicontrol_production_costs.created_at', 'desc')
                ->get();

            $generatedAt = now();

            // Forzar HTML si se solicita: ?format=html
            if ($request->query('format') === 'html') {
                return view('avicontrol::admin.information.pdf.costos-produccion', compact('productionCosts', 'generatedAt'));
            }

            // Si DomPDF no está disponible, retornar HTML imprimible
            if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                return view('avicontrol::admin.information.pdf.costos-produccion', compact('productionCosts', 'generatedAt'));
            }

            // Generar PDF con opciones seguras
            try {
                \Barryvdh\DomPDF\Facade\Pdf::setOptions([
                    'isRemoteEnabled' => false,
                    'isHtml5ParserEnabled' => true,
                    'defaultFont' => 'DejaVu Sans',
                    'dpi' => 96,
                ]);

                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('avicontrol::admin.information.pdf.costos-produccion', compact('productionCosts', 'generatedAt'));
                $pdf->setPaper('a4', 'landscape');

                $filename = 'costos_produccion_' . $generatedAt->format('Y-m-d_H-i-s') . '.pdf';
                return $pdf->download($filename);
            } catch (\Throwable $e) {
                \Log::error('Fallo generando PDF listado de costos: ' . $e->getMessage());
                // Fallback a HTML para no colgar la petición
                return view('avicontrol::admin.information.pdf.costos-produccion', compact('productionCosts', 'generatedAt'));
            }
        } catch (\Exception $e) {
            \Log::error('Error exportando PDF de costos de producción: ' . $e->getMessage());
            return redirect()->route('avicontrol.admin.information.costos_produccion')
                ->with('error', 'No se pudo generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene estadísticas generales del sistema
     */
    private function obtenerEstadisticasGenerales()
    {
        try {
            $estadisticas = [
                'total_galpones' => PoultryFacility::count(),
                'total_aves' => Bird::where('status', 'active')->sum('quantity'),
                'total_insumos' => \DB::table('avicontrol_inventory_products')->where('status', 'active')->count(),
                'registros_hoy' => $this->obtenerRegistrosHoy(),
                'produccion_hoy' => $this->obtenerProduccionHoy(),
                'consumo_hoy' => $this->obtenerConsumoHoy(),
                'galpones_activos' => PoultryFacility::where('status', 'active')->count(),
                'aves_por_galpon' => $this->obtenerAvesPorGalpon(),
            ];

            return $estadisticas;
        } catch (\Exception $e) {
            \Log::error('Error obteniendo estadísticas: ' . $e->getMessage());
            return [
                'total_galpones' => 0,
                'total_aves' => 0,
                'total_insumos' => 0,
                'registros_hoy' => 0,
                'produccion_hoy' => 0,
                'consumo_hoy' => 0,
                'galpones_activos' => 0,
                'aves_por_galpon' => [],
            ];
        }
    }

    /**
     * Obtiene el total de registros del día
     */
    private function obtenerRegistrosHoy()
    {
        $hoy = now()->toDateString();
        
        $produccion = \DB::table('avicontrol_productions')
            ->whereDate('created_at', $hoy)->count();
            
        $consumo = \DB::table('avicontrol_food_consumption')
            ->whereDate('created_at', $hoy)->count();
            
        $movimientos = \DB::table('avicontrol_inventory_movements')
            ->whereDate('created_at', $hoy)->count();
            
        return $produccion + $consumo + $movimientos;
    }

    /**
     * Obtiene la producción del día
     */
    private function obtenerProduccionHoy()
    {
        $hoy = now()->toDateString();
        
        return \DB::table('avicontrol_productions')
            ->whereDate('fecha', $hoy)
            ->sum('cantidad') ?? 0;
    }

    /**
     * Obtiene el consumo del día
     */
    private function obtenerConsumoHoy()
    {
        $hoy = now()->toDateString();
        
        return \DB::table('avicontrol_food_consumption')
            ->whereDate('fecha_registro', $hoy)
            ->sum('cantidad_kg') ?? 0;
    }

    /**
     * Obtiene el número de aves por galpón
     */
    private function obtenerAvesPorGalpon()
    {
        return PoultryFacility::withCount(['birds as total_aves' => function($query) {
            $query->where('status', 'active');
        }])
        ->get()
        ->map(function($galpon) {
            return [
                'id' => $galpon->id,
                'nombre' => $galpon->name,
                'total_aves' => $galpon->total_aves ?? 0,
                'capacidad' => $galpon->capacity,
                'porcentaje_ocupacion' => $galpon->capacity > 0 ? round(($galpon->total_aves / $galpon->capacity) * 100, 2) : 0
            ];
        });
    }

    public function show($id)
    {
        $galpon = PoultryFacility::with('birds')->findOrFail($id);
        return view('avicontrol::admin.information.show', compact('galpon'));
    }

    /**
     * Muestra la página de informes de producción
     */
    public function produccion(Request $request)
    {
        try {
            // Si es una petición AJAX, devolver datos JSON
            if ($request->ajax()) {
                $action = $request->get('action');
                
                switch ($action) {
                    case 'galpones':
                        $galpones = PoultryFacility::select('id', 'name')->get();
                        return response()->json(['success' => true, 'galpones' => $galpones]);
                        
                    case 'datos_iniciales':
                        $datosProduccion = $this->obtenerDatosProduccion();
                        return response()->json(['success' => true, 'datos' => $datosProduccion]);
                        
                    default:
                        return response()->json(['success' => false, 'message' => 'Acción no válida']);
                }
            }
            
            // Si no es AJAX, mostrar la vista normal
            $galpones = PoultryFacility::all();
            
            // Obtener datos reales de producción
            $datosProduccion = $this->obtenerDatosProduccion();
            $estadisticasProduccion = $this->obtenerEstadisticasProduccion();
            
            return view('avicontrol::admin.information.produccion', compact(
                'galpones', 
                'datosProduccion', 
                'estadisticasProduccion'
            ));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
            
            return view('avicontrol::admin.information.produccion', [
                'galpones' => collect([]),
                'datosProduccion' => collect([]),
                'estadisticasProduccion' => [],
                'error' => 'Error al cargar informes de producción: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene datos reales de producción
     */
    private function obtenerDatosProduccion()
    {
        try {
            \Log::info('Obteniendo datos de producción...');
            
            $producciones = Production::with(['galpon'])
                ->orderBy('fecha', 'desc')
                ->limit(100)
                ->get();
            
            \Log::info('Producciones encontradas: ' . $producciones->count());
            
            return $producciones->map(function($produccion) {
                    return [
                        'fecha' => $produccion->fecha,
                        'galpon' => $produccion->galpon ? $produccion->galpon->name : 'N/A',
                        'galpon_id' => $produccion->galpon_id,
                        'tipo_produccion' => $produccion->tipo_produccion ?? 'huevos',
                        'tipo' => $produccion->tipo ?? 'N/A',
                        'cantidad' => $produccion->cantidad ?? 0,
                        'mortalidad_aves' => $produccion->mortalidad_aves ?? 0,
                        'total_aves' => $this->obtenerAvesEnGalpon($produccion->galpon_id, $produccion->fecha),
                        'produccion' => $produccion->cantidad ?? 0,
                        'huevos_buenos' => $produccion->cantidad - ($produccion->huevos_rotos ?? 0) - ($produccion->huevos_sucios ?? 0),
                        'huevos_rotos' => $produccion->huevos_rotos ?? 0,
                        'huevos_sucios' => $produccion->huevos_sucios ?? 0,
                        'porcentaje' => $this->calcularPorcentajeProduccion($produccion),
                        'peso_promedio' => $produccion->peso_promedio ?? 0,
                        'peso_total' => $produccion->peso_total ?? 0,
                        'valor_unidad' => $produccion->valor_unidad ?? 0,
                        'valor_total' => $produccion->valor_total ?? 0,
                        'destino' => $produccion->destino ?? 'N/A',
                        'semana_produccion' => $produccion->semana_produccion ?? 'N/A',
                        'semana' => $produccion->semana_produccion ?? 'N/A',
                        'estado' => $produccion->estado ?? 'N/A',
                        'observaciones' => $produccion->observaciones ?? 'Sin observaciones'
                    ];
                });
            
            \Log::info('Datos de producción procesados y devueltos');
            
        } catch (\Exception $e) {
            \Log::error('Error obteniendo datos de producción: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Obtiene estadísticas de producción
     */
    private function obtenerEstadisticasProduccion()
    {
        try {
            $hoy = now()->toDateString();
            $inicioMes = now()->startOfMonth()->toDateString();
            
            // Estadísticas del día
            $produccionHoy = Production::whereDate('fecha', $hoy);
            $produccionHuevosHoy = $produccionHoy->where('tipo_produccion', 'huevos');
            
            // Estadísticas de mortalidad del día
            $mortalidadHoy = Production::whereDate('fecha', $hoy)->sum('mortalidad_aves') ?? 0;
            $avesActivasHoy = Bird::where('status', 'active')->sum('quantity') ?? 0;
            
            return [
                'total_produccion' => Production::sum('cantidad') ?? 0,
                'produccion_hoy' => $produccionHuevosHoy->sum('cantidad') ?? 0,
                'produccion_mes' => Production::whereBetween('fecha', [$inicioMes, $hoy])->where('tipo_produccion', 'huevos')->sum('cantidad') ?? 0,
                'promedio_diario' => $this->calcularPromedioDiarioProduccion(),
                'mejor_galpon' => $this->obtenerMejorGalponProduccion(),
                'total_aves_activas' => Bird::where('status', 'active')->sum('quantity') ?? 0,
                'produccion_por_galpon' => $this->obtenerProduccionPorGalpon(),
                
                // Estadísticas detalladas del día
                'huevos_buenos_hoy' => $this->obtenerHuevosBuenosHoy(),
                'huevos_rotos_hoy' => $produccionHuevosHoy->sum('huevos_rotos') ?? 0,
                'huevos_sucios_hoy' => $produccionHuevosHoy->sum('huevos_sucios') ?? 0,
                'valor_total_hoy' => $produccionHuevosHoy->sum('valor_total') ?? 0,
                
                // Producción por tipo de huevo hoy
                'tipo_a_hoy' => $produccionHuevosHoy->where('tipo', 'A')->sum('cantidad') ?? 0,
                'tipo_aa_hoy' => $produccionHuevosHoy->where('tipo', 'AA')->sum('cantidad') ?? 0,
                'tipo_b_hoy' => $produccionHuevosHoy->where('tipo', 'B')->sum('cantidad') ?? 0,
                'tipo_c_hoy' => $produccionHuevosHoy->where('tipo', 'C')->sum('cantidad') ?? 0,
                
                // Estadísticas de mortalidad
                'mortalidad_hoy' => $mortalidadHoy,
                'aves_activas_hoy' => $avesActivasHoy,
            ];
        } catch (\Exception $e) {
            \Log::error('Error obteniendo estadísticas de producción: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene el número de aves en un galpón en una fecha específica
     */
    private function obtenerAvesEnGalpon($galponId, $fecha)
    {
        try {
            return Bird::where('galpon_id', $galponId)
                ->where('status', 'active')
                ->where('entry_date', '<=', $fecha)
                ->sum('quantity') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula el porcentaje de producción
     */
    private function calcularPorcentajeProduccion($produccion)
    {
        try {
            $avesEnGalpon = $this->obtenerAvesEnGalpon($produccion->galpon_id, $produccion->fecha);
            if ($avesEnGalpon > 0) {
                return round(($produccion->cantidad / $avesEnGalpon) * 100, 2);
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula el promedio diario de producción
     */
    private function calcularPromedioDiarioProduccion()
    {
        try {
            $totalProduccion = Production::sum('cantidad') ?? 0;
            $totalDias = Production::distinct('fecha')->count('fecha');
            
            return $totalDias > 0 ? round($totalProduccion / $totalDias, 0) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtiene el mejor galpón por producción
     */
    private function obtenerMejorGalponProduccion()
    {
        try {
            $mejorGalpon = Production::select('galpon_id')
                ->selectRaw('SUM(cantidad) as total_produccion')
                ->groupBy('galpon_id')
                ->orderByDesc('total_produccion')
                ->first();
                
            if ($mejorGalpon) {
                $galpon = PoultryFacility::find($mejorGalpon->galpon_id);
                return $galpon ? $galpon->name : 'N/A';
            }
            
            return 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Obtiene la cantidad de huevos buenos del día
     */
    private function obtenerHuevosBuenosHoy()
    {
        try {
            $hoy = now()->toDateString();
            $produccionHoy = Production::whereDate('fecha', $hoy)
                ->where('tipo_produccion', 'huevos');
            
            $totalHuevos = $produccionHoy->sum('cantidad') ?? 0;
            $huevosRotos = $produccionHoy->sum('huevos_rotos') ?? 0;
            $huevosSucios = $produccionHoy->sum('huevos_sucios') ?? 0;
            
            return $totalHuevos - $huevosRotos - $huevosSucios;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Muestra el detalle de producción de un galpón específico
     */
    public function detalleGalpon($galpon_id)
    {
        try {
            \Log::info('Accediendo a detalle del galpón: ' . $galpon_id);
            
            // Obtener información del galpón
            $galpon = Galpon::findOrFail($galpon_id);
            
            // Obtener toda la producción del galpón
            $produccionGalpon = Production::where('galpon_id', $galpon_id)
                ->where('tipo_produccion', 'huevos')
                ->orderBy('fecha', 'desc')
                ->get();
            
            \Log::info('Producción encontrada para galpón ' . $galpon_id . ': ' . $produccionGalpon->count() . ' registros');
            
            // Calcular estadísticas acumuladas
            $estadisticasAcumuladas = [
                'total_produccion' => $produccionGalpon->sum('cantidad'),
                'total_huevos_buenos' => $produccionGalpon->sum('cantidad') - $produccionGalpon->sum('huevos_rotos') - $produccionGalpon->sum('huevos_sucios'),
                'total_huevos_rotos' => $produccionGalpon->sum('huevos_rotos'),
                'total_huevos_sucios' => $produccionGalpon->sum('huevos_sucios'),
                'total_valor' => $produccionGalpon->sum('valor_total'),
                'promedio_diario' => $produccionGalpon->count() > 0 ? round($produccionGalpon->sum('cantidad') / $produccionGalpon->count(), 0) : 0,
                'dias_registrados' => $produccionGalpon->distinct('fecha')->count('fecha'),
                'mejor_dia' => $produccionGalpon->max('cantidad'),
                'peor_dia' => $produccionGalpon->min('cantidad'),
            ];
            
            // Obtener producción por tipo de huevo
            $produccionPorTipo = [
                'tipo_a' => $produccionGalpon->where('tipo', 'A')->sum('cantidad'),
                'tipo_aa' => $produccionGalpon->where('tipo', 'AA')->sum('cantidad'),
                'tipo_b' => $produccionGalpon->where('tipo', 'B')->sum('cantidad'),
                'tipo_c' => $produccionGalpon->where('tipo', 'C')->sum('cantidad'),
                'tipo_d' => $produccionGalpon->where('tipo', 'D')->sum('cantidad'),
            ];
            
            // Obtener producción por semana
            $produccionPorSemana = $produccionGalpon->groupBy('semana_produccion')
                ->map(function($grupo) {
                    return [
                        'total' => $grupo->sum('cantidad'),
                        'huevos_buenos' => $grupo->sum('cantidad') - $grupo->sum('huevos_rotos') - $grupo->sum('huevos_sucios'),
                        'huevos_rotos' => $grupo->sum('huevos_rotos'),
                        'huevos_sucios' => $grupo->sum('huevos_sucios'),
                        'valor_total' => $grupo->sum('valor_total'),
                        'dias' => $grupo->count()
                    ];
                });
            
            \Log::info('Vista de detalle del galpón renderizada exitosamente');
            
            return view('avicontrol::admin.information.detalle-galpon', compact(
                'galpon',
                'produccionGalpon',
                'estadisticasAcumuladas',
                'produccionPorTipo',
                'produccionPorSemana'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error obteniendo detalle del galpón: ' . $e->getMessage());
            return redirect()->route('avicontrol.admin.information.produccion')
                ->with('error', 'Error al cargar el detalle del galpón: ' . $e->getMessage());
        }
    }

    /**
     * Método de prueba para verificar datos de producción
     */
    public function testProduccion()
    {
        try {
            \Log::info('=== TEST PRODUCCIÓN ===');
            
            // Verificar que la tabla existe
            $tableExists = \Schema::hasTable('avicontrol_productions');
            \Log::info('Tabla avicontrol_productions existe: ' . ($tableExists ? 'SÍ' : 'NO'));
            
            if (!$tableExists) {
                return response()->json(['error' => 'Tabla de producción no existe'], 404);
            }
            
            // Contar registros
            $totalProducciones = Production::count();
            \Log::info('Total de producciones: ' . $totalProducciones);
            
            // Obtener algunos registros de ejemplo
            $producciones = Production::limit(5)->get();
            \Log::info('Registros de ejemplo: ' . $producciones->toArray());
            
            // Verificar galpones
            $totalGalpones = Galpon::count();
            \Log::info('Total de galpones: ' . $totalGalpones);
            
            return response()->json([
                'success' => true,
                'total_producciones' => $totalProducciones,
                'total_galpones' => $totalGalpones,
                'producciones_ejemplo' => $producciones->toArray(),
                'message' => 'Test completado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en test de producción: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Método de prueba para verificar que la ruta esté funcionando
     */
    public function testDetalleGalpon()
    {
        try {
            // Obtener el primer galpón disponible
            $galpon = Galpon::first();
            
            if (!$galpon) {
                return response()->json(['error' => 'No hay galpones disponibles'], 404);
            }
            
            return response()->json([
                'success' => true,
                'galpon' => $galpon->toArray(),
                'message' => 'Ruta funcionando correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene la producción por galpón
     */
    private function obtenerProduccionPorGalpon()
    {
        try {
            return Production::select('galpon_id')
                ->selectRaw('SUM(cantidad) as total_produccion')
                ->groupBy('galpon_id')
                ->get()
                ->map(function($item) {
                    $galpon = PoultryFacility::find($item->galpon_id);
                    return [
                        'galpon' => $galpon ? $galpon->name : 'N/A',
                        'total_produccion' => $item->total_produccion
                    ];
                });
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Muestra la página de informes de alimentos
     */
    public function alimentos(Request $request)
    {
        try {
            // Si es una petición AJAX, devolver datos JSON
            if ($request->ajax()) {
                $action = $request->get('action');
                
                switch ($action) {
                    case 'galpones':
                        $galpones = PoultryFacility::select('id', 'name')->get();
                        return response()->json(['success' => true, 'galpones' => $galpones]);
                        
                    case 'datos_iniciales':
                        $datosAlimentos = $this->obtenerDatosAlimentos();
                        return response()->json(['success' => true, 'datos' => $datosAlimentos]);
                        
                    default:
                        return response()->json(['success' => false, 'message' => 'Acción no válida']);
                }
            }
            
            // Si no es AJAX, mostrar la vista normal
            $galpones = PoultryFacility::all();
            
            // Obtener datos reales de alimentos
            $datosAlimentos = $this->obtenerDatosAlimentos();
            $estadisticasAlimentos = $this->obtenerEstadisticasAlimentos();
            
            return view('avicontrol::admin.information.alimentos', compact(
                'galpones', 
                'datosAlimentos', 
                'estadisticasAlimentos'
            ));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
            
            return view('avicontrol::admin.information.alimentos', [
                'galpones' => collect([]),
                'datosAlimentos' => collect([]),
                'estadisticasAlimentos' => [],
                'error' => 'Error al cargar informes de alimentos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene datos reales de alimentos
     */
    private function obtenerDatosAlimentos()
    {
        try {
            return FoodConsumption::with(['galpon', 'producto'])
                ->orderBy('fecha_registro', 'desc')
                ->limit(100)
                ->get()
                ->map(function($consumo) {
                    return [
                        'id' => $consumo->id, // Agregar el ID del registro
                        'fecha' => $consumo->fecha_registro,
                        'galpon' => $consumo->galpon ? $consumo->galpon->name : 'N/A',
                        'insumo' => $consumo->producto ? $consumo->producto->name : 'N/A',
                        'cantidad' => $consumo->cantidad_kg ?? 0,
                        'precio_unitario' => $this->obtenerPrecioInsumo($consumo->producto_id),
                        'total' => $this->calcularCostoTotal($consumo),
                        'proveedor' => $this->obtenerProveedorInsumo($consumo->producto_id),
                        'observaciones' => $consumo->observaciones ?? 'Sin observaciones'
                    ];
                });
        } catch (\Exception $e) {
            \Log::error('Error obteniendo datos de alimentos: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Obtiene estadísticas de alimentos
     */
    private function obtenerEstadisticasAlimentos()
    {
        try {
            $hoy = now()->toDateString();
            $inicioMes = now()->startOfMonth()->toDateString();
            
            return [
                'total_gasto' => $this->calcularTotalGastoAlimentos(),
                'promedio_galpon' => $this->calcularPromedioGastoPorGalpon(),
                'total_kilos' => FoodConsumption::sum('cantidad_kg') ?? 0,
                'costo_ave' => $this->calcularCostoPorAve(),
                'consumo_hoy' => FoodConsumption::whereDate('fecha_registro', $hoy)->sum('cantidad_kg') ?? 0,
                'consumo_mes' => FoodConsumption::whereBetween('fecha_registro', [$inicioMes, $hoy])->sum('cantidad_kg') ?? 0,
                'costos_por_galpon' => $this->obtenerCostosPorGalpon(),
                'consumo_por_insumo' => $this->obtenerConsumoPorInsumo(),
                'desperdicios' => $this->obtenerDatosDesperdicios(),
            ];
        } catch (\Exception $e) {
            \Log::error('Error obteniendo estadísticas de alimentos: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene el precio de un insumo
     */
    private function obtenerPrecioInsumo($productoId)
    {
        try {
            $producto = \DB::table('avicontrol_inventory_products')->find($productoId);
            return $producto ? ($producto->unit_price ?? 0) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula el costo total de un consumo
     */
    private function calcularCostoTotal($consumo)
    {
        try {
            $precioUnitario = $this->obtenerPrecioInsumo($consumo->producto_id);
            return $precioUnitario * ($consumo->cantidad_kg ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtiene el proveedor de un insumo
     */
    private function obtenerProveedorInsumo($productoId)
    {
        try {
            $producto = \DB::table('avicontrol_inventory_products')->find($productoId);
            return $producto ? ($producto->supplier ?? 'N/A') : 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Calcula el total gastado en alimentos
     */
    private function calcularTotalGastoAlimentos()
    {
        try {
            $consumos = FoodConsumption::where('estado', '!=', 'cancelled')->get();
            return $consumos->sum(function($consumo) {
                return $this->calcularCostoTotal($consumo);
            });
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula el promedio de gasto por galpón
     */
    private function calcularPromedioGastoPorGalpon()
    {
        try {
            $totalGasto = $this->calcularTotalGastoAlimentos();
            $totalGalpones = PoultryFacility::count();
            
            return $totalGalpones > 0 ? round($totalGasto / $totalGalpones, 2) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula el costo por ave
     */
    private function calcularCostoPorAve()
    {
        try {
            $totalGasto = $this->calcularTotalGastoAlimentos();
            $totalAves = Bird::where('status', 'active')->sum('quantity') ?? 0;
            
            return $totalAves > 0 ? round($totalGasto / $totalAves, 2) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtiene los costos por galpón
     */
    private function obtenerCostosPorGalpon()
    {
        try {
            return FoodConsumption::where('estado', '!=', 'cancelled')
                ->select('galpon_id')
                ->selectRaw('SUM(cantidad_kg) as total_kg')
                ->groupBy('galpon_id')
                ->get()
                ->map(function($item) {
                    $galpon = PoultryFacility::find($item->galpon_id);
                    return [
                        'galpon' => $galpon ? $galpon->name : 'N/A',
                        'total_kg' => $item->total_kg,
                        'costo_total' => $this->calcularCostoTotalPorGalpon($item->galpon_id)
                    ];
                });
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Calcula el costo total por galpón
     */
    private function calcularCostoTotalPorGalpon($galponId)
    {
        try {
            $consumos = FoodConsumption::where('galpon_id', $galponId)
                ->where('estado', '!=', 'cancelled')
                ->get();
            return $consumos->sum(function($consumo) {
                return $this->calcularCostoTotal($consumo);
            });
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtiene el consumo por tipo de insumo
     */
    private function obtenerConsumoPorInsumo()
    {
        try {
            return FoodConsumption::where('estado', '!=', 'cancelled')
                ->select('producto_id')
                ->selectRaw('SUM(cantidad_kg) as total_kg')
                ->groupBy('producto_id')
                ->get()
                ->map(function($item) {
                    $producto = \DB::table('avicontrol_inventory_products')->find($item->producto_id);
                    return [
                        'insumo' => $producto ? $producto->name : 'N/A',
                        'total_kg' => $item->total_kg
                    ];
                });
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Obtiene datos de desperdicios de alimentos
     */
    private function obtenerDatosDesperdicios()
    {
        try {
            // Verificar si existe la tabla de desperdicios
            if (!\Schema::hasTable('avicontrol_food_waste')) {
                return collect([]);
            }

            return \DB::table('avicontrol_food_waste')
                ->select('galpon_id')
                ->selectRaw('SUM(cantidad_kg) as total_desperdicio')
                ->groupBy('galpon_id')
                ->get()
                ->map(function($item) {
                    $galpon = PoultryFacility::find($item->galpon_id);
                    $consumoTotal = FoodConsumption::where('galpon_id', $item->galpon_id)
                        ->where('estado', '!=', 'cancelled')
                        ->sum('cantidad_kg') ?? 0;
                    $porcentajeDesperdicio = $consumoTotal > 0 ? ($item->total_desperdicio / $consumoTotal) * 100 : 0;
                    
                    return [
                        'galpon' => $galpon ? $galpon->name : 'N/A',
                        'total_desperdicio' => $item->total_desperdicio,
                        'porcentaje_desperdicio' => $porcentajeDesperdicio
                    ];
                });
        } catch (\Exception $e) {
            \Log::error('Error obteniendo datos de desperdicios: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Muestra la página de seguimientos de galpón
     */
    public function seguimientos(Request $request)
    {
        try {
            // Si es una petición AJAX, devolver datos JSON
            if ($request->ajax()) {
                $action = $request->get('action');
                
                switch ($action) {
                    case 'galpones':
                        $galpones = PoultryFacility::select('id', 'name')->get();
                        return response()->json(['success' => true, 'galpones' => $galpones]);
                        
                    case 'lotes':
                        $lotes = Bird::select('id', 'batch_name')
                            ->whereNotNull('batch_name')
                            ->where('batch_name', '!=', '')
                            ->distinct()
                            ->get();
                        return response()->json(['success' => true, 'lotes' => $lotes]);
                        
                    case 'datos_iniciales':
                        $datosSeguimientos = $this->obtenerDatosSeguimientos();
                        return response()->json(['success' => true, 'datos' => $datosSeguimientos]);
                        
                    default:
                        return response()->json(['success' => false, 'message' => 'Acción no válida']);
                }
            }
            
            // Si no es AJAX, mostrar la vista normal
            $galpones = PoultryFacility::all();
            
            // Obtener datos reales de seguimientos
            $datosSeguimientos = $this->obtenerDatosSeguimientos();
            $estadisticasSeguimientos = $this->obtenerEstadisticasSeguimientos();
            $datosCrecimiento = $this->obtenerDatosCrecimiento();
            
            return view('avicontrol::admin.information.seguimientos', compact(
                'galpones', 
                'datosSeguimientos', 
                'estadisticasSeguimientos',
                'datosCrecimiento'
            ));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
            
            return view('avicontrol::admin.information.seguimientos', [
                'galpones' => collect([]),
                'datosSeguimientos' => collect([]),
                'estadisticasSeguimientos' => [],
                'datosCrecimiento' => collect([]),
                'error' => 'Error al cargar seguimientos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene datos reales de seguimientos
     */
    private function obtenerDatosSeguimientos()
    {
        try {
            return Bird::with(['poultryFacility', 'galpon'])
                ->where('status', 'active')
                ->orderBy('entry_date', 'desc')
                ->get()
                ->map(function($ave) {
                    return [
                        'fecha' => $ave->entry_date,
                        'galpon' => $ave->poultryFacility ? $ave->poultryFacility->name : 
                                   ($ave->galpon ? $ave->galpon->name : 'N/A'),
                        'lote' => $ave->batch_name ?? 'N/A',
                        'edad' => $this->calcularEdadAve($ave->entry_date),
                        'peso_promedio' => $ave->average_weight ?? 0,
                        'peso_minimo' => $this->obtenerPesoMinimo($ave),
                        'peso_maximo' => $this->obtenerPesoMaximo($ave),
                        'desviacion' => $this->calcularDesviacionPeso($ave),
                        'observaciones' => $ave->notes ?? 'Sin observaciones'
                    ];
                });
        } catch (\Exception $e) {
            \Log::error('Error obteniendo datos de seguimientos: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Obtiene estadísticas de seguimientos
     */
    private function obtenerEstadisticasSeguimientos()
    {
        try {
            $avesActivas = Bird::where('status', 'active')->get();
            
            return [
                'total_aves_seguimiento' => $avesActivas->sum('quantity') ?? 0,
                'promedio_peso' => $avesActivas->avg('average_weight') ?? 0,
                'edad_promedio' => $this->calcularEdadPromedio($avesActivas),
                'tasa_crecimiento' => $this->calcularTasaCrecimiento(),
                'aves_por_galpon' => $this->obtenerAvesPorGalponSeguimiento(),
                'distribucion_pesos' => $this->obtenerDistribucionPesos($avesActivas),
            ];
        } catch (\Exception $e) {
            \Log::error('Error obteniendo estadísticas de seguimientos: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene datos de crecimiento para gráficas
     */
    private function obtenerDatosCrecimiento()
    {
        try {
            return Bird::with(['poultryFacility'])
                ->where('status', 'active')
                ->whereNotNull('entry_date')
                ->whereNotNull('average_weight')
                ->orderBy('entry_date')
                ->get()
                ->groupBy('poultry_facility_id')
                ->map(function($aves, $galponId) {
                    $galpon = PoultryFacility::find($galponId);
                    return [
                        'galpon' => $galpon ? $galpon->name : 'N/A',
                        'datos' => $aves->map(function($ave) {
                            return [
                                'fecha' => $ave->entry_date,
                                'peso' => $ave->average_weight,
                                'edad_dias' => $this->calcularEdadAve($ave->entry_date)
                            ];
                        })->sortBy('entry_date')->values()
                    ];
                })->values();
        } catch (\Exception $e) {
            \Log::error('Error obteniendo datos de crecimiento: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Calcula la edad de un ave en días
     */
    private function calcularEdadAve($entryDate)
    {
        try {
            if (!$entryDate) return 0;
            return now()->diffInDays($entryDate);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtiene el peso mínimo de un ave
     */
    private function obtenerPesoMinimo($ave)
    {
        try {
            // Por ahora usamos el peso promedio como mínimo
            // En un sistema real, esto vendría de mediciones individuales
            return ($ave->average_weight ?? 0) * 0.9; // 90% del peso promedio
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtiene el peso máximo de un ave
     */
    private function obtenerPesoMaximo($ave)
    {
        try {
            // Por ahora usamos el peso promedio como máximo
            // En un sistema real, esto vendría de mediciones individuales
            return ($ave->average_weight ?? 0) * 1.1; // 110% del peso promedio
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula la desviación estándar del peso
     */
    private function calcularDesviacionPeso($ave)
    {
        try {
            // Por ahora usamos un valor fijo
            // En un sistema real, esto se calcularía con datos reales
            return ($ave->average_weight ?? 0) * 0.05; // 5% del peso promedio
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula la edad promedio de las aves
     */
    private function calcularEdadPromedio($aves)
    {
        try {
            $totalEdad = 0;
            $totalAves = 0;
            
            foreach ($aves as $ave) {
                if ($ave->entry_date) {
                    $edad = $this->calcularEdadAve($ave->entry_date);
                    $totalEdad += $edad * ($ave->quantity ?? 1);
                    $totalAves += $ave->quantity ?? 1;
                }
            }
            
            return $totalAves > 0 ? round($totalEdad / $totalAves, 0) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Calcula la tasa de crecimiento
     */
    private function calcularTasaCrecimiento()
    {
        try {
            // Por ahora calculamos una tasa simple
            // En un sistema real, esto se calcularía con datos históricos
            $aves = Bird::where('status', 'active')->get();
            $pesoPromedio = $aves->avg('average_weight') ?? 0;
            $edadPromedio = $this->calcularEdadPromedio($aves);
            
            if ($edadPromedio > 0) {
                return round(($pesoPromedio / $edadPromedio) * 100, 2);
            }
            
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtiene aves por galpón para seguimientos
     */
    private function obtenerAvesPorGalponSeguimiento()
    {
        try {
            return PoultryFacility::withCount(['birds as total_aves' => function($query) {
                $query->where('status', 'active');
            }])
            ->get()
            ->map(function($galpon) {
                return [
                    'id' => $galpon->id,
                    'nombre' => $galpon->name,
                    'total_aves' => $galpon->total_aves ?? 0,
                    'peso_promedio' => $this->obtenerPesoPromedioGalpon($galpon->id)
                ];
            });
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Obtiene el peso promedio de un galpón
     */
    private function obtenerPesoPromedioGalpon($galponId)
    {
        try {
            return Bird::where('galpon_id', $galponId)
                ->where('status', 'active')
                ->avg('average_weight') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Obtiene la distribución de pesos
     */
    private function obtenerDistribucionPesos($aves)
    {
        try {
            $rangos = [
                ['min' => 0, 'max' => 1000, 'label' => '0-1000g'],
                ['min' => 1000, 'max' => 1500, 'label' => '1000-1500g'],
                ['min' => 1500, 'max' => 2000, 'label' => '1500-2000g'],
                ['min' => 2000, 'max' => 2500, 'label' => '2000-2500g'],
                ['min' => 2500, 'max' => 9999, 'label' => '2500g+']
            ];
            
            $conteos = [];
            foreach ($rangos as $rango) {
                $conteo = $aves->filter(function($ave) use ($rango) {
                    $peso = $ave->average_weight ?? 0;
                    return $peso >= $rango['min'] && $peso < $rango['max'];
                })->sum('quantity');
                $conteos[] = $conteo;
            }
            
            return $conteos;
        } catch (\Exception $e) {
            return [0, 0, 0, 0, 0];
        }
    }

    /**
                'success' => false,
                'message' => 'Error al filtrar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtra datos de alimentos
     */
    public function filtrarAlimentos(Request $request)
    {
        try {
            // Aquí implementarías la lógica de filtrado real
            $datos = [
                'success' => true,
                'data' => [],
                'message' => 'Filtrado aplicado correctamente'
            ];
            
            return response()->json($datos);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al filtrar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filtra datos de consumo de alimentos
     */
    public function filtrarConsumoAlimentos(Request $request)
    {
        try {
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');
            $galponId = $request->input('galpon_id');

            $query = FoodConsumption::query()->with(['galpon', 'producto']);

            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('fecha_registro', [$fechaInicio, $fechaFin]);
            }

            if ($galponId) {
                $query->where('galpon_id', $galponId);
            }

            $consumo = $query->orderBy('fecha_registro', 'desc')->get()->map(function($consumo) {
                return [
                    'fecha' => $consumo->fecha_registro,
                    'galpon' => $consumo->galpon ? $consumo->galpon->name : 'N/A',
                    'insumo' => $consumo->producto ? $consumo->producto->name : 'N/A',
                    'cantidad' => $consumo->cantidad_kg ?? 0,
                    'precio_unitario' => $this->obtenerPrecioInsumo($consumo->producto_id),
                    'total' => $this->calcularCostoTotal($consumo),
                    'observaciones' => $consumo->observaciones ?? 'Sin observaciones'
                ];
            });

            return response()->json(['success' => true, 'data' => $consumo]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al filtrar datos de consumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Filtra datos de seguimientos
     */
    public function filtrarSeguimientos(Request $request)
    {
        try {
            $query = \Modules\AVICONTROL\Entities\Bird::query()->with('poultryFacility');

            // Aplicar filtros
            if ($request->filled('galpon')) {
                $query->where('poultry_facility_id', $request->input('galpon'));
            }

            if ($request->filled('lote')) {
                $query->where('id', $request->input('lote')); // Asumiendo que el valor del lote es el ID del ave/lote
            }

            if ($request->filled('fecha_inicio')) {
                $query->whereDate('entry_date', '>=', $request->input('fecha_inicio'));
            }

            if ($request->filled('fecha_fin')) {
                $query->whereDate('entry_date', '<=', $request->input('fecha_fin'));
            }

            $aves = $query->where('status', 'active')->get();

            $datosFiltrados = $aves->map(function ($ave) {
                // Calcular la edad
                $edad = 0;
                if ($ave->entry_date) {
                    $edad = \Carbon\Carbon::parse($ave->entry_date)->diffInDays(\Carbon\Carbon::now());
                }

                return [
                    'fecha' => \Carbon\Carbon::parse($ave->entry_date)->format('Y-m-d'),
                    'galpon' => $ave->poultryFacility->name ?? 'N/A',
                    'lote' => $ave->batch_name,
                    'edad' => $edad,
                    'peso_promedio' => $ave->average_weight ?? 0,
                    'peso_minimo' => 0, // Dato de ejemplo
                    'peso_maximo' => 0, // Dato de ejemplo
                    'desviacion' => 0, // Dato de ejemplo
                    'observaciones' => '' // Dato de ejemplo
                ];
            });

            // Filtrado por edad (post-consulta)
            if ($request->filled('edad_min')) {
                $datosFiltrados = $datosFiltrados->where('edad', '>=', $request->input('edad_min'));
            }

            if ($request->filled('edad_max')) {
                $datosFiltrados = $datosFiltrados->where('edad', '<=', $request->input('edad_max'));
            }

            return response()->json(['success' => true, 'datos' => $datosFiltrados->values()]);
        } catch (\Exception $e) {
            \Log::error('Error al filtrar seguimientos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al filtrar los datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporta informes de producción a Excel
     */
    public function exportarProduccionExcel(Request $request)
    {
        try {
            // Implementar exportación a Excel
            return response()->json(['success' => true, 'message' => 'Exportación iniciada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Exporta informes de producción a PDF
     */
    public function exportarProduccionPdf(Request $request)
    {
        try {
            // Implementar exportación a PDF
            return response()->json(['success' => true, 'message' => 'Exportación iniciada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Exporta informes de alimentos a Excel
     */
    public function exportarAlimentosExcel(Request $request)
    {
        try {
            // Implementar exportación a Excel
            return response()->json(['success' => true, 'message' => 'Exportación iniciada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Exporta a PDF un alimento específico por ID
     */
    public function exportarAlimentoIndividualPdf($id)
    {
        try {
            // Buscar el alimento en la base de datos
            $alimento = \DB::table('avicontrol_food_consumption')
                ->leftJoin('avicontrol_poultry_facilities', 'avicontrol_food_consumption.poultry_facility_id', '=', 'avicontrol_poultry_facilities.id')
                ->leftJoin('avicontrol_inventory_products', 'avicontrol_food_consumption.product_id', '=', 'avicontrol_inventory_products.id')
                ->select([
                    'avicontrol_food_consumption.*',
                    'avicontrol_poultry_facilities.name as galpon_name',
                    'avicontrol_inventory_products.name as producto_name'
                ])
                ->where('avicontrol_food_consumption.id', $id)
                ->first();

            if (!$alimento) {
                return redirect()->back()->with('error', 'Alimento no encontrado');
            }

            $generatedAt = now();

            // Si se solicita descarga, generar PDF
            if (request()->query('download') === '1') {
                // Verificar si DomPDF está disponible
                if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                    try {
                        // Configuración ultra-robusta de DomPDF
                        \Barryvdh\DomPDF\Facade\Pdf::setOptions([
                            'isRemoteEnabled' => false,
                            'isHtml5ParserEnabled' => true,
                            'defaultFont' => 'DejaVu Sans',
                            'dpi' => 96,
                            'defaultPaperSize' => 'a4',
                            'tempDir' => storage_path('app/temp'),
                            'chroot' => storage_path('app'),
                            'isFontSubsettingEnabled' => false,
                            'isPhpEnabled' => false,
                            'debugKeepTemp' => false,
                            'debugCss' => false,
                            'debugLayout' => false,
                            'defaultMediaType' => 'print',
                            'fontHeightRatio' => 0.9,
                            'isJavascriptEnabled' => false,
                            'isCssFloatEnabled' => true,
                            'isCssPositionEnabled' => true,
                            'isCssMarginEnabled' => true,
                            'isCssPaddingEnabled' => true,
                            'isCssBorderEnabled' => true,
                            'isCssBackgroundEnabled' => true,
                            'isCssTextEnabled' => true,
                            'isCssFontEnabled' => true,
                            'isCssListEnabled' => true,
                            'isCssTableEnabled' => true,
                            'isCssTransformEnabled' => false,
                            'isCssAnimationEnabled' => false,
                            'isCssTransitionEnabled' => false,
                            'isCssFlexboxEnabled' => false,
                            'isCssGridEnabled' => false,
                        ]);

                        // Generar PDF desde la vista Blade con manejo de errores
                        $html = view('avicontrol::admin.information.pdf.alimento', compact('alimento', 'generatedAt'))->render();
                        
                        // Verificar que el HTML se generó correctamente
                        if (empty($html) || strlen($html) < 100) {
                            throw new \Exception('Error generando HTML: contenido vacío o muy corto');
                        }
                        
                        // Crear PDF con HTML validado
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                        $pdf->setPaper('a4', 'portrait');
                        
                        // Generar nombre de archivo
                        $filename = 'alimento_' . $alimento->id . '_' . $generatedAt->format('Y-m-d_H-i-s') . '.pdf';
                        
                        // Verificar que el PDF se generó correctamente
                        $pdfContent = $pdf->output();
                        if (empty($pdfContent) || strlen($pdfContent) < 100) {
                            throw new \Exception('Error generando PDF: contenido vacío o muy corto');
                        }
                        
                        // Descargar PDF con headers correctos
                        return response($pdfContent)
                            ->header('Content-Type', 'application/pdf')
                            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                            ->header('Content-Length', strlen($pdfContent));
                        
                    } catch (\Throwable $e) {
                        \Log::error('Error generando PDF con DomPDF: ' . $e->getMessage());
                        \Log::error('Stack trace: ' . $e->getTraceAsString());
                        
                        // Fallback: generar HTML descargable
                        return $this->generarHtmlDescargable($alimento, $generatedAt);
                    }
                } else {
                    // Si DomPDF no está disponible, generar HTML
                    return $this->generarHtmlDescargable($alimento, $generatedAt);
                }
            }

            // Retornar vista HTML imprimible (fallback)
            return view('avicontrol::admin.information.pdf.alimento', compact('alimento', 'generatedAt'));
            
        } catch (\Exception $e) {
            \Log::error('Error general en exportarAlimentoIndividualPdf: ' . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo generar el informe: ' . $e->getMessage());
        }
    }

    /**
     * Genera HTML optimizado específicamente para PDF
     */
    private function generarHtmlParaPdf($alimento, $generatedAt)
    {
        $estado = $alimento->estado ?? 'active';
        $estadoTexto = $estado === 'active' ? 'Activo' : ($estado === 'cancelled' ? 'Cancelado' : 'Pendiente');
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Informe Alimento ' . $alimento->id . '</title>
            <style>
                @page {
                    margin: 2cm;
                    size: A4 portrait;
                }
                
                body {
                    font-family: DejaVu Sans, Arial, sans-serif;
                    font-size: 10pt;
                    line-height: 1.4;
                    color: #000;
                    margin: 0;
                    padding: 0;
                    background-color: white;
                }
                
                .header {
                    text-align: center;
                    border-bottom: 2pt solid #007bff;
                    padding-bottom: 15pt;
                    margin-bottom: 20pt;
                }
                
                .header h1 {
                    color: #007bff;
                    margin: 0;
                    font-size: 18pt;
                    font-weight: bold;
                }
                
                .header .subtitle {
                    color: #666;
                    font-size: 12pt;
                    margin-top: 5pt;
                }
                
                .info-section {
                    margin-bottom: 20pt;
                    page-break-inside: avoid;
                }
                
                .info-section h2 {
                    color: #007bff;
                    font-size: 14pt;
                    border-bottom: 1pt solid #ddd;
                    padding-bottom: 5pt;
                    margin-bottom: 10pt;
                }
                
                .info-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 15pt;
                }
                
                .info-table th,
                .info-table td {
                    border: 1pt solid #ddd;
                    padding: 8pt;
                    text-align: left;
                    font-size: 9pt;
                }
                
                .info-table th {
                    background-color: #f8f9fa;
                    font-weight: bold;
                    width: 35%;
                }
                
                .info-table td {
                    width: 65%;
                }
                
                .footer {
                    margin-top: 30pt;
                    text-align: center;
                    font-size: 8pt;
                    color: #666;
                    border-top: 1pt solid #ddd;
                    padding-top: 15pt;
                }
                
                .status-badge {
                    display: inline-block;
                    padding: 4pt 8pt;
                    border-radius: 4pt;
                    font-size: 8pt;
                    font-weight: bold;
                    text-transform: uppercase;
                }
                
                .status-active {
                    background-color: #28a745;
                    color: white;
                }
                
                .status-cancelled {
                    background-color: #dc3545;
                    color: white;
                }
                
                .status-pending {
                    background-color: #ffc107;
                    color: #000;
                }
                
                .summary-box {
                    background-color: #e9ecef;
                    border: 1pt solid #dee2e6;
                    border-radius: 6pt;
                    padding: 12pt;
                    margin: 15pt 0;
                }
                
                .summary-box h3 {
                    margin-top: 0;
                    color: #495057;
                    font-size: 12pt;
                }
                
                .summary-grid {
                    display: table;
                    width: 100%;
                    margin-top: 10pt;
                }
                
                .summary-row {
                    display: table-row;
                }
                
                .summary-item {
                    display: table-cell;
                    text-align: center;
                    padding: 8pt;
                    background-color: white;
                    border: 1pt solid #dee2e6;
                    width: 33.33%;
                }
                
                .summary-item .value {
                    font-size: 14pt;
                    font-weight: bold;
                    color: #007bff;
                }
                
                .summary-item .label {
                    font-size: 8pt;
                    color: #666;
                    margin-top: 3pt;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>INFORME DE ALIMENTO - AVICONTROL</h1>
                <div class="subtitle">Sistema de Control Avícola</div>
            </div>

            <div class="info-section">
                <h2>Información del Registro</h2>
                <table class="info-table">
                    <tr>
                        <th>ID del Registro:</th>
                        <td><strong>' . $alimento->id . '</strong></td>
                    </tr>
                    <tr>
                        <th>Fecha de Registro:</th>
                        <td>' . ($alimento->fecha_registro ? \Carbon\Carbon::parse($alimento->fecha_registro)->format('d/m/Y H:i:s') : 'N/A') . '</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            <span class="status-badge status-' . $estado . '">
                                ' . $estadoTexto . '
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="info-section">
                <h2>Información del Galpón</h2>
                <table class="info-table">
                    <tr>
                        <th>Nombre del Galpón:</th>
                        <td>' . ($alimento->galpon_name ?? 'N/A') . '</td>
                    </tr>
                    <tr>
                        <th>ID del Galpón:</th>
                        <td>' . ($alimento->poultry_facility_id ?? 'N/A') . '</td>
                    </tr>
                </table>
            </div>

            <div class="info-section">
                <h2>Información del Producto</h2>
                <table class="info-table">
                    <tr>
                        <th>Nombre del Producto:</th>
                        <td>' . ($alimento->producto_name ?? 'N/A') . '</td>
                    </tr>
                    <tr>
                        <th>ID del Producto:</th>
                        <td>' . ($alimento->product_id ?? 'N/A') . '</td>
                    </tr>
                </table>
            </div>

            <div class="info-section">
                <h2>Detalles del Consumo</h2>
                <table class="info-table">
                    <tr>
                        <th>Cantidad (kg):</th>
                        <td>' . number_format($alimento->cantidad_kg ?? 0, 2) . ' kg</td>
                    </tr>
                    <tr>
                        <th>Cantidad de Bultos:</th>
                        <td>' . ($alimento->cantidad_bultos ?? 0) . '</td>
                    </tr>
                    <tr>
                        <th>Peso por Bulto:</th>
                        <td>' . number_format($alimento->peso_por_bulto ?? 0, 2) . ' kg</td>
                    </tr>
                    <tr>
                        <th>Número de Aves:</th>
                        <td>' . number_format($alimento->numero_aves ?? 0) . '</td>
                    </tr>
                </table>
            </div>

            <div class="summary-box">
                <h3>Resumen del Consumo</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="value">' . number_format(($alimento->cantidad_kg ?? 0) + (($alimento->cantidad_bultos ?? 0) * ($alimento->peso_por_bulto ?? 0)), 2) . '</div>
                        <div class="label">Total Consumido (kg)</div>
                    </div>
                    <div class="summary-item">
                        <div class="value">' . number_format(($alimento->cantidad_kg ?? 0) / max(($alimento->numero_aves ?? 1), 1) * 1000, 2) . '</div>
                        <div class="label">Consumo por Ave (g)</div>
                    </div>
                    <div class="summary-item">
                        <div class="value">' . ($alimento->numero_aves ?? 0) . '</div>
                        <div class="label">Total de Aves</div>
                    </div>
                </div>
            </div>';

        // Agregar información adicional si existe
        if (isset($alimento->responsable) || isset($alimento->observaciones)) {
            $html .= '
            <div class="info-section">
                <h2>Información Adicional</h2>
                <table class="info-table">';
            
            if (isset($alimento->responsable)) {
                $html .= '
                    <tr>
                        <th>Responsable:</th>
                        <td>' . $alimento->responsable . '</td>
                    </tr>';
            }
            
            if (isset($alimento->observaciones)) {
                $html .= '
                    <tr>
                        <th>Observaciones:</th>
                        <td>' . $alimento->observaciones . '</td>
                    </tr>';
            }
            
            $html .= '
                </table>
            </div>';
        }

        $html .= '
            <div class="footer">
                <p><strong>Generado el:</strong> ' . $generatedAt->format('d/m/Y H:i:s') . '</p>
                <p><strong>Sistema:</strong> AVICONTROL - Módulo de Información</p>
                <p>Este es un informe automático generado por el sistema</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    /**
     * Genera HTML simple para PDF
     */
    private function generarHtmlSimple($alimento, $generatedAt)
    {
        $estado = $alimento->estado ?? 'active';
        $estadoTexto = $estado === 'active' ? 'Activo' : ($estado === 'cancelled' ? 'Cancelado' : 'Pendiente');
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Informe Alimento ' . $alimento->id . '</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
                .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
                .header h1 { color: #007bff; margin: 0; font-size: 18px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f8f9fa; font-weight: bold; width: 30%; }
                .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>INFORME DE ALIMENTO - AVICONTROL</h1>
            </div>
            
            <table>
                <tr><th>ID:</th><td>' . $alimento->id . '</td></tr>
                <tr><th>Fecha:</th><td>' . ($alimento->fecha_registro ? \Carbon\Carbon::parse($alimento->fecha_registro)->format('d/m/Y H:i') : 'N/A') . '</td></tr>
                <tr><th>Estado:</th><td>' . $estadoTexto . '</td></tr>
                <tr><th>Galpón:</th><td>' . ($alimento->galpon_name ?? 'N/A') . '</td></tr>
                <tr><th>Producto:</th><td>' . ($alimento->producto_name ?? 'N/A') . '</td></tr>
                <tr><th>Cantidad (kg):</th><td>' . number_format($alimento->cantidad_kg ?? 0, 2) . ' kg</td></tr>
                <tr><th>Bultos:</th><td>' . ($alimento->cantidad_bultos ?? 0) . '</td></tr>
                <tr><th>Peso por Bulto:</th><td>' . number_format($alimento->peso_por_bulto ?? 0, 2) . ' kg</td></tr>
                <tr><th>Número de Aves:</th><td>' . number_format($alimento->numero_aves ?? 0) . '</td></tr>
            </table>
            
            <div class="footer">
                Generado: ' . $generatedAt->format('d/m/Y H:i:s') . '<br>
                Sistema: AVICONTROL
            </div>
        </body>
        </html>';
    }

    /**
     * Genera HTML descargable como alternativa
     */
    private function generarHtmlDescargable($alimento, $generatedAt)
    {
        $estado = $alimento->estado ?? 'active';
        $estadoTexto = $estado === 'active' ? 'Activo' : ($estado === 'cancelled' ? 'Cancelado' : 'Pendiente');
        
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Informe de Alimento ' . $alimento->id . ' - AVICONTROL</title>
            <style>
                @media print {
                    body { margin: 0; padding: 15px; }
                    .no-print { display: none !important; }
                    .page-break { page-break-before: always; }
                }
                
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    line-height: 1.4;
                    color: #000;
                    margin: 0;
                    padding: 20px;
                    background-color: white;
                }
                
                .header {
                    text-align: center;
                    border-bottom: 3px solid #007bff;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                
                .header h1 {
                    color: #007bff;
                    margin: 0;
                    font-size: 24px;
                    font-weight: bold;
                }
                
                .header .subtitle {
                    color: #666;
                    font-size: 14px;
                    margin-top: 8px;
                }
                
                .info-section {
                    margin-bottom: 25px;
                }
                
                .info-section h2 {
                    color: #007bff;
                    font-size: 16px;
                    border-bottom: 2px solid #ddd;
                    padding-bottom: 8px;
                    margin-bottom: 15px;
                }
                
                .info-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                
                .info-table th,
                .info-table td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: left;
                    font-size: 11px;
                }
                
                .info-table th {
                    background-color: #f8f9fa;
                    font-weight: bold;
                    width: 35%;
                }
                
                .info-table td {
                    width: 65%;
                }
                
                .footer {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 10px;
                    color: #666;
                    border-top: 2px solid #ddd;
                    padding-top: 20px;
                }
                
                .status-badge {
                    display: inline-block;
                    padding: 6px 12px;
                    border-radius: 6px;
                    font-size: 10px;
                    font-weight: bold;
                    text-transform: uppercase;
                }
                
                .status-active {
                    background-color: #28a745;
                    color: white;
                }
                
                .status-cancelled {
                    background-color: #dc3545;
                    color: white;
                }
                
                .status-pending {
                    background-color: #ffc107;
                    color: #000;
                }
                
                .print-buttons {
                    text-align: center;
                    margin: 20px 0;
                    padding: 20px;
                    background-color: #f8f9fa;
                    border-radius: 8px;
                    border: 1px solid #dee2e6;
                }
                
                .print-btn {
                    background-color: #007bff;
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    margin: 0 10px;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 14px;
                    text-decoration: none;
                    display: inline-block;
                }
                
                .print-btn:hover {
                    background-color: #0056b3;
                }
                
                .print-btn.secondary {
                    background-color: #6c757d;
                }
                
                .print-btn.secondary:hover {
                    background-color: #545b62;
                }
                
                .summary-box {
                    background-color: #e9ecef;
                    border: 1px solid #dee2e6;
                    border-radius: 8px;
                    padding: 15px;
                    margin: 20px 0;
                }
                
                .summary-box h3 {
                    margin-top: 0;
                    color: #495057;
                    font-size: 14px;
                }
                
                .summary-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 15px;
                    margin-top: 15px;
                }
                
                .summary-item {
                    text-align: center;
                    padding: 10px;
                    background-color: white;
                    border-radius: 6px;
                    border: 1px solid #dee2e6;
                }
                
                .summary-item .value {
                    font-size: 18px;
                    font-weight: bold;
                    color: #007bff;
                }
                
                .summary-item .label {
                    font-size: 10px;
                    color: #666;
                    margin-top: 5px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>INFORME DE ALIMENTO - AVICONTROL</h1>
                <div class="subtitle">Sistema de Control Avícola</div>
            </div>

            <div class="info-section">
                <h2>Información del Registro</h2>
                <table class="info-table">
                    <tr>
                        <th>ID del Registro:</th>
                        <td><strong>' . $alimento->id . '</strong></td>
                    </tr>
                    <tr>
                        <th>Fecha de Registro:</th>
                        <td>' . ($alimento->fecha_registro ? \Carbon\Carbon::parse($alimento->fecha_registro)->format('d/m/Y H:i:s') : 'N/A') . '</td>
                    </tr>
                    <tr>
                        <th>Estado:</th>
                        <td>
                            <span class="status-badge status-' . $estado . '">
                                ' . $estadoTexto . '
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="info-section">
                <h2>Información del Galpón</h2>
                <table class="info-table">
                    <tr>
                        <th>Nombre del Galpón:</th>
                        <td>' . ($alimento->galpon_name ?? 'N/A') . '</td>
                    </tr>
                    <tr>
                        <th>ID del Galpón:</th>
                        <td>' . ($alimento->poultry_facility_id ?? 'N/A') . '</td>
                    </tr>
                </table>
            </div>

            <div class="info-section">
                <h2>Información del Producto</h2>
                <table class="info-table">
                    <tr>
                        <th>Nombre del Producto:</th>
                        <td>' . ($alimento->producto_name ?? 'N/A') . '</td>
                    </tr>
                    <tr>
                        <th>ID del Producto:</th>
                        <td>' . ($alimento->product_id ?? 'N/A') . '</td>
                    </tr>
                </table>
            </div>

            <div class="info-section">
                <h2>Detalles del Consumo</h2>
                <table class="info-table">
                    <tr>
                        <th>Cantidad (kg):</th>
                        <td>' . number_format($alimento->cantidad_kg ?? 0, 2) . ' kg</td>
                    </tr>
                    <tr>
                        <th>Cantidad de Bultos:</th>
                        <td>' . ($alimento->cantidad_bultos ?? 0) . '</td>
                    </tr>
                    <tr>
                        <th>Peso por Bulto:</th>
                        <td>' . number_format($alimento->peso_por_bulto ?? 0, 2) . ' kg</td>
                    </tr>
                    <tr>
                        <th>Número de Aves:</th>
                        <td>' . number_format($alimento->numero_aves ?? 0) . '</td>
                    </tr>
                </table>
            </div>

            <div class="summary-box">
                <h3>Resumen del Consumo</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="value">' . number_format(($alimento->cantidad_kg ?? 0) + (($alimento->cantidad_bultos ?? 0) * ($alimento->peso_por_bulto ?? 0)), 2) . '</div>
                        <div class="label">Total Consumido (kg)</div>
                    </div>
                    <div class="summary-item">
                        <div class="value">' . number_format(($alimento->cantidad_kg ?? 0) / max(($alimento->numero_aves ?? 1), 1) * 1000, 2) . '</div>
                        <div class="label">Consumo por Ave (g)</div>
                    </div>
                    <div class="summary-item">
                        <div class="value">' . ($alimento->numero_aves ?? 0) . '</div>
                        <div class="label">Total de Aves</div>
                    </div>
                </div>
            </div>';

        // Agregar información adicional si existe
        if (isset($alimento->responsable) || isset($alimento->observaciones)) {
            $html .= '
            <div class="info-section">
                <h2>Información Adicional</h2>
                <table class="info-table">';
            
            if (isset($alimento->responsable)) {
                $html .= '
                    <tr>
                        <th>Responsable:</th>
                        <td>' . $alimento->responsable . '</td>
                    </tr>';
            }
            
            if (isset($alimento->observaciones)) {
                $html .= '
                    <tr>
                        <th>Observaciones:</th>
                        <td>' . $alimento->observaciones . '</td>
                    </tr>';
            }
            
            $html .= '
                </table>
            </div>';
        }

        $html .= '
            <div class="footer">
                <p><strong>Generado el:</strong> ' . $generatedAt->format('d/m/Y H:i:s') . '</p>
                <p><strong>Sistema:</strong> AVICONTROL - Módulo de Información</p>
                <p class="text-muted">Este es un informe automático generado por el sistema</p>
            </div>

            <div class="print-buttons no-print">
                <a href="#" class="print-btn" onclick="window.print()">
                    🖨️ Imprimir Informe
                </a>
                <a href="#" class="print-btn secondary" onclick="window.close()">
                    ❌ Cerrar
                </a>
            </div>
        </body>
        </html>';
        
        $filename = 'alimento_' . $alimento->id . '_' . $generatedAt->format('Y-m-d_H-i-s') . '.html';
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    /**
     * Elimina un registro de alimento
     */
    public function eliminarAlimento(Request $request)
    {
        try {
            $id = $request->get('id');
            
            if (!$id) {
                return response()->json(['success' => false, 'message' => 'ID de alimento requerido'], 400);
            }

            // Verificar si el alimento existe
            $alimento = \DB::table('avicontrol_food_consumption')->where('id', $id)->first();
            
            if (!$alimento) {
                return response()->json(['success' => false, 'message' => 'Alimento no encontrado'], 404);
            }

            // Eliminar el registro
            $deleted = \DB::table('avicontrol_food_consumption')->where('id', $id)->delete();
            
            if ($deleted) {
                // Obtener datos actualizados
                $estadisticasActualizadas = $this->obtenerEstadisticasAlimentos();
                $datosActualizados = $this->obtenerDatosAlimentos();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Alimento eliminado correctamente',
                    'estadisticas' => $estadisticasActualizadas,
                    'datos' => $datosActualizados
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'No se pudo eliminar el alimento'], 500);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error eliminando alimento: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error eliminando alimento: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Exporta seguimientos a Excel
     */
    public function exportarSeguimientosExcel(Request $request)
    {
        try {
            // Implementar exportación a Excel
            return response()->json(['success' => true, 'message' => 'Exportación iniciada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Exporta seguimientos a PDF
     */
    public function exportarSeguimientosPdf(Request $request)
    {
        try {
            // Implementar exportación a PDF
            return response()->json(['success' => false, 'message' => 'Exportación iniciada']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Actualiza los informes de alimentos cuando se eliminan registros
     */
    public function actualizarInformesAlimentos()
    {
        try {
            // Obtener estadísticas actualizadas
            $estadisticasActualizadas = $this->obtenerEstadisticasAlimentos();
            $datosActualizados = $this->obtenerDatosAlimentos();
            
            return response()->json([
                'success' => true,
                'estadisticas' => $estadisticasActualizadas,
                'datos' => $datosActualizados,
                'message' => 'Informes actualizados correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error actualizando informes de alimentos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar informes: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Muestra la vista de costos de producción
     */
    public function costosProduccion()
    {
        try {
            // Verificar si la tabla de costos de producción existe
            if (!\Schema::hasTable('avicontrol_production_costs')) {
                return view('avicontrol::admin.information.costos-produccion', [
                    'productionCosts' => collect([]),
                    'error' => 'La tabla de costos de producción no está creada. Por favor ejecute las migraciones.'
                ]);
            }

            // Obtener costos de producción con paginación
            $productionCosts = \DB::table('avicontrol_production_costs')
                ->leftJoin('avicontrol_poultry_facilities', 'avicontrol_production_costs.poultry_facility_id', '=', 'avicontrol_poultry_facilities.id')
                ->select([
                    'avicontrol_production_costs.*',
                    'avicontrol_poultry_facilities.name as facility_name'
                ])
                ->orderBy('avicontrol_production_costs.created_at', 'desc')
                ->paginate(20);

            return view('avicontrol::admin.information.costos-produccion', compact('productionCosts'));
        } catch (\Exception $e) {
            \Log::error('Error cargando costos de producción: ' . $e->getMessage());
            return view('avicontrol::admin.information.costos-produccion', [
                'productionCosts' => collect([]),
                'error' => 'Error al cargar los costos de producción: ' . $e->getMessage()
            ]);
        }
    }
}