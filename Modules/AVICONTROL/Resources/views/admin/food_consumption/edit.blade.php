@extends('avicontrol::layouts.admin')

@section('title', 'Editar Consumo de Alimento')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-edit text-warning me-3"></i>
                    Editar Consumo de Alimento
                </h2>
                <p class="text-muted mb-0">Modifique la información del registro de consumo</p>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <a href="{{ route('avicontrol.admin.food_consumption.show', $consumo->id) }}" 
                       class="btn btn-info">
                        <i class="fas fa-eye me-2"></i>Ver Detalles
                    </a>
                    <a href="{{ route('avicontrol.admin.food_consumption.index') }}" 
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Formulario de Edición
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Alerta de funcionalidades automáticas -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Funcionalidades Automáticas:</strong> 
                        Los campos se calculan automáticamente. Puede ingresar la cantidad en kilogramos o en bultos con su peso correspondiente.
                    </div>

                    <form action="{{ route('avicontrol.admin.food_consumption.update', $consumo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="fecha_registro" class="form-label fw-bold">
                                        <i class="fas fa-calendar me-2 text-primary"></i>Fecha de Registro
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('fecha_registro') is-invalid @enderror" 
                                           id="fecha_registro" 
                                           name="fecha_registro" 
                                           value="{{ old('fecha_registro', $consumo->fecha_registro->format('Y-m-d')) }}" 
                                           required>
                                    @error('fecha_registro')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="galpon_id" class="form-label fw-bold">
                                        <i class="fas fa-warehouse me-2 text-primary"></i>Galpón
                                    </label>
                                    <select class="form-select @error('galpon_id') is-invalid @enderror" 
                                            id="galpon_id" 
                                            name="galpon_id" 
                                            required>
                                        <option value="">Seleccione un galpón</option>
                                        @foreach($galpones as $galpon)
                                            <option value="{{ $galpon->id }}" 
                                                    {{ old('galpon_id', $consumo->galpon_id) == $galpon->id ? 'selected' : '' }}>
                                                {{ $galpon->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('galpon_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="producto_id" class="form-label fw-bold">
                                        <i class="fas fa-box me-2 text-info"></i>Producto
                                    </label>
                                    <select class="form-select @error('producto_id') is-invalid @enderror" 
                                            id="producto_id" 
                                            name="producto_id" 
                                            required>
                                        <option value="">Seleccione un producto</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}" 
                                                    {{ old('producto_id', $consumo->producto_id) == $producto->id ? 'selected' : '' }}>
                                                {{ $producto->name }} - Stock: {{ $producto->current_stock }} kg
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('producto_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="numero_aves" class="form-label fw-bold">
                                        <i class="fas fa-kiwi-bird me-2 text-success"></i>Número de Aves
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('numero_aves') is-invalid @enderror" 
                                           id="numero_aves" 
                                           name="numero_aves" 
                                           value="{{ old('numero_aves', $consumo->numero_aves) }}" 
                                           min="1" 
                                           placeholder="Ej: 1000" 
                                           required>
                                    @error('numero_aves')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="cantidad_kg" class="form-label fw-bold">
                                        <i class="fas fa-weight-hanging me-2 text-success"></i>Cantidad en Kilogramos
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('cantidad_kg') is-invalid @enderror" 
                                           id="cantidad_kg" 
                                           name="cantidad_kg" 
                                           value="{{ old('cantidad_kg', $consumo->cantidad_kg) }}" 
                                           step="0.01" 
                                           min="0" 
                                           placeholder="Ej: 40.00">
                                    @error('cantidad_kg')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Deje vacío si va a usar bultos</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="cantidad_bultos" class="form-label fw-bold">
                                        <i class="fas fa-boxes me-2 text-info"></i>Cantidad de Bultos
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('cantidad_bultos') is-invalid @enderror" 
                                           id="cantidad_bultos" 
                                           name="cantidad_bultos" 
                                           value="{{ old('cantidad_bultos', $consumo->cantidad_bultos) }}" 
                                           min="0" 
                                           placeholder="Ej: 1">
                                    @error('cantidad_bultos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Deje vacío si va a usar kg</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="peso_por_bulto" class="form-label fw-bold">
                                        <i class="fas fa-balance-scale me-2 text-warning"></i>Peso por Bulto (kg)
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('peso_por_bulto') is-invalid @enderror" 
                                           id="peso_por_bulto" 
                                           name="peso_por_bulto" 
                                           value="{{ old('peso_por_bulto', $consumo->peso_por_bulto) }}" 
                                           step="0.01" 
                                           min="0" 
                                           placeholder="Ej: 40.00">
                                    @error('peso_por_bulto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Peso estándar por bulto</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="responsable" class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-secondary"></i>Responsable
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('responsable') is-invalid @enderror" 
                                           id="responsable" 
                                           name="responsable" 
                                           value="{{ old('responsable', $consumo->responsable) }}" 
                                           placeholder="Nombre del responsable" 
                                           required>
                                    @error('responsable')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="estado" class="form-label fw-bold">
                                        <i class="fas fa-toggle-on me-2 text-info"></i>Estado
                                    </label>
                                    <select class="form-select @error('estado') is-invalid @enderror" 
                                            id="estado" 
                                            name="estado" 
                                            required>
                                        <option value="active" {{ old('estado', $consumo->estado) == 'active' ? 'selected' : '' }}>Activo</option>
                                        <option value="pending" {{ old('estado', $consumo->estado) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="cancelled" {{ old('estado', $consumo->estado) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="observaciones" class="form-label fw-bold">
                                        <i class="fas fa-comment me-2 text-info"></i>Observaciones
                                    </label>
                                    <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                              id="observaciones" 
                                              name="observaciones" 
                                              rows="3" 
                                              placeholder="Observaciones adicionales...">{{ old('observaciones', $consumo->observaciones) }}</textarea>
                                    @error('observaciones')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('avicontrol.admin.food_consumption.index') }}" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-2"></i>Actualizar Consumo
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

@push('styles')
<style>
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

.form-label {
    color: #495057;
    font-weight: 600;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 10px 20px;
}

.alert {
    border-radius: 10px;
    border: none;
}

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
    
    .btn-group {
        width: 100%;
        margin-top: 15px;
    }
    
    .btn-group .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar Select2 para los selectores
    $('#galpon_id, #producto_id').select2({
        placeholder: 'Seleccione una opción',
        allowClear: true
    });

    // Cargar número de aves cuando se selecciona un galpón
    $('#galpon_id').on('change', function() {
        const galponId = $(this).val();
        if (galponId) {
            $.ajax({
                url: '{{ route("avicontrol.admin.food_consumption.get_birds_count") }}',
                method: 'POST',
                data: {
                    galpon_id: galponId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#numero_aves').val(response.total_birds);
                    }
                },
                error: function() {
                    console.log('Error al obtener número de aves');
                }
            });
        }
    });
});

// Cálculos automáticos
$('#cantidad_kg').on('input', function() {
    var cantidadKg = parseFloat($(this).val()) || 0;
    var pesoBulto = parseFloat($('#peso_por_bulto').val()) || 40;

    if (cantidadKg > 0 && pesoBulto > 0) {
        var bultosCalculados = cantidadKg / pesoBulto;
        if (bultosCalculados % 1 === 0) {
            $('#cantidad_bultos').val(bultosCalculados);
        } else {
            $('#cantidad_bultos').val(bultosCalculados.toFixed(2));
        }
    } else {
        $('#cantidad_bultos').val('');
    }
});

$('#cantidad_bultos').on('input', function() {
    var bultos = parseFloat($(this).val()) || 0;
    var peso = parseFloat($('#peso_por_bulto').val()) || 40;

    if (bultos > 0 && peso > 0) {
        var totalKg = bultos * peso;
        if (totalKg % 1 === 0) {
            $('#cantidad_kg').val(totalKg);
        } else {
            $('#cantidad_kg').val(totalKg.toFixed(2));
        }
    } else {
        $('#cantidad_kg').val('');
    }
});

$('#peso_por_bulto').on('input', function() {
    var peso = parseFloat($(this).val()) || 0;
    var bultos = parseFloat($('#cantidad_bultos').val()) || 0;
    var cantidadKg = parseFloat($('#cantidad_kg').val()) || 0;

    // Recalcular kg if bultos are entered
    if (peso > 0 && bultos > 0) {
        var totalKg = bultos * peso;
        if (totalKg % 1 === 0) {
            $('#cantidad_kg').val(totalKg);
        } else {
            $('#cantidad_kg').val(totalKg.toFixed(2));
        }
    }
    
    // Recalcular bultos if kg are entered
    if (cantidadKg > 0 && peso > 0) {
        var bultosCalculados = cantidadKg / peso;
        if (bultosCalculados % 1 === 0) {
            $('#cantidad_bultos').val(bultosCalculados);
        } else {
            $('#cantidad_bultos').val(bultosCalculados.toFixed(2));
        }
    }
});
</script>
@endpush
