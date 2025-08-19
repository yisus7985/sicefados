@extends('avicontrol::layouts.admin')

@section('title', 'Nuevo Consumo de Alimento')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Consumo de Alimento
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('avicontrol.admin.food_consumption.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Volver
                        </a>
                    </div>
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
                                            <option value="{{ $galpon->id }}" {{ old('galpon_id') == $galpon->id ? 'selected' : '' }}>
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
                                <div class="form-group">
                                    <label for="producto_id">Producto (Alimento) *</label>
                                    <select name="producto_id" id="producto_id" 
                                            class="form-control @error('producto_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un producto</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}" 
                                                    data-stock="{{ $producto->current_stock }}"
                                                    data-price="{{ $producto->unit_price }}"
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
                                    <label for="cantidad_kg">Cantidad en Kilogramos</label>
                                    <input type="number" name="cantidad_kg" id="cantidad_kg" 
                                           class="form-control @error('cantidad_kg') is-invalid @enderror"
                                           value="{{ old('cantidad_kg') }}" min="0" step="0.01">
                                    <small class="form-text text-muted">O especifique bultos y peso por bulto</small>
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
                                           value="{{ old('cantidad_bultos') }}" min="0">
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
                                           value="{{ old('peso_por_bulto') }}" min="0" step="0.01">
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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Información:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>El sistema calculará automáticamente el consumo promedio por ave por día</li>
                                        <li>El inventario se actualizará automáticamente al registrar el consumo</li>
                                        <li>Puede especificar la cantidad en kg directamente o en bultos con su peso</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

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

@section('scripts')
<script>
$(document).ready(function() {
    // Calcular total cuando cambien los campos de bultos
    $('#cantidad_bultos, #peso_por_bulto').on('input', function() {
        var bultos = parseInt($('#cantidad_bultos').val()) || 0;
        var peso = parseFloat($('#peso_por_bulto').val()) || 0;
        var total = bultos * peso;
        
        if (total > 0) {
            $('#cantidad_kg').val(total.toFixed(2));
        }
    });

    // Validar stock disponible
    $('#producto_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var stock = parseInt(selectedOption.data('stock')) || 0;
        
        if (stock <= 0) {
            alert('El producto seleccionado no tiene stock disponible.');
        }
    });

    // Validar que se proporcione al menos una forma de cantidad
    $('form').on('submit', function(e) {
        var cantidadKg = parseFloat($('#cantidad_kg').val()) || 0;
        var bultos = parseInt($('#cantidad_bultos').val()) || 0;
        var pesoBulto = parseFloat($('#peso_por_bulto').val()) || 0;
        
        if (cantidadKg <= 0 && (bultos <= 0 || pesoBulto <= 0)) {
            e.preventDefault();
            alert('Debe proporcionar la cantidad en kg o en bultos con peso por bulto.');
            return false;
        }
    });
});
</script>
@endsection
