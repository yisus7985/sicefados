<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\AVICONTROL\Entities\Bird;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BirdController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        try {
            Log::info('Accessing birds index');
            
            $query = Bird::with('poultryFacility');
            
            // Filtros
            if ($request->filled('facility_id')) {
                $query->where('poultry_facility_id', $request->facility_id);
            }
            
            if ($request->filled('bird_type')) {
                $query->where('bird_type', $request->bird_type);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            $birds = $query->orderBy('created_at', 'desc')->get();
            $poultryFacilities = PoultryFacility::where('status', 'active')->get();
            
            return view('avicontrol::admin.birds.index', compact('birds', 'poultryFacilities'));
        } catch (\Exception $e) {
            Log::error('Error in birds index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las aves: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        try {
            Log::info('Accessing bird create form');
            
            $poultryFacilities = PoultryFacility::where('status', 'active')->get();
            
            return view('avicontrol::admin.birds.create', compact('poultryFacilities'));
        } catch (\Exception $e) {
            Log::error('Error in bird create form: ' . $e->getMessage());
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
            Log::info('Storing new bird', $request->all());
            
            $validator = Validator::make($request->all(), [
                'poultry_facility_id' => 'required|exists:avicontrol_poultry_facilities,id',
                'batch_code' => 'required|string|max:50|unique:avicontrol_birds,batch_code',
                'bird_type' => 'required|in:laying_hens,broilers,chicks,breeders',
                'quantity' => 'required|integer|min:1',
                'entry_date' => 'required|date',
                'age_weeks' => 'nullable|integer|min:0|max:200',
                'breed' => 'nullable|string|max:100',
                'average_weight' => 'nullable|numeric|min:0',
                'purchase_price' => 'nullable|numeric|min:0',
                'supplier' => 'nullable|string|max:200',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for bird creation', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Verificar capacidad del galpón
            $facility = PoultryFacility::findOrFail($request->poultry_facility_id);
            if (!$facility->canAccommodate($request->quantity)) {
                return redirect()->back()
                    ->with('error', 'El galpón no tiene capacidad suficiente. Capacidad disponible: ' . $facility->available_capacity . ' aves')
                    ->withInput();
            }

            // Crear el registro
            $birdData = $request->all();
            $birdData['initial_quantity'] = $request->quantity; // Guardar cantidad inicial
            
            $bird = Bird::create($birdData);
            Log::info('Created bird with ID: ' . $bird->id);

            return redirect()->route('avicontrol.admin.birds.index')
                ->with('success', 'Lote de aves registrado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error creating bird: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al registrar el lote de aves: ' . $e->getMessage())
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
            Log::info('Showing bird with ID: ' . $id);
            
            $bird = Bird::with('poultryFacility')->findOrFail($id);
            
            return view('avicontrol::admin.birds.show', compact('bird'));
        } catch (\Exception $e) {
            Log::error('Error showing bird: ' . $e->getMessage());
            return redirect()->route('avicontrol.admin.birds.index')
                ->with('error', 'Error al cargar el lote de aves: ' . $e->getMessage());
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
            Log::info('Editing bird with ID: ' . $id);
            
            $bird = Bird::findOrFail($id);
            $poultryFacilities = PoultryFacility::where('status', 'active')->get();
            
            return view('avicontrol::admin.birds.edit', compact('bird', 'poultryFacilities'));
        } catch (\Exception $e) {
            Log::error('Error in bird edit form: ' . $e->getMessage());
            return redirect()->route('avicontrol.admin.birds.index')
                ->with('error', 'Error al cargar el formulario de edición: ' . $e->getMessage());
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
            Log::info('Updating bird with ID: ' . $id, $request->all());
            
            $bird = Bird::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'poultry_facility_id' => 'required|exists:avicontrol_poultry_facilities,id',
                'batch_code' => 'required|string|max:50|unique:avicontrol_birds,batch_code,' . $id,
                'bird_type' => 'required|in:laying_hens,broilers,chicks,breeders',
                'quantity' => 'required|integer|min:0|max:' . $bird->initial_quantity,
                'entry_date' => 'required|date',
                'age_weeks' => 'nullable|integer|min:0|max:200',
                'breed' => 'nullable|string|max:100',
                'average_weight' => 'nullable|numeric|min:0',
                'status' => 'required|in:active,sold,deceased,transferred',
                'purchase_price' => 'nullable|numeric|min:0',
                'supplier' => 'nullable|string|max:200',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed for bird update', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Si se cambia de galpón, verificar capacidad
            if ($request->poultry_facility_id != $bird->poultry_facility_id) {
                $newFacility = PoultryFacility::findOrFail($request->poultry_facility_id);
                if (!$newFacility->canAccommodate($request->quantity)) {
                    return redirect()->back()
                        ->with('error', 'El nuevo galpón no tiene capacidad suficiente. Capacidad disponible: ' . $newFacility->available_capacity . ' aves')
                        ->withInput();
                }
            }

            $bird->update($request->all());
            Log::info('Updated bird with ID: ' . $id);

            return redirect()->route('avicontrol.admin.birds.index')
                ->with('success', 'Lote de aves actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error updating bird: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar el lote de aves: ' . $e->getMessage())
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
            Log::info('Deleting bird with ID: ' . $id);
            
            $bird = Bird::findOrFail($id);
            $bird->delete();
            Log::info('Deleted bird with ID: ' . $id);

            return redirect()->route('avicontrol.admin.birds.index')
                ->with('success', 'Lote de aves eliminado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error deleting bird: ' . $e->getMessage());
            return redirect()->route('avicontrol.admin.birds.index')
                ->with('error', 'Error al eliminar el lote de aves: ' . $e->getMessage());
        }
    }

    /**
     * Get facility capacity via AJAX
     */
    public function getFacilityCapacity($facilityId)
    {
        try {
            $facility = PoultryFacility::findOrFail($facilityId);
            
            return response()->json([
                'success' => true,
                'capacity' => $facility->capacity,
                'current_birds' => $facility->total_active_birds,
                'available_capacity' => $facility->available_capacity,
                'occupancy_percentage' => $facility->occupancy_percentage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información del galpón'
            ], 404);
        }
    }
}
