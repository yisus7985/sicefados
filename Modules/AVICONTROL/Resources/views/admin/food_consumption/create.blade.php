@extends('avicontrol::layouts.admin')

@section('title', 'Nuevo Consumo de Alimento')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-plus text-primary me-3"></i>
                    Nuevo Consumo de Alimento
                </h2>
                <p class="text-muted mb-0">Registre el consumo diario de alimentos para cada galpón</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('avicontrol.admin.food_consumption.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-utensils me-2"></i>
                        Formulario de Consumo
                    </h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Mensaje informativo -->
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Funcionalidades Automáticas:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Seleccione un galpón → Se auto-completa el número de aves</li>
                            <li>Seleccione un producto → Se auto-completa el peso del bulto</li>
                            <li>Ingrese los kg consumidos → Se calculan automáticamente los bultos</li>
                        </ul>
                    </div>

                    <form action="{{ route('avicontrol.admin.food_consumption.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_registro">Fecha de Registro *</label>
                                    <input type="date" name="fecha_registro" id="fecha_registro" 
                                           class="form-control @error('fecha_registro') is-invalid @enderror"
                                           value="{{ old('fecha_registro', date('Y-m-d')) }}" required>
                                    @error('fecha_registro')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="galpon_id">Galpón *</label>
                                    <select name="galpon_id" id="galpon_id" 
                                            class="form-control @error('galpon_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un galpón</option>
                                        @foreach($galpones as $galpon)
                                            <option value="{{ $galpon->id }}" 
                                                    data-aves="{{ $galpon->total_active_birds }}"
                                                    {{ old('galpon_id') == $galpon->id ? 'selected' : '' }}>
                                                {{ $galpon->name }} ({{ $galpon->total_active_birds }} aves)
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        <strong>Total de galpones:</strong> {{ $galpones->count() }} disponibles
                                    </small>
                                    <!-- Mensaje de estado en tiempo real -->
                                    <div id="galpon-status" class="mt-2" style="display: none;">
                                        <span class="badge bg-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <span id="galpon-status-text">Seleccione un galpón</span>
                                        </span>
                                    </div>
                                    @error('galpon_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="producto_id">Producto (Alimento) *</label>
                                    <select name="producto_id" id="producto_id" 
                                            class="form-control @error('producto_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un producto</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}" 
                                                    data-stock="{{ $producto->current_stock }}"
                                                    data-price="{{ $producto->unit_price }}"
                                                    data-bulto-weight="{{ $producto->bulto_weight ?? 40 }}"
                                                    {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                                {{ $producto->name }} (Stock: {{ $producto->current_stock }} kg)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('producto_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero_aves">Número de Aves *</label>
                                    <input type="number" name="numero_aves" id="numero_aves" 
                                           class="form-control @error('numero_aves') is-invalid @enderror"
                                           value="{{ old('numero_aves') }}" min="1" required>
                                    @error('numero_aves')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cantidad_kg">Cantidad en Kilogramos *</label>
                                    <input type="number" name="cantidad_kg" id="cantidad_kg" 
                                           class="form-control @error('cantidad_kg') is-invalid @enderror"
                                           value="{{ old('cantidad_kg') }}" min="0" step="0.01" placeholder="Ej: 39.215">
                                    <small class="form-text text-muted">
                                        <strong>Ejemplo:</strong> Si ingresa 40 kg y el bulto pesa 40 kg = 1 bulto | Si ingresa 80 kg = 2 bultos
                                    </small>
                                    @error('cantidad_kg')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cantidad_bultos">Cantidad de Bultos</label>
                                    <input type="number" name="cantidad_bultos" id="cantidad_bultos" 
                                           class="form-control @error('cantidad_bultos') is-invalid @enderror"
                                           value="{{ old('cantidad_bultos') }}" min="0" step="0.01" placeholder="Ej: 1.5">
                                    <small class="form-text text-muted">
                                        <strong>Opcional:</strong> Ingrese bultos para calcular los kg (Ej: 1 bulto = 40 kg)
                                    </small>
                                    @error('cantidad_bultos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="peso_por_bulto">Peso por Bulto (kg)</label>
                                    <input type="number" name="peso_por_bulto" id="peso_por_bulto" 
                                           class="form-control @error('peso_por_bulto') is-invalid @enderror"
                                           value="{{ old('peso_por_bulto') }}" min="0" step="0.01" placeholder="Ej: 40">
                                    <small class="form-text text-muted">Ingrese el peso estándar del bulto</small>
                                    @error('peso_por_bulto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observaciones">Observaciones</label>
                                    <textarea name="observaciones" id="observaciones" rows="3" 
                                              class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones') }}</textarea>
                                    @error('observaciones')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Campo oculto para el peso del bulto del producto -->
                        <input type="hidden" id="producto_bulto_weight" value="">

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i>
                                    Registrar Consumo
                                </button>
                                <a href="{{ route('avicontrol.admin.food_consumption.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-1"></i>
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Page Header */
.page-header {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 25px;
}

.page-title {
    color: #495057;
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.page-subtitle {
    color: #6c757d;
    margin: 10px 0 0 0;
    font-size: 1rem;
}

/* Form Styles */
.form-group {
    margin-bottom: 1.5rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-text {
    font-size: 0.875rem;
    margin-top: 0.25rem;
    color: #6c757d;
}

.form-text strong {
    color: #007bff;
}

/* Alert Styles */
.alert-info {
    background-color: rgba(23, 162, 184, 0.1);
    color: #0c5460;
    border-left: 4px solid #17a2b8;
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 15px;
    }
    
    .page-title {
        font-size: 1.25rem;
        text-align: center;
    }
    
    .page-subtitle {
        text-align: center;
        font-size: 0.9rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-completar número de aves al seleccionar galpón
    $('#galpon_id').on('change', function() {
        var galponId = $(this).val();
        var selectedOption = $(this).find('option:selected');
        var avesFromData = parseInt(selectedOption.data('aves')) || 0;
        
        if (galponId && avesFromData > 0) {
            $('#numero_aves').val(avesFromData);
            $('#galpon-status').show();
            $('#galpon-status-text').text(`Galpón seleccionado: ${avesFromData} aves activas`);
            $('#galpon-status .badge').removeClass('bg-info bg-warning').addClass('bg-success');
        } else if (galponId) {
            $('#galpon-status').show();
            $('#galpon-status-text').text('Este galpón no tiene aves activas');
            $('#galpon-status .badge').removeClass('bg-info bg-success').addClass('bg-warning');
        } else {
            $('#numero_aves').val('');
            $('#galpon-status').hide();
        }
    });

    // Auto-completar peso del bulto al seleccionar producto
    $('#producto_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var bultoWeight = parseFloat(selectedOption.data('bulto-weight')) || 40;
        
        $('#producto_bulto_weight').val(bultoWeight);
        $('#peso_por_bulto').val(bultoWeight);
    });

    // Cálculo bidireccional entre kg y bultos
    $('#cantidad_kg').on('input', function() {
        var cantidadKg = parseFloat($(this).val()) || 0;
        var pesoBulto = parseFloat($('#peso_por_bulto').val()) || 40;

        if (cantidadKg > 0 && pesoBulto > 0) {
            // Si tienes 40 kg y el bulto pesa 40 kg = 1 bulto
            var bultosCalculados = cantidadKg / pesoBulto;
            // Si es un número entero, mostrarlo sin decimales
            if (bultosCalculados % 1 === 0) {
                $('#cantidad_bultos').val(bultosCalculados);
            } else {
                $('#cantidad_bultos').val(bultosCalculados.toFixed(2));
            }
        } else {
            $('#cantidad_bultos').val('');
        }
    });

    // Evento para cantidad de bultos
    $('#cantidad_bultos').on('input', function() {
        var bultos = parseFloat($(this).val()) || 0;
        var peso = parseFloat($('#peso_por_bulto').val()) || 40;

        if (bultos > 0 && peso > 0) {
            // Si tienes 1 bulto de 40 kg = 40 kg total
            var totalKg = bultos * peso;
            // Si es un número entero, mostrarlo sin decimales
            if (totalKg % 1 === 0) {
                $('#cantidad_kg').val(totalKg);
            } else {
                $('#cantidad_kg').val(totalKg.toFixed(2));
            }
        } else {
            $('#cantidad_kg').val('');
        }
    });

    // Evento para peso por bulto
    $('#peso_por_bulto').on('input', function() {
        var peso = parseFloat($(this).val()) || 0;
        var bultos = parseFloat($('#cantidad_bultos').val()) || 0;
        var cantidadKg = parseFloat($('#cantidad_kg').val()) || 0;

        // Recalcular kg si hay bultos ingresados
        if (peso > 0 && bultos > 0) {
            var totalKg = bultos * peso;
            if (totalKg % 1 === 0) {
                $('#cantidad_kg').val(totalKg);
            } else {
                $('#cantidad_kg').val(totalKg.toFixed(2));
            }
        }
        
        // Recalcular bultos si hay kg ingresados
        if (cantidadKg > 0 && peso > 0) {
            var bultosCalculados = cantidadKg / peso;
            if (bultosCalculados % 1 === 0) {
                $('#cantidad_bultos').val(bultosCalculados);
            } else {
                $('#cantidad_bultos').val(bultosCalculados.toFixed(2));
            }
        }
    });

    // Validación del formulario
    $('form').on('submit', function(e) {
        var cantidadKg = parseFloat($('#cantidad_kg').val()) || 0;
        var bultos = parseFloat($('#cantidad_bultos').val()) || 0;
        var pesoBulto = parseFloat($('#peso_por_bulto').val()) || 0;
        var numeroAves = parseInt($('#numero_aves').val()) || 0;
        
        if (cantidadKg <= 0 && (bultos <= 0 || pesoBulto <= 0)) {
            e.preventDefault();
            alert('Debe proporcionar la cantidad en kg o en bultos con peso por bulto.');
            return false;
        }
        
        if (numeroAves <= 0) {
            e.preventDefault();
            alert('Debe seleccionar un galpón válido con aves activas.');
            return false;
        }
    });

    // Inicializar campos si hay valores old
    if ($('#producto_id').val()) {
        $('#producto_id').trigger('change');
    }
    if ($('#galpon_id').val()) {
        $('#galpon_id').trigger('change');
    }
});
</script>
@endpush