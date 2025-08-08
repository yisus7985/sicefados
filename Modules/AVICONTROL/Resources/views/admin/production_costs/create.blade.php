@extends('avicontrol::layouts.admin')

@section('title', 'Crear Costo de Producción')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i> Crear Nuevo Costo de Producción
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('avicontrol.admin.production_costs.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <form action="{{ route('avicontrol.admin.production_costs.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Información básica -->
                            <div class="col-md-6">
                                <h5>Información Básica</h5>
                                <div class="form-group">
                                    <label for="poultry_facility_id">Galpón *</label>
                                    <select name="poultry_facility_id" id="poultry_facility_id" class="form-control @error('poultry_facility_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un galpón</option>
                                        @foreach($poultryFacilities as $facility)
                                            <option value="{{ $facility->id }}" {{ old('poultry_facility_id') == $facility->id ? 'selected' : '' }}>
                                                {{ $facility->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('poultry_facility_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="bird_id">Lote de Aves</label>
                                    <select name="bird_id" id="bird_id" class="form-control @error('bird_id') is-invalid @enderror">
                                        <option value="">Seleccione un lote</option>
                                        @foreach($birds as $bird)
                                            <option value="{{ $bird->id }}" {{ old('bird_id') == $bird->id ? 'selected' : '' }}>
                                                {{ $bird->batch_code }} - {{ $bird->bird_type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bird_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="batch_code">Código de Lote</label>
                                    <input type="text" name="batch_code" id="batch_code" class="form-control @error('batch_code') is-invalid @enderror" 
                                           value="{{ old('batch_code') }}" placeholder="Ej: LOTE-2024-001">
                                    @error('batch_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cost_type">Tipo de Costo *</label>
                                    <select name="cost_type" id="cost_type" class="form-control @error('cost_type') is-invalid @enderror" required>
                                        <option value="">Seleccione el tipo</option>
                                        <option value="batch" {{ old('cost_type') == 'batch' ? 'selected' : '' }}>Por Lote</option>
                                        <option value="period" {{ old('cost_type') == 'period' ? 'selected' : '' }}>Por Período</option>
                                    </select>
                                    @error('cost_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Período -->
                            <div class="col-md-6">
                                <h5>Período de Costos</h5>
                                <div class="form-group">
                                    <label for="period_start">Fecha de Inicio *</label>
                                    <input type="date" name="period_start" id="period_start" class="form-control @error('period_start') is-invalid @enderror" 
                                           value="{{ old('period_start') }}" required>
                                    @error('period_start')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="period_end">Fecha de Fin *</label>
                                    <input type="date" name="period_end" id="period_end" class="form-control @error('period_end') is-invalid @enderror" 
                                           value="{{ old('period_end') }}" required>
                                    @error('period_end')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="unit_type">Tipo de Unidad</label>
                                    <select name="unit_type" id="unit_type" class="form-control @error('unit_type') is-invalid @enderror">
                                        <option value="">Seleccione la unidad</option>
                                        <option value="egg" {{ old('unit_type') == 'egg' ? 'selected' : '' }}>Por Huevo</option>
                                        <option value="kg_meat" {{ old('unit_type') == 'kg_meat' ? 'selected' : '' }}>Por Kg de Carne</option>
                                        <option value="bird" {{ old('unit_type') == 'bird' ? 'selected' : '' }}>Por Ave</option>
                                    </select>
                                    @error('unit_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="notes">Notas</label>
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                              rows="3" placeholder="Notas adicionales...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Botón para calcular desde inventario -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" id="calculateFromInventory" class="btn btn-info">
                                    <i class="fas fa-calculator"></i> Calcular desde Inventario
                                </button>
                                <small class="text-muted">Calcula automáticamente los costos basados en los movimientos de inventario del período</small>
                            </div>
                        </div>

                        <!-- Costos por componente -->
                        <h5>Costos por Componente</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="concentrate_cost">Costo de Concentrado</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="concentrate_cost" id="concentrate_cost" class="form-control @error('concentrate_cost') is-invalid @enderror" 
                                               value="{{ old('concentrate_cost', 0) }}" step="0.01" min="0">
                                    </div>
                                    @error('concentrate_cost')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="water_cost">Costo de Agua</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="water_cost" id="water_cost" class="form-control @error('water_cost') is-invalid @enderror" 
                                               value="{{ old('water_cost', 0) }}" step="0.01" min="0">
                                    </div>
                                    @error('water_cost')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="energy_cost">Costo de Energía</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="energy_cost" id="energy_cost" class="form-control @error('energy_cost') is-invalid @enderror" 
                                               value="{{ old('energy_cost', 0) }}" step="0.01" min="0">
                                    </div>
                                    @error('energy_cost')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="medication_cost">Costo de Medicamentos</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="medication_cost" id="medication_cost" class="form-control @error('medication_cost') is-invalid @enderror" 
                                               value="{{ old('medication_cost', 0) }}" step="0.01" min="0">
                                    </div>
                                    @error('medication_cost')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="biological_cost">Costo de Biológicos</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="biological_cost" id="biological_cost" class="form-control @error('biological_cost') is-invalid @enderror" 
                                               value="{{ old('biological_cost', 0) }}" step="0.01" min="0">
                                    </div>
                                    @error('biological_cost')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="packaging_cost">Costo de Embalajes</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="packaging_cost" id="packaging_cost" class="form-control @error('packaging_cost') is-invalid @enderror" 
                                               value="{{ old('packaging_cost', 0) }}" step="0.01" min="0">
                                    </div>
                                    @error('packaging_cost')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="labor_cost">Costo de Mano de Obra</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="labor_cost" id="labor_cost" class="form-control @error('labor_cost') is-invalid @enderror" 
                                               value="{{ old('labor_cost', 0) }}" step="0.01" min="0">
                                    </div>
                                    @error('labor_cost')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="maintenance_cost">Costo de Mantenimiento</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="maintenance_cost" id="maintenance_cost" class="form-control @error('maintenance_cost') is-invalid @enderror" 
                                               value="{{ old('maintenance_cost', 0) }}" step="0.01" min="0">
                                    </div>
                                    @error('maintenance_cost')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="other_costs">Otros Costos</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="other_costs" id="other_costs" class="form-control @error('other_costs') is-invalid @enderror" 
                                               value="{{ old('other_costs', 0) }}" step="0.01" min="0">
                                    </div>
                                    @error('other_costs')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Resumen de costos -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-calculator"></i> Resumen de Costos</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Costo Total:</strong> <span id="totalCost">$0.00</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Duración del Período:</strong> <span id="periodDuration">0 días</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Costo de Producción
                        </button>
                        <a href="{{ route('avicontrol.admin.production_costs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Calcular costo total automáticamente
    function calculateTotalCost() {
        let total = 0;
        const costFields = [
            'concentrate_cost', 'water_cost', 'energy_cost', 'medication_cost',
            'biological_cost', 'packaging_cost', 'labor_cost', 'maintenance_cost', 'other_costs'
        ];
        
        costFields.forEach(field => {
            const value = parseFloat($('#' + field).val()) || 0;
            total += value;
        });
        
        $('#totalCost').text('$' + total.toFixed(2));
    }

    // Calcular duración del período
    function calculatePeriodDuration() {
        const startDate = $('#period_start').val();
        const endDate = $('#period_end').val();
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            $('#periodDuration').text(diffDays + ' días');
        }
    }

    // Event listeners para recalcular
    $('input[type="number"]').on('input', calculateTotalCost);
    $('#period_start, #period_end').on('change', calculatePeriodDuration);

    // Calcular desde inventario
    $('#calculateFromInventory').click(function() {
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
                _token: '{{ csrf_token() }}',
                poultry_facility_id: facilityId,
                period_start: periodStart,
                period_end: periodEnd
            },
            success: function(response) {
                if (response.success) {
                    $('#concentrate_cost').val(response.costs.concentrate_cost);
                    $('#medication_cost').val(response.costs.medication_cost);
                    $('#biological_cost').val(response.costs.biological_cost);
                    $('#packaging_cost').val(response.costs.packaging_cost);
                    calculateTotalCost();
                    alert('Costos calculados desde inventario exitosamente');
                }
            },
            error: function() {
                alert('Error al calcular costos desde inventario');
            }
        });
    });

    // Calcular inicial
    calculateTotalCost();
    calculatePeriodDuration();
});
</script>
@endpush 