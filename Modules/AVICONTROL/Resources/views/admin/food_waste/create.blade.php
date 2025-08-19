@extends('avicontrol::layouts.admin')

@section('title', 'Nuevo Registro de Merma de Alimento')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle"></i> Nuevo Registro de Merma de Alimento
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('avicontrol.admin.food_waste.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('avicontrol.admin.food_waste.store') }}" method="POST" id="formMerma">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_registro">Fecha de Registro <span class="text-danger">*</span></label>
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
                                    <label for="galpon_id">Galpón <span class="text-danger">*</span></label>
                                    <select name="galpon_id" id="galpon_id" class="form-control @error('galpon_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un galpón</option>
                                        @foreach($galpones ?? [] as $galpon)
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
                                    <label for="producto_id">Producto <span class="text-danger">*</span></label>
                                    <select name="producto_id" id="producto_id" class="form-control @error('producto_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un producto</option>
                                        @foreach($productos ?? [] as $producto)
                                            <option value="{{ $producto->id }}" 
                                                    data-precio="{{ $producto->precio_unitario ?? 0 }}"
                                                    {{ old('producto_id') == $producto->id ? 'selected' : '' }}>
                                                {{ $producto->nombre }} - Stock: {{ $producto->stock_actual ?? 0 }} kg
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
                                    <label for="cantidad_perdida">Cantidad Perdida (kg) <span class="text-danger">*</span></label>
                                    <input type="number" name="cantidad_perdida" id="cantidad_perdida" 
                                           class="form-control @error('cantidad_perdida') is-invalid @enderror" 
                                           value="{{ old('cantidad_perdida') }}" step="0.01" min="0" required>
                                    @error('cantidad_perdida')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Stock disponible: <span id="stock_disponible">0</span> kg
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="causa_merma">Causa de Merma <span class="text-danger">*</span></label>
                                    <select name="causa_merma" id="causa_merma" class="form-control @error('causa_merma') is-invalid @enderror" required>
                                        <option value="">Seleccione la causa</option>
                                        <option value="derrame" {{ old('causa_merma') == 'derrame' ? 'selected' : '' }}>Derrame</option>
                                        <option value="contaminacion" {{ old('causa_merma') == 'contaminacion' ? 'selected' : '' }}>Contaminación</option>
                                        <option value="roedores" {{ old('causa_merma') == 'roedores' ? 'selected' : '' }}>Roedores</option>
                                        <option value="humedad" {{ old('causa_merma') == 'humedad' ? 'selected' : '' }}>Humedad</option>
                                        <option value="caducidad" {{ old('causa_merma') == 'caducidad' ? 'selected' : '' }}>Caducidad</option>
                                        <option value="transporte" {{ old('causa_merma') == 'transporte' ? 'selected' : '' }}>Transporte</option>
                                        <option value="almacenamiento" {{ old('causa_merma') == 'almacenamiento' ? 'selected' : '' }}>Almacenamiento</option>
                                        <option value="otros" {{ old('causa_merma') == 'otros' ? 'selected' : '' }}>Otros</option>
                                    </select>
                                    @error('causa_merma')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="responsable">Responsable <span class="text-danger">*</span></label>
                                    <input type="text" name="responsable" id="responsable" 
                                           class="form-control @error('responsable') is-invalid @enderror" 
                                           value="{{ old('responsable', auth()->user()->name ?? '') }}" required>
                                    @error('responsable')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo_perdida">Costo de Pérdida</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="costo_perdida" id="costo_perdida" 
                                               class="form-control @error('costo_perdida') is-invalid @enderror" 
                                               value="{{ old('costo_perdida') }}" step="0.01" min="0" readonly>
                                        @error('costo_perdida')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Se calcula automáticamente: Cantidad × Precio Unitario
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Precio Unitario</label>
                                    <div class="form-control-plaintext">
                                        $<span id="precio_unitario_display">0.00</span> / kg
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="3" 
                                      class="form-control @error('observaciones') is-invalid @enderror" 
                                      placeholder="Describa detalles adicionales sobre la merma...">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alertas de validación -->
                        <div id="alertas" class="mt-3"></div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Registro
                            </button>
                            <a href="{{ route('avicontrol.admin.food_waste.index') }}" class="btn btn-secondary">
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

@push('scripts')
<script>
$(document).ready(function() {
    // Variables globales
    let productos = @json($productos ?? []);
    let stockDisponible = 0;
    let precioUnitario = 0;

    // Calcular costo cuando cambien cantidad o producto
    $('#cantidad_perdida, #producto_id').on('input change', function() {
        calcularCosto();
        validarStock();
    });

    // Actualizar información del producto cuando se seleccione
    $('#producto_id').on('change', function() {
        actualizarInfoProducto();
    });

    function actualizarInfoProducto() {
        let productoId = $('#producto_id').val();
        let producto = productos.find(p => p.id == productoId);
        
        if (producto) {
            stockDisponible = parseFloat(producto.stock_actual) || 0;
            precioUnitario = parseFloat(producto.precio_unitario) || 0;
            
            $('#stock_disponible').text(stockDisponible.toFixed(2));
            $('#precio_unitario_display').text(precioUnitario.toFixed(2));
        } else {
            stockDisponible = 0;
            precioUnitario = 0;
            $('#stock_disponible').text('0.00');
            $('#precio_unitario_display').text('0.00');
        }
        
        calcularCosto();
        validarStock();
    }

    function calcularCosto() {
        let cantidad = parseFloat($('#cantidad_perdida').val()) || 0;
        let costo = cantidad * precioUnitario;
        
        $('#costo_perdida').val(costo.toFixed(2));
    }

    function validarStock() {
        let cantidad = parseFloat($('#cantidad_perdida').val()) || 0;
        let alertas = $('#alertas');
        alertas.empty();

        if (cantidad > stockDisponible) {
            alertas.append(`
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Advertencia:</strong> La cantidad perdida (${cantidad} kg) es mayor al stock disponible (${stockDisponible} kg).
                </div>
            `);
        }

        if (cantidad > 0 && stockDisponible > 0) {
            let porcentaje = (cantidad / stockDisponible) * 100;
            if (porcentaje > 50) {
                alertas.append(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Alerta:</strong> La merma representa el ${porcentaje.toFixed(1)}% del stock disponible.
                    </div>
                `);
            }
        }
    }

    // Validación del formulario
    $('#formMerma').on('submit', function(e) {
        let fecha = $('#fecha_registro').val();
        let galponId = $('#galpon_id').val();
        let productoId = $('#producto_id').val();
        let cantidad = parseFloat($('#cantidad_perdida').val()) || 0;
        let causa = $('#causa_merma').val();
        let responsable = $('#responsable').val().trim();

        if (!fecha || !galponId || !productoId || cantidad <= 0 || !causa || !responsable) {
            alert('Por favor complete todos los campos requeridos.');
            e.preventDefault();
            return false;
        }

        if (cantidad > stockDisponible) {
            if (!confirm('La cantidad perdida es mayor al stock disponible. ¿Desea continuar de todas formas?')) {
                e.preventDefault();
                return false;
            }
        }

        return true;
    });

    // Inicializar información del producto si hay un valor seleccionado
    if ($('#producto_id').val()) {
        actualizarInfoProducto();
    }
});
</script>
@endpush
