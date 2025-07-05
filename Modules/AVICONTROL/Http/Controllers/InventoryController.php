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
     * Display a listing of the resource.
     */
    public function index()
    {
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
        $product = InventoryProduct::findOrFail($id);
        $product->delete();

        return redirect()->route('avicontrol.admin.inventory.index')
            ->with('success', 'Producto eliminado exitosamente.');
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
     * Show inventory movements.
     */
    public function movements(Request $request)
    {
        $query = InventoryMovement::with(['product', 'user']);

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

        $movements = $query->orderBy('movement_date', 'desc')->paginate(15);
        $products = InventoryProduct::orderBy('name')->get();

        return view('avicontrol::admin.inventory.movements.index', compact('movements', 'products'));
    }
} 