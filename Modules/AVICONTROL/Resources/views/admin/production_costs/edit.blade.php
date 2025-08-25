@extends('avicontrol::layouts.admin')

@section('title', 'Editar Costo de Producción')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Editar Costo de Producción
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('avicontrol.admin.production_costs.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('avicontrol.admin.production_costs.update', $productionCost->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Galpón -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="poultry_facility_id">Galpón <span class="text-danger">*</span></label>
                                    <select name="poultry_facility_id" id="poultry_facility_id" class="form-control @error('poultry_facility_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un galpón</option>
                                        @foreach($poultryFacilities as $facility)
                                            <option value="{{ $facility->id }}" {{ old('poultry_facility_id', $productionCost->poultry_facility_id) == $facility->id ? 'selected' : '' }}>
                                                {{ $facility->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('poultry_facility_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Lote -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bird_id">Lote</label>
                                    <select name="bird_id" id="bird_id" class="form-control @error('bird_id') is-invalid @enderror">
                                        <option value="">Seleccione un lote</option>
                                        @foreach($birds as $bird)
                                            <option value="{{ $bird->id }}" {{ old('bird_id', $productionCost->bird_id) == $bird->id ? 'selected' : '' }}>
                                                {{ $bird->batch_code }} - {{ $bird->batch_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bird_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Código de Lote -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch_code">Código de Lote</label>
                                    <input type="text" name="batch_code" id="batch_code" class="form-control @error('batch_code') is-invalid @enderror" 
                                           value="{{ old('batch_code', $productionCost->batch_code) }}" placeholder="Ej: LOTE-001">
                                    @error('batch_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tipo de Costo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cost_type">Tipo de Costo <span class="text-danger">*</span></label>
                                    <select name="cost_type" id="cost_type" class="form-control @error('cost_type') is-invalid @enderror" required>
                                        <option value="batch" {{ old('cost_type', $productionCost->cost_type) == 'batch' ? 'selected' : '' }}>Por Lote</option>
                                        <option value="period" {{ old('cost_type', $productionCost->cost_type) == 'period' ? 'selected' : '' }}>Por Período</option>
                                    </select>
                                    @error('cost_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Fecha de Inicio del Período -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_start">Fecha de Inicio del Período <span class="text-danger">*</span></label>
                                    <input type="date" name="period_start" id="period_start" class="form-control @error('period_start') is-invalid @enderror" 
                                           value="{{ old('period_start', $productionCost->period_start->format('Y-m-d')) }}" required>
                                    @error('period_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fecha de Fin del Período -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="period_end">Fecha de Fin del Período <span class="text-danger">*</span></label>
                                    <input type="date" name="period_end" id="period_end" class="form-control @error('period_end') is-invalid @enderror" 
                                           value="{{ old('period_end', $productionCost->period_end->format('Y-m-d')) }}" required>
                                    @error('period_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5><i class="fas fa-dollar-sign"></i> Costos por Categoría</h5>

                        <div class="row">
                            <!-- Costo de Concentrado -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="concentrate_cost">Costo de Concentrado</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="concentrate_cost" id="concentrate_cost" step="0.01" min="0" 
                                               class="form-control @error('concentrate_cost') is-invalid @enderror cost-input" 
                                               value="{{ old('concentrate_cost', $productionCost->concentrate_cost) }}" placeholder="0.00">
                                    </div>
                                    @error('concentrate_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Costo de Agua -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="water_cost">Costo de Agua</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="water_cost" id="water_cost" step="0.01" min="0" 
                                               class="form-control @error('water_cost') is-invalid @enderror cost-input" 
                                               value="{{ old('water_cost', $productionCost->water_cost) }}" placeholder="0.00">
                                    </div>
                                    @error('water_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Costo de Energía -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="energy_cost">Costo de Energía</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="energy_cost" id="energy_cost" step="0.01" min="0" 
                                               class="form-control @error('energy_cost') is-invalid @enderror cost-input" 
                                               value="{{ old('energy_cost', $productionCost->energy_cost) }}" placeholder="0.00">
                                    </div>
                                    @error('energy_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Costo de Medicamentos -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="medication_cost">Costo de Medicamentos</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="medication_cost" id="medication_cost" step="0.01" min="0" 
                                               class="form-control @error('medication_cost') is-invalid @enderror cost-input" 
                                               value="{{ old('medication_cost', $productionCost->medication_cost) }}" placeholder="0.00">
                                    </div>
                                    @error('medication_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Costo de Biológicos -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="biological_cost">Costo de Biológicos</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="biological_cost" id="biological_cost" step="0.01" min="0" 
                                               class="form-control @error('biological_cost') is-invalid @enderror cost-input" 
                                               value="{{ old('biological_cost', $productionCost->biological_cost) }}" placeholder="0.00">
                                    </div>
                                    @error('biological_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Costo de Empaque -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="packaging_cost">Costo de Empaque</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="packaging_cost" id="packaging_cost" step="0.01" min="0" 
                                               class="form-control @error('packaging_cost') is-invalid @enderror cost-input" 
                                               value="{{ old('packaging_cost', $productionCost->packaging_cost) }}" placeholder="0.00">
                                    </div>
                                    @error('packaging_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Costo de Mano de Obra -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="labor_cost">Costo de Mano de Obra</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="labor_cost" id="labor_cost" step="0.01" min="0" 
                                               class="form-control @error('labor_cost') is-invalid @enderror cost-input" 
                                               value="{{ old('labor_cost', $productionCost->labor_cost) }}" placeholder="0.00">
                                    </div>
                                    @error('labor_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Costo de Mantenimiento -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="maintenance_cost">Costo de Mantenimiento</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="maintenance_cost" id="maintenance_cost" step="0.01" min="0" 
                                               class="form-control @error('maintenance_cost') is-invalid @enderror cost-input" 
                                               value="{{ old('maintenance_cost', $productionCost->maintenance_cost) }}" placeholder="0.00">
                                    </div>
                                    @error('maintenance_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Otros Costos -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="other_costs">Otros Costos</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="other_costs" id="other_costs" step="0.01" min="0" 
                                               class="form-control @error('other_costs') is-invalid @enderror cost-input" 
                                               value="{{ old('other_costs', $productionCost->other_costs) }}" placeholder="0.00">
                                    </div>
                                    @error('other_costs')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5><i class="fas fa-calculator"></i> Información de Unidades</h5>

                        <div class="row">
                            <!-- Tipo de Unidad -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit_type">Tipo de Unidad</label>
                                    <select name="unit_type" id="unit_type" class="form-control @error('unit_type') is-invalid @enderror">
                                        <option value="">Seleccione el tipo de unidad</option>
                                        <option value="egg" {{ old('unit_type', $productionCost->unit_type) == 'egg' ? 'selected' : '' }}>Huevo</option>
                                        <option value="kg_meat" {{ old('unit_type', $productionCost->unit_type) == 'kg_meat' ? 'selected' : '' }}>Kilogramo de Carne</option>
                                        <option value="bird" {{ old('unit_type', $productionCost->unit_type) == 'bird' ? 'selected' : '' }}>Ave</option>
                                    </select>
                                    @error('unit_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Estado <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', $productionCost->status) == 'draft' ? 'selected' : '' }}>Borrador</option>
                                        <option value="confirmed" {{ old('status', $productionCost->status) == 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                                        <option value="cancelled" {{ old('status', $productionCost->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div class="form-group">
                            <label for="notes">Notas</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" 
                                      placeholder="Notas adicionales sobre el costo de producción">{{ old('notes', $productionCost->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Resumen de Costos -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Resumen de Costos</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Costo Total:</strong> <span id="total_cost_display">${{ number_format($productionCost->total_cost, 2) }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Costo por Unidad:</strong> <span id="cost_per_unit_display">
                                        @if($productionCost->cost_per_unit)
                                            ${{ number_format($productionCost->cost_per_unit, 2) }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Botón para calcular desde inventario -->
                        <div class="form-group text-center">
                            <button type="button" id="calculate_from_inventory" class="btn btn-info btn-lg">
                                <i class="fas fa-calculator"></i> Calcular desde Inventario
                            </button>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Actualizar Costo de Producción
                            </button>
                            <a href="{{ route('avicontrol.admin.production_costs.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Función para calcular el costo total
    function calculateTotalCost() {
        let total = 0;
        $('.cost-input').each(function() {
            const value = parseFloat($(this).val()) || 0;
            total += value;
        });
        
        $('#total_cost_display').text('$' + total.toFixed(2));
        return total;
    }

    // Calcular costo total cuando cambien los inputs
    $('.cost-input').on('input', function() {
        calculateTotalCost();
    });

    // Obtener lotes filtrados por galpón
    $('#poultry_facility_id').on('change', function() {
        const facilityId = $(this).val();
        if (facilityId) {
            $.ajax({
                url: '{{ route("avicontrol.admin.production_costs.get_birds_by_facility") }}',
                method: 'POST',
                data: {
                    poultry_facility_id: facilityId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#bird_id').empty();
                        $('#bird_id').append('<option value="">Seleccione un lote</option>');
                        
                        response.birds.forEach(function(bird) {
                            $('#bird_id').append(
                                '<option value="' + bird.id + '">' + 
                                bird.batch_code + ' - ' + bird.batch_name + 
                                '</option>'
                            );
                        });
                    }
                },
                error: function() {
                    console.error('Error al obtener los lotes');
                }
            });
        } else {
            $('#bird_id').empty();
            $('#bird_id').append('<option value="">Seleccione un lote</option>');
        }
    });

    // Calcular costos desde inventario
    $('#calculate_from_inventory').on('click', function() {
        const facilityId = $('#poultry_facility_id').val();
        const periodStart = $('#period_start').val();
        const periodEnd = $('#period_end').val();
        
        if (!facilityId || !periodStart || !periodEnd) {
            alert('Por favor complete el galpón y las fechas del período');
            return;
        }
        
        $.ajax({
            url: '{{ route("avicontrol.admin.production_costs.calculate_from_inventory") }}',
            method: 'POST',
            data: {
                poultry_facility_id: facilityId,
                period_start: periodStart,
                period_end: periodEnd,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#concentrate_cost').val(response.costs.concentrate_cost);
                    $('#medication_cost').val(response.costs.medication_cost);
                    $('#biological_cost').val(response.costs.biological_cost);
                    $('#packaging_cost').val(response.costs.packaging_cost);
                    calculateTotalCost();
                }
            },
            error: function() {
                alert('Error al calcular costos desde inventario');
            }
        });
    });

    // Validación de fechas
    $('#period_end').on('change', function() {
        const startDate = $('#period_start').val();
        const endDate = $(this).val();
        
        if (startDate && endDate && startDate > endDate) {
            alert('La fecha de fin debe ser posterior a la fecha de inicio');
            $(this).val('');
        }
    });
});
</script>
@endsection

@push('styles')
<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .alert {
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border-left: 4px solid #17a2b8;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #ced4da;
    }
    
    .cost-input:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
</style>
@endpush
