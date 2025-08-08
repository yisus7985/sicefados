<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AVICONTROL\Entities\ProfitabilityAnalysis;
use Modules\AVICONTROL\Entities\ProductionCost;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfitabilityAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            Log::info('Accessing profitability analysis index');
            
            $query = ProfitabilityAnalysis::with(['productionCost.poultryFacility']);
            
            // Filtros
            if ($request->filled('facility_id')) {
                $query->whereHas('productionCost', function($q) use ($request) {
                    $q->where('poultry_facility_id', $request->facility_id);
                });
            }
            
            if ($request->filled('analysis_period')) {
                $query->where('analysis_period', $request->analysis_period);
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
            
            if ($request->filled('profitability_status')) {
                switch ($request->profitability_status) {
                    case 'profitable':
                        $query->where('net_profit', '>', 0);
                        break;
                    case 'loss':
                        $query->where('net_profit', '<', 0);
                        break;
                    case 'break_even':
                        $query->where('net_profit', '=', 0);
                        break;
                }
            }
            
            $analyses = $query->orderBy('created_at', 'desc')->paginate(15);
            $poultryFacilities = PoultryFacility::where('status', 'active')->get();
            
            return view('avicontrol::admin.profitability_analysis.index', compact('analyses', 'poultryFacilities'));
        } catch (\Exception $e) {
            Log::error('Error in profitability analysis index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el análisis de rentabilidad: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        try {
            Log::info('Accessing profitability analysis create form');
            
            $poultryFacilities = PoultryFacility::where('status', 'active')->get();
            $productionCosts = ProductionCost::confirmed()->with('poultryFacility')->get();
            
            return view('avicontrol::admin.profitability_analysis.create', compact('poultryFacilities', 'productionCosts'));
        } catch (\Exception $e) {
            Log::error('Error in profitability analysis create form: ' . $e->getMessage());
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
            Log::info('Storing new profitability analysis', $request->all());
            
            $validator = Validator::make($request->all(), [
                'production_cost_id' => 'required|exists:avicontrol_production_costs,id',
                'analysis_period' => 'required|in:daily,weekly,monthly,batch,custom',
                'period_start' => 'required|date',
                'period_end' => 'required|date|after_or_equal:period_start',
                'total_revenue' => 'required|numeric|min:0',
                'other_income' => 'nullable|numeric|min:0',
                'other_expenses' => 'nullable|numeric|min:0',
                'unit_type' => 'nullable|in:egg,kg_meat,bird',
                'total_units_produced' => 'nullable|numeric|min:0',
                'average_selling_price' => 'nullable|numeric|min:0',
                'analysis_date' => 'required|date',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for profitability analysis creation', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Obtener el costo de producción
            $productionCost = ProductionCost::findOrFail($request->production_cost_id);
            
            // Verificar que no exista ya un análisis para este costo de producción
            if (ProfitabilityAnalysis::where('production_cost_id', $request->production_cost_id)->exists()) {
                return redirect()->back()
                    ->with('error', 'Ya existe un análisis de rentabilidad para este costo de producción')
                    ->withInput();
            }

            $analysis = new ProfitabilityAnalysis($request->all());
            $analysis->total_production_cost = $productionCost->total_cost;
            $analysis->calculateAllMetrics();
            $analysis->save();

            Log::info('Profitability analysis created successfully', ['id' => $analysis->id]);
            return redirect()->route('avicontrol.admin.profitability_analysis.show', $analysis->id)
                ->with('success', 'Análisis de rentabilidad creado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error creating profitability analysis: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al crear el análisis de rentabilidad: ' . $e->getMessage())
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
            Log::info('Showing profitability analysis', ['id' => $id]);
            
            $analysis = ProfitabilityAnalysis::with([
                'productionCost.poultryFacility',
                'productionCost.bird',
                'productionCost.costComponents'
            ])->findOrFail($id);
            
            return view('avicontrol::admin.profitability_analysis.show', compact('analysis'));
        } catch (\Exception $e) {
            Log::error('Error showing profitability analysis: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al mostrar el análisis de rentabilidad: ' . $e->getMessage());
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
            Log::info('Accessing profitability analysis edit form', ['id' => $id]);
            
            $analysis = ProfitabilityAnalysis::findOrFail($id);
            $poultryFacilities = PoultryFacility::where('status', 'active')->get();
            $productionCosts = ProductionCost::confirmed()->with('poultryFacility')->get();
            
            return view('avicontrol::admin.profitability_analysis.edit', compact('analysis', 'poultryFacilities', 'productionCosts'));
        } catch (\Exception $e) {
            Log::error('Error in profitability analysis edit form: ' . $e->getMessage());
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
            Log::info('Updating profitability analysis', ['id' => $id, 'data' => $request->all()]);
            
            $analysis = ProfitabilityAnalysis::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'production_cost_id' => 'required|exists:avicontrol_production_costs,id',
                'analysis_period' => 'required|in:daily,weekly,monthly,batch,custom',
                'period_start' => 'required|date',
                'period_end' => 'required|date|after_or_equal:period_start',
                'total_revenue' => 'required|numeric|min:0',
                'other_income' => 'nullable|numeric|min:0',
                'other_expenses' => 'nullable|numeric|min:0',
                'unit_type' => 'nullable|in:egg,kg_meat,bird',
                'total_units_produced' => 'nullable|numeric|min:0',
                'average_selling_price' => 'nullable|numeric|min:0',
                'analysis_date' => 'required|date',
                'notes' => 'nullable|string',
                'status' => 'required|in:draft,confirmed,archived'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for profitability analysis update', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Obtener el costo de producción
            $productionCost = ProductionCost::findOrFail($request->production_cost_id);
            
            $analysis->fill($request->all());
            $analysis->total_production_cost = $productionCost->total_cost;
            $analysis->calculateAllMetrics();
            $analysis->save();

            Log::info('Profitability analysis updated successfully', ['id' => $analysis->id]);
            return redirect()->route('avicontrol.admin.profitability_analysis.show', $analysis->id)
                ->with('success', 'Análisis de rentabilidad actualizado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error updating profitability analysis: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar el análisis de rentabilidad: ' . $e->getMessage())
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
            Log::info('Deleting profitability analysis', ['id' => $id]);
            
            $analysis = ProfitabilityAnalysis::findOrFail($id);
            $analysis->delete();
            
            Log::info('Profitability analysis deleted successfully', ['id' => $id]);
            return redirect()->route('avicontrol.admin.profitability_analysis.index')
                ->with('success', 'Análisis de rentabilidad eliminado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error deleting profitability analysis: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el análisis de rentabilidad: ' . $e->getMessage());
        }
    }

    /**
     * Confirm a profitability analysis
     * @param int $id
     * @return Renderable
     */
    public function confirm($id)
    {
        try {
            Log::info('Confirming profitability analysis', ['id' => $id]);
            
            $analysis = ProfitabilityAnalysis::findOrFail($id);
            $analysis->status = 'confirmed';
            $analysis->save();
            
            Log::info('Profitability analysis confirmed successfully', ['id' => $id]);
            return redirect()->back()->with('success', 'Análisis de rentabilidad confirmado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error confirming profitability analysis: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al confirmar el análisis de rentabilidad: ' . $e->getMessage());
        }
    }

    /**
     * Generate profitability report
     * @param Request $request
     * @return Renderable
     */
    public function generateReport(Request $request)
    {
        try {
            Log::info('Generating profitability report', $request->all());
            
            $validator = Validator::make($request->all(), [
                'facility_id' => 'nullable|exists:avicontrol_poultry_facilities,id',
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
                'analysis_period' => 'nullable|in:daily,weekly,monthly,batch,custom',
                'unit_type' => 'nullable|in:egg,kg_meat,bird'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $query = ProfitabilityAnalysis::with(['productionCost.poultryFacility'])
                ->whereBetween('period_start', [$request->date_from, $request->date_to]);

            if ($request->filled('facility_id')) {
                $query->whereHas('productionCost', function($q) use ($request) {
                    $q->where('poultry_facility_id', $request->facility_id);
                });
            }

            if ($request->filled('analysis_period')) {
                $query->where('analysis_period', $request->analysis_period);
            }

            if ($request->filled('unit_type')) {
                $query->where('unit_type', $request->unit_type);
            }

            $analyses = $query->confirmed()->orderBy('period_start')->get();
            $poultryFacilities = PoultryFacility::where('status', 'active')->get();

            // Calcular estadísticas del reporte
            $reportStats = [
                'total_analyses' => $analyses->count(),
                'total_revenue' => $analyses->sum('total_revenue'),
                'total_costs' => $analyses->sum('total_production_cost'),
                'total_profit' => $analyses->sum('net_profit'),
                'average_margin' => $analyses->avg('net_margin_percentage'),
                'profitable_count' => $analyses->where('net_profit', '>', 0)->count(),
                'loss_count' => $analyses->where('net_profit', '<', 0)->count()
            ];

            return view('avicontrol::admin.profitability_analysis.report', compact('analyses', 'poultryFacilities', 'reportStats'));

        } catch (\Exception $e) {
            Log::error('Error generating profitability report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Get profitability summary for dashboard
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfitabilitySummary()
    {
        try {
            $summary = [
                'total_profit' => ProfitabilityAnalysis::confirmed()->sum('net_profit'),
                'monthly_profit' => ProfitabilityAnalysis::confirmed()
                    ->whereBetween('period_start', [now()->startOfMonth(), now()->endOfMonth()])
                    ->sum('net_profit'),
                'profitable_analyses' => ProfitabilityAnalysis::confirmed()->where('net_profit', '>', 0)->count(),
                'loss_analyses' => ProfitabilityAnalysis::confirmed()->where('net_profit', '<', 0)->count(),
                'average_margin' => ProfitabilityAnalysis::confirmed()->avg('net_margin_percentage'),
                'profit_by_period' => ProfitabilityAnalysis::confirmed()
                    ->selectRaw('analysis_period, SUM(net_profit) as total_profit')
                    ->groupBy('analysis_period')
                    ->pluck('total_profit', 'analysis_period')
            ];

            return response()->json(['success' => true, 'summary' => $summary]);

        } catch (\Exception $e) {
            Log::error('Error getting profitability summary: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener resumen de rentabilidad'], 500);
        }
    }

    /**
     * Export profitability analysis to PDF
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function exportPdf($id)
    {
        try {
            Log::info('Exporting profitability analysis to PDF', ['id' => $id]);
            
            $analysis = ProfitabilityAnalysis::with([
                'productionCost.poultryFacility',
                'productionCost.bird',
                'productionCost.costComponents'
            ])->findOrFail($id);
            
            // Aquí se implementaría la lógica para generar el PDF
            // Por ahora retornamos una vista simple
            return view('avicontrol::admin.profitability_analysis.pdf', compact('analysis'));

        } catch (\Exception $e) {
            Log::error('Error exporting profitability analysis to PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al exportar el análisis: ' . $e->getMessage());
        }
    }
} 