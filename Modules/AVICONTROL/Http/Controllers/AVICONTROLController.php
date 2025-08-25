<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AVICONTROLController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('avicontrol::index');
    }

   
    public function welcome()
    {
        return view('avicontrol::welcome');
    }

    public function admin()
    {
        try {
            // Obtener alertas dinámicas para el dashboard
            $alertController = new \Modules\AVICONTROL\Http\Controllers\AlertController();
            $dashboardAlerts = $alertController->getDashboardAlerts();
            
            // Obtener estadísticas reales de la base de datos
            $estadisticas = $this->obtenerEstadisticasDashboard();
            
            // Obtener datos para la gráfica de producción
            $datosProduccion = $this->obtenerDatosGraficaProduccion();
            
            return view('avicontrol::admin.welcome', compact('dashboardAlerts', 'estadisticas', 'datosProduccion'));
        } catch (\Exception $e) {
            \Log::error('Error en dashboard admin: ' . $e->getMessage());
            
            // Valores por defecto en caso de error
            $dashboardAlerts = [];
            $estadisticas = [
                'instalaciones_activas' => 0,
                'produccion_diaria' => 0,
                'tasa_postura' => 0,
                'alertas_pendientes' => 0
            ];
            $datosProduccion = [
                'labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                'data' => [0, 0, 0, 0, 0, 0, 0]
            ];
            
            return view('avicontrol::admin.welcome', compact('dashboardAlerts', 'estadisticas', 'datosProduccion'));
        }
    }

    /**
     * Obtiene las estadísticas principales del dashboard
     */
    private function obtenerEstadisticasDashboard()
    {
        // Instalaciones activas
        $instalacionesActivas = \DB::table('avicontrol_poultry_facilities')
            ->where('status', 'active')
            ->count();

        // Producción del día actual
        $hoy = now()->toDateString();
        $produccionHoy = \DB::table('avicontrol_productions')
            ->whereDate('fecha', $hoy)
            ->where('estado', 'activo')
            ->sum('cantidad') ?? 0;

        // Calcular tasa de postura (producción de huevos vs aves activas)
        $avesActivas = \DB::table('avicontrol_birds')
            ->where('status', 'active')
            ->sum('quantity') ?? 1; // Evitar división por cero

        $produccionHuevosHoy = \DB::table('avicontrol_productions')
            ->whereDate('fecha', $hoy)
            ->where('tipo_produccion', 'huevos')
            ->where('estado', 'activo')
            ->sum('cantidad') ?? 0;

        $tasaPostura = $avesActivas > 0 ? round(($produccionHuevosHoy / $avesActivas) * 100, 1) : 0;

        // Alertas pendientes
        $alertasPendientes = count($this->getDashboardAlerts());

        return [
            'instalaciones_activas' => $instalacionesActivas,
            'produccion_diaria' => $produccionHoy,
            'tasa_postura' => $tasaPostura,
            'alertas_pendientes' => $alertasPendientes
        ];
    }

    /**
     * Obtiene los datos para la gráfica de producción de los últimos 7 días
     */
    private function obtenerDatosGraficaProduccion()
    {
        $labels = [];
        $data = [];
        
        // Obtener datos de los últimos 7 días
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->subDays($i);
            $labels[] = $fecha->locale('es')->format('D'); // Lun, Mar, etc.
            
            $produccion = \DB::table('avicontrol_productions')
                ->whereDate('fecha', $fecha->toDateString())
                ->where('estado', 'activo')
                ->sum('cantidad') ?? 0;
                
            $data[] = $produccion;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Obtiene las alertas del dashboard (método auxiliar)
     */
    private function getDashboardAlerts()
    {
        try {
            $alertController = new \Modules\AVICONTROL\Http\Controllers\AlertController();
            return $alertController->getDashboardAlerts();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function create()
    {
        return view('avicontrol::create');
    }


    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('avicontrol::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('avicontrol::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Exporta el dashboard a PDF
     */
    public function exportDashboardPDF()
    {
        try {
            \Log::info('Iniciando exportación de dashboard a PDF');
            
            // Verificar que la librería PDF esté disponible
            if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'La librería de PDF no está disponible.'
                ], 500);
            }
            
            // Obtener datos para el dashboard
            $alertController = new \Modules\AVICONTROL\Http\Controllers\AlertController();
            $dashboardAlerts = $alertController->getDashboardAlerts();
            $estadisticas = $this->obtenerEstadisticasDashboard();
            $datosProduccion = $this->obtenerDatosGraficaProduccion();
            
            // Obtener datos adicionales para el PDF
            $datosAdicionales = $this->obtenerDatosAdicionalesPDF();
            
            $generatedAt = now()->format('d/m/Y H:i:s');
            
            // Generar PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('avicontrol::admin.pdf.dashboard-report', [
                'estadisticas' => $estadisticas,
                'datosProduccion' => $datosProduccion,
                'dashboardAlerts' => $dashboardAlerts,
                'datosAdicionales' => $datosAdicionales,
                'generatedAt' => $generatedAt
            ]);
            
            $pdf->setPaper('A4', 'portrait');
            
            $fileName = 'dashboard_avicontrol_' . now()->format('Y-m-d') . '.pdf';
            
            \Log::info('Dashboard PDF generado exitosamente: ' . $fileName);
            
            return $pdf->download($fileName);
            
        } catch (\Exception $e) {
            \Log::error('Error generando PDF del dashboard: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error al generar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene datos adicionales para el PDF del dashboard
     */
    private function obtenerDatosAdicionalesPDF()
    {
        // Top 5 galpones más productivos
        $topGalpones = \DB::table('avicontrol_productions as p')
            ->join('avicontrol_poultry_facilities as g', 'p.galpon_id', '=', 'g.id')
            ->select('g.name', \DB::raw('SUM(p.cantidad) as total_produccion'))
            ->where('p.estado', 'activo')
            ->whereDate('p.fecha', '>=', now()->subDays(30))
            ->groupBy('g.id', 'g.name')
            ->orderBy('total_produccion', 'desc')
            ->limit(5)
            ->get();

        // Producción por tipo en el último mes
        $produccionPorTipo = \DB::table('avicontrol_productions')
            ->select('tipo_produccion', \DB::raw('SUM(cantidad) as total'))
            ->where('estado', 'activo')
            ->whereDate('fecha', '>=', now()->subDays(30))
            ->groupBy('tipo_produccion')
            ->get();

        // Resumen de inventario
        $inventarioResumen = [
            'productos_totales' => \DB::table('avicontrol_inventory_products')->count(),
            'productos_bajo_stock' => \DB::table('avicontrol_inventory_products')->where('current_stock', '<=', 'min_stock')->count(),
            'movimientos_mes' => \DB::table('avicontrol_inventory_movements')->whereMonth('created_at', now()->month)->count()
        ];

        return [
            'top_galpones' => $topGalpones,
            'produccion_por_tipo' => $produccionPorTipo,
            'inventario_resumen' => $inventarioResumen
        ];
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
