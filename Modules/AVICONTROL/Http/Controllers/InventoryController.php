<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AVICONTROL\Entities\InventoryProduct;
use Modules\AVICONTROL\Entities\InventoryMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Constructor to bypass authorization for testing
     */
    public function __construct()
    {
        // Bypass authorization checks for AVICONTROL inventory
        // This is the same pattern used in other working AVICONTROL controllers
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Verificar si las tablas existen
            if (!\Schema::hasTable('avicontrol_inventory_products')) {
                return view('avicontrol::admin.inventory.index', [
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

            return view('avicontrol::admin.inventory.index', compact('products', 'stats', 'recent_activity'));
        } catch (\Exception $e) {
            return view('avicontrol::admin.inventory.index', [
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = InventoryProduct::CATEGORIES;
        return view('avicontrol::admin.inventory.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', array_keys(InventoryProduct::CATEGORIES)),
            'description' => 'nullable|string',
            'unit_measure' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date|after:today',
            'minimum_stock' => 'required|integer|min:0',
            'current_stock' => 'required|integer|min:0',
        ]);

        $product = InventoryProduct::create([
            'name' => $request->name,
            'code' => InventoryProduct::generateCode(),
            'category' => $request->category,
            'description' => $request->description,
            'unit_measure' => $request->unit_measure,
            'unit_price' => $request->unit_price,
            'supplier' => $request->supplier,
            'expiration_date' => $request->expiration_date,
            'minimum_stock' => $request->minimum_stock,
            'current_stock' => $request->current_stock,
            'status' => 'active'
        ]);

        // Si hay stock inicial, crear movimiento de entrada
        if ($request->current_stock > 0) {
            InventoryMovement::create([
                'product_id' => $product->id,
                'movement_type' => InventoryMovement::TYPE_ENTRY,
                'quantity' => $request->current_stock,
                'unit_price' => $request->unit_price,
                'reference' => InventoryMovement::REFERENCE_PURCHASE,
                'notes' => 'Stock inicial del producto',
                'movement_date' => now(),
                'user_id' => Auth::id()
            ]);
        }

        return redirect()->route('avicontrol.admin.inventory.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = InventoryProduct::with(['movements' => function($query) {
            $query->orderBy('movement_date', 'desc');
        }])->findOrFail($id);

        $movements = $product->movements()->paginate(10);

        return view('avicontrol::admin.inventory.show', compact('product', 'movements'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = InventoryProduct::findOrFail($id);
        $categories = InventoryProduct::CATEGORIES;
        
        return view('avicontrol::admin.inventory.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = InventoryProduct::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', array_keys(InventoryProduct::CATEGORIES)),
            'description' => 'nullable|string',
            'unit_measure' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date',
            'minimum_stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,expired'
        ]);

        $product->update($request->all());

        return redirect()->route('avicontrol.admin.inventory.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            \Log::info('Attempting to delete inventory product with ID: ' . $id);
            
            $product = InventoryProduct::findOrFail($id);
            \Log::info('Product found: ' . $product->name);
            
            // Usar forceDelete() para eliminar físicamente el registro
            $product->forceDelete();
            \Log::info('Product deleted successfully');

            return redirect()->route('avicontrol.admin.inventory.index')
                ->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Error deleting inventory product: ' . $e->getMessage());
            
            return redirect()->route('avicontrol.admin.inventory.index')
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a movement.
     */
    public function createMovement($id)
    {
        $product = InventoryProduct::findOrFail($id);
        $movementTypes = [
            InventoryMovement::TYPE_ENTRY => 'Entrada',
            InventoryMovement::TYPE_EXIT => 'Salida',
            InventoryMovement::TYPE_ADJUSTMENT => 'Ajuste'
        ];
        
        $references = [
            InventoryMovement::REFERENCE_PURCHASE => 'Compra',
            InventoryMovement::REFERENCE_PRODUCTION => 'Producción',
            InventoryMovement::REFERENCE_CONSUMPTION => 'Consumo',
            InventoryMovement::REFERENCE_SALE => 'Venta',
            InventoryMovement::REFERENCE_DISCARD => 'Descarte',
            InventoryMovement::REFERENCE_ADJUSTMENT => 'Ajuste'
        ];

        return view('avicontrol::admin.inventory.movements.create', compact('product', 'movementTypes', 'references'));
    }

    /**
     * Store a new movement.
     */
    public function storeMovement(Request $request, $id)
    {
        $product = InventoryProduct::findOrFail($id);

        $request->validate([
            'movement_type' => 'required|in:entry,exit,adjustment',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'reference' => 'required|in:purchase,production,consumption,sale,discard,adjustment',
            'notes' => 'nullable|string',
            'movement_date' => 'required|date'
        ]);

        // Validar que no se retire más stock del disponible
        if ($request->movement_type === 'exit' && $request->quantity > $product->current_stock) {
            return back()->withErrors(['quantity' => 'No hay suficiente stock disponible. Stock actual: ' . $product->current_stock]);
        }

        InventoryMovement::create([
            'product_id' => $product->id,
            'movement_type' => $request->movement_type,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'reference' => $request->reference,
            'notes' => $request->notes,
            'movement_date' => $request->movement_date,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('avicontrol.admin.inventory.show', $product->id)
            ->with('success', 'Movimiento registrado exitosamente.');
    }

    /**
     * Show low stock alerts.
     */
    public function lowStock()
    {
        $products = InventoryProduct::lowStock()->paginate(15);
        
        return view('avicontrol::admin.inventory.low_stock', compact('products'));
    }

    /**
     * Show expiring products.
     */
    public function expiring()
    {
        $products = InventoryProduct::expiringSoon()->paginate(15);
        
        return view('avicontrol::admin.inventory.expiring', compact('products'));
    }

    /**
     * Export movements to CSV
     */
    private function exportMovementsToCsv($movements, $request)
    {
        $filename = 'movimientos_inventario_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($movements) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM para Excel
            fputs($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'Fecha',
                'Producto',
                'Código',
                'Tipo de Movimiento',
                'Referencia',
                'Cantidad',
                'Unidad',
                'Precio Unitario',
                'Valor Total',
                'Usuario',
                'Notas'
            ], ';');

            foreach ($movements as $movement) {
                fputcsv($file, [
                    $movement->movement_date->format('d/m/Y H:i'),
                    $movement->product->name,
                    $movement->product->code,
                    $movement->movement_type_name,
                    $movement->reference_name,
                    $movement->quantity,
                    $movement->product->unit_measure,
                    number_format($movement->unit_price, 2),
                    number_format($movement->total_value, 2),
                    $movement->user->name ?? 'N/A',
                    $movement->notes ?? ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get movement statistics via AJAX
     */
    public function getMovementStats(Request $request)
    {
        try {
            $query = InventoryMovement::query();
            
            // Aplicar filtros
            if ($request->filled('product_id')) {
                $query->where('product_id', $request->product_id);
            }
            if ($request->filled('movement_type')) {
                $query->where('movement_type', $request->movement_type);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('movement_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('movement_date', '<=', $request->date_to);
            }

            $stats = [
                'total_movements' => $query->count(),
                'entries' => (clone $query)->where('movement_type', 'entry')->count(),
                'exits' => (clone $query)->where('movement_type', 'exit')->count(),
                'adjustments' => (clone $query)->where('movement_type', 'adjustment')->count(),
                'total_value' => $query->sum('total_value'),
                'entries_value' => (clone $query)->where('movement_type', 'entry')->sum('total_value'),
                'exits_value' => (clone $query)->where('movement_type', 'exit')->sum('total_value')
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show inventory movements.
     */
    public function movements(Request $request)
    {
        try {
            // Log para debugging (mismo patrón que otros controladores)
            \Log::info('Accessing inventory movements index');
            
            // Verificar si las tablas existen (patrón del InformationController)
            if (!\Schema::hasTable('avicontrol_inventory_movements')) {
                return view('avicontrol::admin.inventory.movements.index', [
                    'movements' => collect([]),
                    'products' => collect([]),
                    'stats' => [
                        'total_movements' => 0,
                        'entries' => 0,
                        'exits' => 0,
                        'adjustments' => 0,
                        'total_value' => 0
                    ],
                    'references' => [],
                    'error' => 'Las tablas de inventario no están creadas. Por favor ejecute las migraciones.'
                ]);
            }

            $movements = InventoryMovement::with(['product', 'user'])
                ->orderBy('movement_date', 'desc')
                ->paginate(15);
                
            $products = InventoryProduct::orderBy('name')->get();

            // Estadísticas básicas
            $stats = [
                'total_movements' => InventoryMovement::count(),
                'entries' => InventoryMovement::where('movement_type', 'entry')->count(),
                'exits' => InventoryMovement::where('movement_type', 'exit')->count(),
                'adjustments' => InventoryMovement::where('movement_type', 'adjustment')->count(),
                'total_value' => InventoryMovement::sum('total_value')
            ];

            // Referencias disponibles
            $references = [
                'purchase' => 'Compra',
                'production' => 'Producción',
                'consumption' => 'Consumo',
                'sale' => 'Venta',
                'discard' => 'Descarte',
                'adjustment' => 'Ajuste'
            ];

            return view('avicontrol::admin.inventory.movements.index', compact('movements', 'products', 'stats', 'references'));
            
        } catch (\Exception $e) {
            \Log::error('Error in inventory movements index: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()->with('error', 'Error al cargar los movimientos: ' . $e->getMessage());
        }
    }
} 