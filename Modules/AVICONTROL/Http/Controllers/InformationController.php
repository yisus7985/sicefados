<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Modules\AVICONTROL\Entities\Bird;
use Modules\AVICONTROL\Entities\InventoryProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

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

            $galpones = PoultryFacility::all();
            return view('avicontrol::admin.information.index', compact('galpones'));
        } catch (\Exception $e) {
            return view('avicontrol::admin.information.index', [
                'galpones' => collect([]),
                'error' => 'Error al cargar los informes: ' . $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        $galpon = PoultryFacility::with('birds')->findOrFail($id);
        return view('avicontrol::admin.information.show', compact('galpon'));
    }

    public function inventory()
    {
        try {
            // Verificar si las tablas existen
            if (!\Schema::hasTable('avicontrol_inventory_products')) {
                return view('avicontrol::admin.information.inventory', [
                    'products' => collect([]),
                    'stats' => [
                        'total_products' => 0,
                        'low_stock_products' => 0,
                        'expiring_products' => 0,
                        'total_value' => 0
                    ],
                    'recent_activity' => [],
                    'error' => 'Las tablas de inventario no están creadas. Por favor ejecute las migraciones.'
                ]);
            }

            $products = InventoryProduct::with('movements')
                ->orderBy('name')
                ->paginate(15);

            $stats = [
                'total_products' => InventoryProduct::count(),
                'low_stock_products' => InventoryProduct::lowStock()->count(),
                'expiring_products' => InventoryProduct::expiringSoon()->count(),
                'total_value' => InventoryProduct::sum(DB::raw('current_stock * unit_price'))
            ];

            // Actividad reciente de ejemplo
            $recent_activity = [
                [
                    'title' => 'Actualización de Inventario',
                    'description' => 'Se actualizó el inventario de productos',
                    'time' => 'Hace 1 hora',
                ],
                [
                    'title' => 'Nuevo Producto',
                    'description' => 'Se agregó un nuevo producto al inventario',
                    'time' => 'Hace 2 horas',
                ],
                [
                    'title' => 'Stock Bajo',
                    'description' => 'El producto X está por debajo del stock mínimo',
                    'time' => 'Hace 3 horas',
                ],
            ];

            return view('avicontrol::admin.information.inventory', compact('products', 'stats', 'recent_activity'));
        } catch (\Exception $e) {
            return view('avicontrol::admin.information.inventory', [
                'products' => collect([]),
                'stats' => [
                    'total_products' => 0,
                    'low_stock_products' => 0,
                    'expiring_products' => 0,
                    'total_value' => 0
                ],
                'recent_activity' => [],
                'error' => 'Error al cargar el inventario: ' . $e->getMessage()
            ]);
        }
    }

    // Métodos para generar PDF

    public function downloadAllProductsPDF()
    {
        try {
            $products = InventoryProduct::all();
            $pdf = Pdf::loadView('avicontrol::admin.information.pdf.inventory_report', compact('products'));
            return $pdf->download('reporte_completo_inventario.pdf');
        } catch (Exception $e) {
            return back()->with('error', 'No se pudo generar el PDF: ' . $e->getMessage());
        }
    }

    public function downloadProductsByDatePDF(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $products = InventoryProduct::whereBetween('created_at', [$startDate, $endDate])->get();

            $pdf = Pdf::loadView('avicontrol::admin.information.pdf.inventory_report', compact('products', 'startDate', 'endDate'));
            return $pdf->download('reporte_inventario_por_fechas.pdf');
        } catch (Exception $e) {
            return back()->with('error', 'No se pudo generar el PDF: ' . $e->getMessage());
        }
    }

    public function downloadProductPDF($id)
    {
        try {
            $product = InventoryProduct::findOrFail($id);
            $pdf = Pdf::loadView('avicontrol::admin.information.pdf.product_report', compact('product'));
            return $pdf->download('reporte_producto_' . $product->code . '.pdf');
        } catch (Exception $e) {
            return back()->with('error', 'No se pudo generar el PDF: ' . $e->getMessage());
        }
    }

    public function downloadGalponPDF($id)
    {
        try {
            $galpon = PoultryFacility::with('birds')->findOrFail($id);
            $pdf = Pdf::loadView('avicontrol::admin.information.pdf.galpon_report', compact('galpon'));
            return $pdf->download('reporte_galpon_' . str_replace(' ', '_', $galpon->name) . '.pdf');
        } catch (Exception $e) {
            return back()->with('error', 'No se pudo generar el PDF: ' . $e->getMessage());
        }
    }

    public function downloadAllGalponesPDF()
    {
        try {
            $galpones = PoultryFacility::with('birds')->get();
            $pdf = Pdf::loadView('avicontrol::admin.information.pdf.all_galpones_report', compact('galpones'));
            return $pdf->download('reporte_completo_galpones.pdf');
        } catch (Exception $e) {
            return back()->with('error', 'No se pudo generar el PDF: ' . $e->getMessage());
        }
    }

    public function downloadGalponesByDatePDF(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $galpones = PoultryFacility::with('birds')
                ->whereBetween('creation_date', [$startDate, $endDate])
                ->get();

            $pdf = Pdf::loadView('avicontrol::admin.information.pdf.all_galpones_report', compact('galpones', 'startDate', 'endDate'));
            return $pdf->download('reporte_galpones_por_fecha.pdf');
        } catch (Exception $e) {
            return back()->with('error', 'No se pudo generar el PDF: ' . $e->getMessage());
        }
    }
}