@extends('avicontrol::layouts.admin')

@section('title', 'Nuevo Registro de Producción')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-plus-circle text-primary me-3"></i>
                    Nuevo Registro de Producción - {{ $tipoProduccion === 'huevos' ? 'Huevos' : 'Carne' }}
                </h2>
                <p class="text-muted mb-0">Complete el formulario para registrar la producción de {{ $tipoProduccion === 'huevos' ? 'huevos' : 'carne' }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('avicontrol.admin.production.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>
        </div>
    </div>

    <!-- Production Form -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-{{ $tipoProduccion === 'huevos' ? 'egg' : 'drumstick-bite' }} me-2"></i>
                        Información de Producción - {{ $tipoProduccion === 'huevos' ? 'Huevos' : 'Carne' }}
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('avicontrol.admin.production.store') }}" id="productionForm">
                        @csrf
                        
                        <!-- Campos ocultos -->
                        <input type="hidden" name="tipo_produccion" value="{{ $tipoProduccion }}">
                        
                        <div class="row g-3">
                            <!-- Fecha -->
                            <div class="col-md-6">
                                <label for="fecha" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Fecha de {{ $tipoProduccion === 'huevos' ? 'Recolección' : 'Producción' }} <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                       name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Galpón -->
                            <div class="col-md-6">
                                <label for="galpon_id" class="form-label">
                                    <i class="fas fa-home me-1"></i>Galpón <span class="text-danger">*</span>
                                </label>
                                <select name="galpon_id" id="galpon_id" class="form-select @error('galpon_id') is-invalid @enderror" required>
                                    <option value="">Seleccione el galpón</option>
                                    @foreach($galpones as $galpon)
                                        <option value="{{ $galpon->id }}" {{ old('galpon_id') == $galpon->id ? 'selected' : '' }}>
                                            {{ $galpon->name }} ({{ $galpon->tipo_display }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('galpon_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipo -->
                            <div class="col-md-6">
                                <label for="tipo" class="form-label">
                                    <i class="fas fa-layer-group me-1"></i>Tipo <span class="text-danger">*</span>
                                </label>
                                <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                    <option value="">Seleccione el tipo</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo') == $tipo ? 'selected' : '' }}>
                                            Tipo {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cantidad -->
                            <div class="col-md-6">
                                <label for="cantidad" class="form-label">
                                    <i class="fas fa-sort-numeric-up me-1"></i>Cantidad <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('cantidad') is-invalid @enderror" 
                                       name="cantidad" id="cantidad" value="{{ old('cantidad') }}" 
                                       min="1" step="1" required>
                                <small class="form-text text-muted">
                                    {{ $tipoProduccion === 'huevos' ? 'Número de huevos' : 'Número de aves' }}
                                </small>
                                @error('cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mortalidad de Aves -->
                            <div class="col-md-6">
                                <label for="mortalidad_aves" class="form-label">
                                    <i class="fas fa-heart-broken me-1"></i>Mortalidad de Aves
                                </label>
                                <input type="number" class="form-control @error('mortalidad_aves') is-invalid @enderror" 
                                       name="mortalidad_aves" id="mortalidad_aves" value="{{ old('mortalidad_aves', 0) }}" 
                                       min="0" step="1">
                                <small class="form-text text-muted">
                                    Número de aves que han fallecido (se actualizará automáticamente el galpón)
                                </small>
                                @error('mortalidad_aves')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Campos específicos para carne -->
                            @if($tipoProduccion === 'carne')
                                <!-- Peso Promedio -->
                                <div class="col-md-6">
                                    <label for="peso_promedio" class="form-label">
                                        <i class="fas fa-weight-hanging me-1"></i>Peso Promedio (kg) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('peso_promedio') is-invalid @enderror" 
                                           name="peso_promedio" id="peso_promedio" value="{{ old('peso_promedio') }}" 
                                           min="0" step="0.01" required>
                                    @error('peso_promedio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Peso Total -->
                                <div class="col-md-6">
                                    <label for="peso_total" class="form-label">
                                        <i class="fas fa-weight-hanging me-1"></i>Peso Total (kg)
                                    </label>
                                    <input type="number" class="form-control @error('peso_total') is-invalid @enderror" 
                                           name="peso_total" id="peso_total" value="{{ old('peso_total') }}" 
                                           min="0" step="0.01" readonly>
                                    <small class="form-text text-muted">Se calcula automáticamente</small>
                                    @error('peso_total')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Campos específicos para huevos -->
                            @if($tipoProduccion === 'huevos')
                                <!-- Huevos Rotos -->
                                <div class="col-md-6">
                                    <label for="huevos_rotos" class="form-label">
                                        <i class="fas fa-times-circle me-1"></i>Huevos Rotos
                                    </label>
                                    <input type="number" class="form-control @error('huevos_rotos') is-invalid @enderror" 
                                           name="huevos_rotos" id="huevos_rotos" value="{{ old('huevos_rotos', 0) }}" 
                                           min="0" step="1">
                                    @error('huevos_rotos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Huevos Sucios -->
                                <div class="col-md-6">
                                    <label for="huevos_sucios" class="form-label">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Huevos Sucios
                                    </label>
                                    <input type="number" class="form-control @error('huevos_sucios') is-invalid @enderror" 
                                           name="huevos_sucios" id="huevos_sucios" value="{{ old('huevos_sucios', 0) }}" 
                                           min="0" step="1">
                                    @error('huevos_sucios')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Valor por Unidad -->
                            <div class="col-md-6">
                                <label for="valor_unidad" class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>Valor por {{ $tipoProduccion === 'huevos' ? 'Unidad' : 'Kg' }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('valor_unidad') is-invalid @enderror" 
                                           name="valor_unidad" id="valor_unidad" value="{{ old('valor_unidad') }}" 
                                           min="0" step="0.01" required>
                                </div>
                                @error('valor_unidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Valor Total (Calculado) -->
                            <div class="col-md-6">
                                <label for="valor_total" class="form-label">
                                    <i class="fas fa-calculator me-1"></i>Valor Total
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" class="form-control" id="valor_total_display" readonly>
                                    <input type="hidden" name="valor_total" id="valor_total">
                                </div>
                                <small class="form-text text-muted">Se calcula automáticamente</small>
                            </div>

                            <!-- Destino -->
                            <div class="col-md-6">
                                <label for="destino" class="form-label">
                                    <i class="fas fa-truck me-1"></i>Destino <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('destino') is-invalid @enderror" 
                                       name="destino" id="destino" value="{{ old('destino', 'Punto venta') }}" required>
                                @error('destino')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Semana de Producción -->
                            <div class="col-md-6">
                                <label for="semana_produccion" class="form-label">
                                    <i class="fas fa-calendar-week me-1"></i>Semana de Producción
                                </label>
                                <select name="semana_produccion" id="semana_produccion" class="form-select @error('semana_produccion') is-invalid @enderror">
                                    <option value="">Seleccione la semana</option>
                                    @foreach($semanas as $semana)
                                        <option value="{{ $semana }}" {{ old('semana_produccion') == $semana ? 'selected' : '' }}>
                                            {{ $semana }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('semana_produccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Firma Recibido -->
                            <div class="col-md-6">
                                <label for="firma_recibido" class="form-label">
                                    <i class="fas fa-user me-1"></i>Firma Recibido <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('firma_recibido') is-invalid @enderror" 
                                       name="firma_recibido" id="firma_recibido" value="{{ old('firma_recibido') }}" required>
                                @error('firma_recibido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Firma Líder -->
                            <div class="col-md-6">
                                <label for="firma_lider" class="form-label">
                                    <i class="fas fa-user-tie me-1"></i>Firma Líder
                                </label>
                                <input type="text" class="form-control @error('firma_lider') is-invalid @enderror" 
                                       name="firma_lider" id="firma_lider" value="{{ old('firma_lider') }}">
                                @error('firma_lider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Observaciones -->
                            <div class="col-12">
                                <label for="observaciones" class="form-label">
                                    <i class="fas fa-comment me-1"></i>Observaciones
                                </label>
                                <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                          name="observaciones" id="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('avicontrol.admin.production.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Guardar Registro
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Calcular valor total automáticamente
    function calcularValorTotal() {
        const cantidad = parseFloat($('#cantidad').val()) || 0;
        const valorUnidad = parseFloat($('#valor_unidad').val()) || 0;
        const valorTotal = cantidad * valorUnidad;
        
        $('#valor_total_display').val('$' + valorTotal.toLocaleString('es-CO', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }));
        $('#valor_total').val(valorTotal);
    }

    // Calcular peso total automáticamente (solo para carne)
    function calcularPesoTotal() {
        const cantidad = parseFloat($('#cantidad').val()) || 0;
        const pesoPromedio = parseFloat($('#peso_promedio').val()) || 0;
        const pesoTotal = cantidad * pesoPromedio;
        
        $('#peso_total').val(pesoTotal.toFixed(2));
    }

    // Event listeners para cálculos automáticos
    $('#cantidad, #valor_unidad').on('input', function() {
        calcularValorTotal();
        @if($tipoProduccion === 'carne')
            calcularPesoTotal();
        @endif
    });

    @if($tipoProduccion === 'carne')
        $('#peso_promedio').on('input', function() {
            calcularPesoTotal();
        });
    @endif

    // Validación de formulario
    $('#productionForm').on('submit', function(e) {
        const cantidad = parseInt($('#cantidad').val()) || 0;
        const huevosRotos = parseInt($('#huevos_rotos').val()) || 0;
        const huevosSucios = parseInt($('#huevos_sucios').val()) || 0;

        @if($tipoProduccion === 'huevos')
            if (huevosRotos + huevosSucios > cantidad) {
                e.preventDefault();
                alert('La suma de huevos rotos y sucios no puede ser mayor que la cantidad total.');
                return false;
            }
        @endif

        return true;
    });

    // Inicializar cálculos
    calcularValorTotal();
    @if($tipoProduccion === 'carne')
        calcularPesoTotal();
    @endif
});
</script>
@endpush
