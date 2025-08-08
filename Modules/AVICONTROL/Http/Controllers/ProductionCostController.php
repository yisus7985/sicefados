<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AVICONTROL\Entities\ProductionCost;
use Modules\AVICONTROL\Entities\CostComponent;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Modules\AVICONTROL\Entities\Bird;
use Modules\AVICONTROL\Entities\InventoryProduct;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductionCostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            Log::info('Accessing production costs index');
            
            // Verificar si las tablas existen
            if (!\Schema::hasTable('avicontrol_production_costs')) {
                return view('avicontrol::admin.production_costs.index', [
                    'productionCosts' => collect([]),
                    'poultryFacilities' => collect([]),
                    'error' => 'Las tablas de la base de datos no están creadas. Por favor ejecute las migraciones.'
                ]);
            }
            
            $query = ProductionCost::with(['poultryFacility', 'bird']);
            
            // Filtros
            if ($request->filled('facility_id')) {
                $query->where('poultry_facility_id', $request->facility_id);
            }
            
            if ($request->filled('cost_type')) {
                $query->where('cost_type', $request->cost_type);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('date_from')) {
                $query->where('period_start', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->where('period_end', '<=', $request->date_to);
            }
            
            $productionCosts = $query->orderBy('created_at', 'desc')->paginate(15);
            $poultryFacilities = PoultryFacility::where('status', 'active')->get();
            
            return view('avicontrol::admin.production_costs.index', compact('productionCosts', 'poultryFacilities'));
        } catch (\Exception $e) {
            Log::error('Error in production costs index: ' . $e->getMessage());
            return view('avicontrol::admin.production_costs.index', [
                'productionCosts' => collect([]),
                'poultryFacilities' => collect([]),
                'error' => 'Error al cargar los costos de producción: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        try {
            Log::info('Accessing production cost create form');
            
            $poultryFacilities = PoultryFacility::where('status', 'active')->get();
            $birds = Bird::where('status', 'active')->get();
            $inventoryProducts = InventoryProduct::where('status', 'active')->get();
            
            return view('avicontrol::admin.production_costs.create', compact('poultryFacilities', 'birds', 'inventoryProducts'));
        } catch (\Exception $e) {
            Log::error('Error in production cost create form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el formulario: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        try {
            Log::info('Storing new production cost', $request->all());
            
            $validator = Validator::make($request->all(), [
                'poultry_facility_id' => 'required|exists:avicontrol_poultry_facilities,id',
                'bird_id' => 'nullable|exists:avicontrol_birds,id',
                'batch_code' => 'nullable|string|max:50',
                'period_start' => 'required|date',
                'period_end' => 'required|date|after_or_equal:period_start',
                'cost_type' => 'required|in:batch,period',
                'concentrate_cost' => 'nullable|numeric|min:0',
                'water_cost' => 'nullable|numeric|min:0',
                'energy_cost' => 'nullable|numeric|min:0',
                'medication_cost' => 'nullable|numeric|min:0',
                'biological_cost' => 'nullable|numeric|min:0',
                'packaging_cost' => 'nullable|numeric|min:0',
                'labor_cost' => 'nullable|numeric|min:0',
                'maintenance_cost' => 'nullable|numeric|min:0',
                'other_costs' => 'nullable|numeric|min:0',
                'unit_type' => 'nullable|in:egg,kg_meat,bird',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for production cost creation', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            $productionCost = new ProductionCost($request->all());
            $productionCost->calculateTotalCost();
            $productionCost->save();

            // Crear componentes de costo si se proporcionan
            if ($request->has('components') && is_array($request->components)) {
                foreach ($request->components as $componentData) {
                    if (!empty($componentData['component_name'])) {
                        $component = new CostComponent($componentData);
                        $component->production_cost_id = $productionCost->id;
                        $component->calculateTotalCost();
                        $component->save();
                    }
                }
            }

            DB::commit();

            Log::info('Production cost created successfully', ['id' => $productionCost->id]);
            return redirect()->route('avicontrol.admin.production_costs.show', $productionCost->id)
                ->with('success', 'Costo de producción creado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating production cost: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al crear el costo de producción: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        try {
            Log::info('Showing production cost', ['id' => $id]);
            
            $productionCost = ProductionCost::with([
                'poultryFacility', 
                'bird', 
                'costComponents'
            ])->findOrFail($id);
            
            return view('avicontrol::admin.production_costs.show', compact('productionCost'));
        } catch (\Exception $e) {
            Log::error('Error showing production cost: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al mostrar el costo de producción: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        try {
            Log::info('Accessing production cost edit form', ['id' => $id]);
            
            $productionCost = ProductionCost::with('costComponents')->findOrFail($id);
            $poultryFacilities = PoultryFacility::where('status', 'active')->get();
            $birds = Bird::where('status', 'active')->get();
            $inventoryProducts = InventoryProduct::where('status', 'active')->get();
            
            return view('avicontrol::admin.production_costs.edit', compact('productionCost', 'poultryFacilities', 'birds', 'inventoryProducts'));
        } catch (\Exception $e) {
            Log::error('Error in production cost edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el formulario de edición: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Updating production cost', ['id' => $id, 'data' => $request->all()]);
            
            $productionCost = ProductionCost::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'poultry_facility_id' => 'required|exists:avicontrol_poultry_facilities,id',
                'bird_id' => 'nullable|exists:avicontrol_birds,id',
                'batch_code' => 'nullable|string|max:50',
                'period_start' => 'required|date',
                'period_end' => 'required|date|after_or_equal:period_start',
                'cost_type' => 'required|in:batch,period',
                'concentrate_cost' => 'nullable|numeric|min:0',
                'water_cost' => 'nullable|numeric|min:0',
                'energy_cost' => 'nullable|numeric|min:0',
                'medication_cost' => 'nullable|numeric|min:0',
                'biological_cost' => 'nullable|numeric|min:0',
                'packaging_cost' => 'nullable|numeric|min:0',
                'labor_cost' => 'nullable|numeric|min:0',
                'maintenance_cost' => 'nullable|numeric|min:0',
                'other_costs' => 'nullable|numeric|min:0',
                'unit_type' => 'nullable|in:egg,kg_meat,bird',
                'notes' => 'nullable|string',
                'status' => 'required|in:draft,confirmed,cancelled'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for production cost update', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            $productionCost->fill($request->all());
            $productionCost->calculateTotalCost();
            $productionCost->save();

            // Actualizar componentes de costo
            if ($request->has('components') && is_array($request->components)) {
                // Eliminar componentes existentes
                $productionCost->costComponents()->delete();
                
                // Crear nuevos componentes
                foreach ($request->components as $componentData) {
                    if (!empty($componentData['component_name'])) {
                        $component = new CostComponent($componentData);
                        $component->production_cost_id = $productionCost->id;
                        $component->calculateTotalCost();
                        $component->save();
                    }
                }
            }

            DB::commit();

            Log::info('Production cost updated successfully', ['id' => $productionCost->id]);
            return redirect()->route('avicontrol.admin.production_costs.show', $productionCost->id)
                ->with('success', 'Costo de producción actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating production cost: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar el costo de producción: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            Log::info('Deleting production cost', ['id' => $id]);
            
            $productionCost = ProductionCost::findOrFail($id);
            
            // Verificar si tiene análisis de rentabilidad asociados
            if ($productionCost->profitabilityAnalysis()->exists()) {
                return redirect()->back()->with('error', 'No se puede eliminar el costo de producción porque tiene análisis de rentabilidad asociados');
            }
            
            $productionCost->delete();
            
            Log::info('Production cost deleted successfully', ['id' => $id]);
            return redirect()->route('avicontrol.admin.production_costs.index')
                ->with('success', 'Costo de producción eliminado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error deleting production cost: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el costo de producción: ' . $e->getMessage());
        }
    }

    /**
     * Confirm a production cost
     * @param int $id
     * @return Renderable
     */
    public function confirm($id)
    {
        try {
            Log::info('Confirming production cost', ['id' => $id]);
            
            $productionCost = ProductionCost::findOrFail($id);
            $productionCost->status = 'confirmed';
            $productionCost->save();
            
            Log::info('Production cost confirmed successfully', ['id' => $id]);
            return redirect()->back()->with('success', 'Costo de producción confirmado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error confirming production cost: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al confirmar el costo de producción: ' . $e->getMessage());
        }
    }

    /**
     * Calculate costs from inventory data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateFromInventory(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'poultry_facility_id' => 'required|exists:avicontrol_poultry_facilities,id',
                'period_start' => 'required|date',
                'period_end' => 'required|date|after_or_equal:period_start'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Obtener movimientos de inventario para el período
            $inventoryMovements = DB::table('avicontrol_inventory_movements as im')
                ->join('avicontrol_inventory_products as ip', 'im.inventory_product_id', '=', 'ip.id')
                ->where('im.poultry_facility_id', $request->poultry_facility_id)
                ->whereBetween('im.movement_date', [$request->period_start, $request->period_end])
                ->where('im.movement_type', 'out')
                ->select('ip.product_type', 'im.quantity', 'ip.unit_price')
                ->get();

            $costs = [
                'concentrate_cost' => 0,
                'medication_cost' => 0,
                'biological_cost' => 0,
                'packaging_cost' => 0
            ];

            foreach ($inventoryMovements as $movement) {
                $totalCost = $movement->quantity * $movement->unit_price;
                
                switch ($movement->product_type) {
                    case 'concentrate':
                        $costs['concentrate_cost'] += $totalCost;
                        break;
                    case 'medication':
                        $costs['medication_cost'] += $totalCost;
                        break;
                    case 'biological':
                        $costs['biological_cost'] += $totalCost;
                        break;
                    case 'packaging':
                        $costs['packaging_cost'] += $totalCost;
                        break;
                }
            }

            return response()->json(['success' => true, 'costs' => $costs]);

        } catch (\Exception $e) {
            Log::error('Error calculating costs from inventory: ' . $e->getMessage());
            return response()->json(['error' => 'Error al calcular costos desde inventario'], 500);
        }
    }

    /**
     * Get cost summary for dashboard
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCostSummary()
    {
        try {
            $summary = [
                'total_costs' => ProductionCost::confirmed()->sum('total_cost'),
                'monthly_costs' => ProductionCost::confirmed()
                    ->whereBetween('period_start', [now()->startOfMonth(), now()->endOfMonth()])
                    ->sum('total_cost'),
                'pending_costs' => ProductionCost::draft()->count(),
                'cost_by_type' => ProductionCost::confirmed()
                    ->selectRaw('cost_type, SUM(total_cost) as total')
                    ->groupBy('cost_type')
                    ->pluck('total', 'cost_type')
            ];

            return response()->json(['success' => true, 'summary' => $summary]);

        } catch (\Exception $e) {
            Log::error('Error getting cost summary: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener resumen de costos'], 500);
        }
    }
} 