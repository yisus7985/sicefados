$content = @'@extends('avicontrol::layouts.admin')

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

                    <!-- Mensaje de ayuda principal -->
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>¡Nuevas Funcionalidades Automáticas!</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>1.</strong> <span class="text-primary">Seleccione un galpón → Se auto-completa el número de aves</span></li>
                            <li><strong>2.</strong> Seleccione un producto → Se auto-completa el peso del bulto</li>
                            <li><strong>3.</strong> Ingrese los kg consumidos → Se calculan automáticamente los bultos</li>
                        </ul>
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong>💡 Instrucciones para probar:</strong>
                            <ol class="mb-0 mt-2">
                                <li>Haga clic en el dropdown "Galpón"</li>
                                <li>Seleccione cualquier galpón de la lista</li>
                                <li>El campo "Número de Aves" se llenará automáticamente</li>
                                <li>Si no funciona, use los botones de prueba abajo</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Debug: Información de galpones disponibles -->
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Debug - Galpones disponibles:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($galpones as $galpon)
                                <li>ID: {{ $galpon->id }} - Nombre: {{ $galpon->name }} - Aves: {{ $galpon->total_active_birds }}</li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-warning" onclick="testJavaScript()">
                                <i class="fas fa-bug me-1"></i> Probar JavaScript
                            </button>
                            <button type="button" class="btn btn-sm btn-info" onclick="testGalpones()">
                                <i class="fas fa-list me-1"></i> Ver Galpones en Consola
                            </button>
                            <button type="button" class="btn btn-sm btn-success" onclick="testGalponChange()">
                                <i class="fas fa-play me-1"></i> Probar Cambio de Galpón
                            </button>
                        </div>
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
                                        <strong>Ejemplo:</strong> Si ingresa 39.215 kg y el bulto pesa 40 kg, se calcularán automáticamente 0.98 bultos
                                    </small>
                                    @error('cantidad_kg')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cantidad_bultos">Cantidad de Bultos (Calculado)</label>
                                    <input type="number" name="cantidad_bultos" id="cantidad_bultos" 
                                           class="form-control @error('cantidad_bultos') is-invalid @enderror"
                                           value="{{ old('cantidad_bultos') }}" min="0" step="0.01" readonly>
                                    <small class="form-text text-muted">
                                        <strong>Automático:</strong> Se calcula dividiendo kg consumidos entre peso del bulto
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
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Información del Sistema:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Número de Aves:</strong> Se auto-completa al seleccionar el galpón</li>
                                        <li><strong>Peso del Bulto:</strong> Se auto-completa al seleccionar el producto</li>
                                        <li><strong>Cálculo Automático:</strong> Al ingresar kg, se calculan automáticamente los bultos</li>
                                        <li><strong>Consumo Promedio:</strong> El sistema calculará el consumo por ave por día</li>
                                        <li><strong>Inventario:</strong> Se actualiza automáticamente al registrar el consumo</li>
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

@section('styles')
<style>
    .alert-message {
        margin-bottom: 20px;
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .alert-info {
        background-color: rgba(59, 130, 246, 0.1);
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }
    
    .alert-warning {
        background-color: rgba(245, 158, 11, 0.1);
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }
    
    .alert-danger {
        background-color: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-control:focus {
        border-color: #4d7c0f;
        box-shadow: 0 0 0 0.2rem rgba(77, 124, 15, 0.25);
    }
    
    .btn-primary {
        background-color: #4d7c0f;
        border-color: #4d7c0f;
    }
    
    .btn-primary:hover {
        background-color: #3f6a0a;
        border-color: #3f6a0a;
    }
    
    input[readonly] {
        background-color: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
    }
    
    .form-text {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .form-text strong {
        color: #4d7c0f;
    }
    
    .alert-success {
        background-color: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border-left: 4px solid #10b981;
    }
    
    .alert-success ul {
        margin-bottom: 0;
    }
    
    .alert-success li {
        margin-bottom: 0.25rem;
    }
    
    .alert-success li:last-child {
        margin-bottom: 0;
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    console.log('🚀 JavaScript del formulario cargado correctamente');
    console.log('📡 URL de la ruta AJAX:', '{{ route("avicontrol.admin.food_consumption.get_birds_count") }}');
    console.log('🔍 Elementos encontrados:');
    console.log('  - Select galpón:', $('#galpon_id').length);
    console.log('  - Campo aves:', $('#numero_aves').length);
    console.log('  - Campo producto:', $('#producto_id').length);
    
    // Mostrar mensaje de inicialización
    showInfoMessage('✅ Formulario cargado correctamente. Seleccione un galpón para auto-completar el número de aves.');
    
    // 1. AUTO-COMPLETAR NÚMERO DE AVES AL SELECCIONAR GALPÓN
    $('#galpon_id').on('change', function() {
        var galponId = $(this).val();
        var selectedOption = $(this).find('option:selected');
        var avesFromData = parseInt(selectedOption.data('aves')) || 0;
        
        console.log('=== EVENTO CHANGE DEL GALPÓN ===');
        console.log('Galpón seleccionado:', galponId);
        console.log('Opción seleccionada:', selectedOption.text());
        console.log('Aves desde data-aves:', avesFromData);
        console.log('Campo numero_aves encontrado:', $('#numero_aves').length);
        
        if (galponId) {
            // ACTUALIZAR INMEDIATAMENTE CON EL VALOR DEL DATA-AVES
            if (avesFromData > 0) {
                $('#numero_aves').val(avesFromData);
                console.log('✅ Número de aves actualizado desde data-aves:', avesFromData);
                
                // Mostrar estado en tiempo real
                $('#galpon-status').show();
                $('#galpon-status-text').text(`Galpón seleccionado: ${avesFromData} aves activas`);
                $('#galpon-status .badge').removeClass('bg-info bg-warning').addClass('bg-success');
                
                // Mostrar mensaje informativo
                showInfoMessage(`Se seleccionó el galpón "${selectedOption.text().split(' (')[0]}" con ${avesFromData} aves activas`);
            } else {
                console.log('⚠️ El galpón seleccionado no tiene aves activas');
                
                // Mostrar estado en tiempo real
                $('#galpon-status').show();
                $('#galpon-status-text').text('Este galpón no tiene aves activas');
                $('#galpon-status .badge').removeClass('bg-info bg-success').addClass('bg-warning');
                
                showWarningMessage('Este galpón no tiene aves activas registradas.');
            }
            
            // HACER LLAMADA AJAX COMO RESPALDO
            console.log('Haciendo llamada AJAX para verificar datos...');
            $.ajax({
                url: '{{ route("avicontrol.admin.food_consumption.get_birds_count") }}',
                type: 'POST',
                data: {
                    galpon_id: galponId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('✅ Respuesta AJAX exitosa:', response);
                    if (response.success && response.total_birds > 0) {
                        // Solo actualizar si el valor AJAX es diferente
                        if (response.total_birds !== avesFromData) {
                            $('#numero_aves').val(response.total_birds);
                            console.log('🔄 Número de aves actualizado desde AJAX:', response.total_birds);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Error en AJAX:', xhr.responseText, status, error);
                    console.log('✅ Usando valor de respaldo desde data-aves:', avesFromData);
                }
            });
        } else {
            $('#numero_aves').val('');
            console.log('Campo de aves limpiado');
            
            // Ocultar estado en tiempo real
            $('#galpon-status').hide();
        }
    });

    // 2. AUTO-COMPLETAR PESO DEL BULTO AL SELECCIONAR PRODUCTO
    $('#producto_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var bultoWeight = parseFloat(selectedOption.data('bulto-weight')) || 40;
        
        // Actualizar el campo oculto y el campo visible
        $('#producto_bulto_weight').val(bultoWeight);
        $('#peso_por_bulto').val(bultoWeight);
        
        // Mostrar mensaje informativo
        if (bultoWeight > 0) {
            showInfoMessage(`Peso del bulto de ${selectedOption.text().split(' (')[0]}: ${bultoWeight} kg`);
        }
    });

    // 3. CALCULAR AUTOMÁTICAMENTE CANTIDAD DE BULTOS AL INGRESAR KG
    $('#cantidad_kg').on('input', function() {
        var cantidadKg = parseFloat($(this).val()) || 0;
        var pesoBulto = parseFloat($('#peso_por_bulto').val()) || 0;
        
        if (cantidadKg > 0 && pesoBulto > 0) {
            var bultosCalculados = cantidadKg / pesoBulto;
            $('#cantidad_bultos').val(bultosCalculados.toFixed(2));
            
            // Mostrar mensaje informativo
            showInfoMessage(`Se calcularon ${bultosCalculados.toFixed(2)} bultos (${cantidadKg} kg ÷ ${pesoBulto} kg/bulto)`);
        }
    });

    // 4. CALCULAR TOTAL CUANDO CAMBIEN LOS CAMPOS DE BULTOS
    $('#cantidad_bultos, #peso_por_bulto').on('input', function() {
        var bultos = parseFloat($('#cantidad_bultos').val()) || 0;
        var peso = parseFloat($('#peso_por_bulto').val()) || 0;
        var total = bultos * peso;
        
        if (total > 0) {
            $('#cantidad_kg').val(total.toFixed(2));
        }
    });

    // 5. VALIDAR STOCK DISPONIBLE
    $('#producto_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var stock = parseInt(selectedOption.data('stock')) || 0;
        
        if (stock <= 0) {
            showWarningMessage('El producto seleccionado no tiene stock disponible.');
        }
    });

    // 6. VALIDAR QUE SE PROPORCIONE AL MENOS UNA FORMA DE CANTIDAD
    $('form').on('submit', function(e) {
        var cantidadKg = parseFloat($('#cantidad_kg').val()) || 0;
        var bultos = parseFloat($('#cantidad_bultos').val()) || 0;
        var pesoBulto = parseFloat($('#peso_por_bulto').val()) || 0;
        
        if (cantidadKg <= 0 && (bultos <= 0 || pesoBulto <= 0)) {
            e.preventDefault();
            showErrorMessage('Debe proporcionar la cantidad en kg o en bultos con peso por bulto.');
            return false;
        }
        
        // Validar que el número de aves sea mayor a 0
        var numeroAves = parseInt($('#numero_aves').val()) || 0;
        if (numeroAves <= 0) {
            e.preventDefault();
            showErrorMessage('Debe seleccionar un galpón válido con aves activas.');
            return false;
        }
    });

    // FUNCIONES AUXILIARES PARA MOSTRAR MENSAJES
    function showInfoMessage(message) {
        showMessage(message, 'info');
    }

    function showWarningMessage(message) {
        showMessage(message, 'warning');
    }

    function showErrorMessage(message) {
        showMessage(message, 'danger');
    }

    function showMessage(message, type) {
        // Remover mensajes anteriores
        $('.alert-message').remove();
        
        // Crear nuevo mensaje
        var alertClass = 'alert-' + type;
        var iconClass = type === 'info' ? 'fa-info-circle' : 
                       type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle';
        
        var alertHtml = `
            <div class="alert ${alertClass} alert-message alert-dismissible fade show" role="alert">
                <i class="fas ${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insertar después del header de la card
        $('.card-header').after(alertHtml);
        
        // Auto-ocultar después de 5 segundos
        setTimeout(function() {
            $('.alert-message').fadeOut();
        }, 5000);
    }

    // Inicializar campos si hay valores old
    if ($('#producto_id').val()) {
        $('#producto_id').trigger('change');
    }
    if ($('#galpon_id').val()) {
        $('#galpon_id').trigger('change');
    }
    
    // FUNCIONES DE PRUEBA PARA DEBUG
    window.testJavaScript = function() {
        console.log('=== PRUEBA DE JAVASCRIPT ===');
        console.log('jQuery cargado:', typeof $ !== 'undefined');
        console.log('Formulario encontrado:', $('#galpon_id').length);
        console.log('Campo galpón valor:', $('#galpon_id').val());
        console.log('Campo aves valor:', $('#numero_aves').val());
        console.log('Eventos registrados en galpón:', $._data($('#galpon_id')[0], 'events'));
        alert('Revisa la consola del navegador para ver los resultados de la prueba');
    };
    
    window.testGalpones = function() {
        console.log('=== PRUEBA DE GALPONES ===');
        $('#galpon_id option').each(function() {
            var option = $(this);
            console.log('Opción:', {
                value: option.val(),
                text: option.text(),
                dataAves: option.data('aves'),
                selected: option.is(':selected')
            });
        });
        alert('Revisa la consola del navegador para ver los galpones disponibles');
    };
    
    window.testGalponChange = function() {
        console.log('=== PRUEBA DE CAMBIO DE GALPÓN ===');
        var galponSelect = $('#galpon_id');
        var options = galponSelect.find('option');
        
        if (options.length > 1) {
            // Seleccionar el segundo galpón (el primero es "Seleccione un galpón")
            var secondOption = options.eq(1);
            var galponId = secondOption.val();
            
            console.log('Seleccionando galpón:', secondOption.text());
            console.log('ID del galpón:', galponId);
            
            // Cambiar el valor del select
            galponSelect.val(galponId);
            
            // Disparar el evento change manualmente
            galponSelect.trigger('change');
            
            console.log('✅ Evento change disparado manualmente');
        } else {
            console.log('❌ No hay galpones disponibles para probar');
        }
    };
});
</script>
@endsection'@; Set-Content -Path "create.blade.php" -Value $content