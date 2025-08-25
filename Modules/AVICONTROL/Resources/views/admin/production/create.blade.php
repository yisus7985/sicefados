@extends('avicontrol::layouts.admin')

@section('title', 'Nuevo Registro de Producción')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                                        <option value="{{ $galpon->id }}" {{ old('galpon_id', $galpones->first()->id ?? '') == $galpon->id ? 'selected' : '' }}>
                                            {{ $galpon->name }} ({{ $galpon->tipo_display }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('galpon_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipo (Solo para huevos) -->
                            @if($tipoProduccion === 'huevos')
                                <div class="col-md-6">
                                    <label for="tipo" class="form-label">
                                        <i class="fas fa-layer-group me-1"></i>Tipo de Huevo <span class="text-danger">*</span>
                                    </label>
                                    <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                        <option value="">Seleccione el tipo</option>
                                        @foreach($tipos as $tipo)
                                            <option value="{{ $tipo }}" {{ old('tipo', 'A') == $tipo ? 'selected' : '' }}>
                                                Tipo {{ $tipo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Cantidad -->
                            <div class="col-md-6">
                                <label for="cantidad" class="form-label">
                                    <i class="fas fa-sort-numeric-up me-1"></i>Cantidad <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('cantidad') is-invalid @enderror" 
                                       name="cantidad" id="cantidad" value="{{ old('cantidad', '1') }}" 
                                       min="1" step="1" required>
                                <small class="form-text text-muted">
                                    {{ $tipoProduccion === 'huevos' ? 'Número de huevos' : 'Número de aves' }}
                                </small>
                                @error('cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mortalidad de Aves (Solo para huevos) -->
                            @if($tipoProduccion === 'huevos')
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

                            <!-- Campos específicos para carne -->
                            @if($tipoProduccion === 'carne')
                                <!-- Peso Promedio por Ave -->
                                <div class="col-md-6">
                                    <label for="peso_promedio" class="form-label">
                                        <i class="fas fa-weight-hanging me-1"></i>Peso Promedio por Ave (kg)
                                    </label>
                                    <input type="number" class="form-control @error('peso_promedio') is-invalid @enderror" 
                                           name="peso_promedio" id="peso_promedio" value="{{ old('peso_promedio') }}" 
                                           min="0" step="0.01">
                                    <small class="form-text text-muted">Peso promedio de cada ave sacrificada</small>
                                    @error('peso_promedio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Peso Total -->
                                <div class="col-md-6">
                                    <label for="peso_total" class="form-label">
                                        <i class="fas fa-weight me-1"></i>Peso Total (kg)
                                    </label>
                                    <input type="number" class="form-control" id="peso_total_display" readonly>
                                    <input type="hidden" name="peso_total" id="peso_total">
                                    <small class="form-text text-muted">Se calcula automáticamente</small>
                                </div>

                                <!-- Fecha de Sacrificio -->
                                <div class="col-md-6">
                                    <label for="fecha_sacrificio" class="form-label">
                                        <i class="fas fa-calendar-day me-1"></i>Fecha de Sacrificio
                                    </label>
                                    <input type="date" class="form-control @error('fecha_sacrificio') is-invalid @enderror" 
                                           name="fecha_sacrificio" id="fecha_sacrificio" value="{{ old('fecha_sacrificio') }}">
                                    <small class="form-text text-muted">Fecha cuando se realizó el sacrificio</small>
                                    @error('fecha_sacrificio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Responsable de Sacrificio -->
                                <div class="col-md-6">
                                    <label for="responsable_sacrificio" class="form-label">
                                        <i class="fas fa-user-shield me-1"></i>Responsable de Sacrificio
                                    </label>
                                    <input type="text" class="form-control @error('responsable_sacrificio') is-invalid @enderror" 
                                           name="responsable_sacrificio" id="responsable_sacrificio" value="{{ old('responsable_sacrificio') }}">
                                    <small class="form-text text-muted">Persona responsable del proceso de sacrificio</small>
                                    @error('responsable_sacrificio')
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
                                           name="valor_unidad" id="valor_unidad" value="{{ old('valor_unidad', '0.00') }}" 
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

                            <!-- Semana de Producción (Solo para huevos) -->
                            @if($tipoProduccion === 'huevos')
                                <div class="col-md-6">
                                    <label for="semana_produccion" class="form-label">
                                        <i class="fas fa-calendar-week me-1"></i>Semana de Producción
                                    </label>
                                    <div class="input-group">
                                        <select name="semana_produccion" id="semana_produccion" class="form-select @error('semana_produccion') is-invalid @enderror" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                            <option value="">Seleccione la semana</option>
                                            @foreach($semanas as $semana)
                                                <option value="{{ $semana }}" {{ old('semana_produccion') == $semana ? 'selected' : '' }}>
                                                    {{ $semana }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" id="btnNuevaSemana" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    @error('semana_produccion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Haga clic en el botón + para crear una nueva semana</small>
                                </div>
                            @endif

                            <!-- Firma Recibido -->
                            <div class="col-md-6">
                                <label for="firma_recibido" class="form-label">
                                    <i class="fas fa-user me-1"></i>Firma Recibido <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('firma_recibido') is-invalid @enderror" 
                                       name="firma_recibido" id="firma_recibido" value="{{ old('firma_recibido', 'Responsable') }}" required>
                                @error('firma_recibido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Firma Líder (Solo para huevos) -->
                            @if($tipoProduccion === 'huevos')
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
                            @endif

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

<!-- Modal para crear nueva semana de producción -->
<div class="modal fade" id="modalNuevaSemana" tabindex="-1" aria-labelledby="modalNuevaSemanaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaSemanaLabel">
                    <i class="fas fa-calendar-plus me-2"></i>Crear Nueva Semana de Producción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formNuevaSemana">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nueva_semana" class="form-label">
                            <i class="fas fa-calendar-week me-1"></i>Nombre de la Semana <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nueva_semana" name="nueva_semana" 
                               placeholder="Ej: Semana 1 - Enero 2025" required>
                        <small class="form-text text-muted">Ingrese un nombre descriptivo para la semana de producción</small>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_inicio_semana" class="form-label">
                            <i class="fas fa-calendar me-1"></i>Fecha de Inicio <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="fecha_inicio_semana" name="fecha_inicio_semana" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_fin_semana" class="form-label">
                            <i class="fas fa-calendar me-1"></i>Fecha de Fin <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="fecha_fin_semana" name="fecha_fin_semana" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_semana" class="form-label">
                            <i class="fas fa-comment me-1"></i>Descripción
                        </label>
                        <textarea class="form-control" id="descripcion_semana" name="descripcion_semana" 
                                  rows="2" placeholder="Descripción opcional de la semana"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarSemana">
                        <i class="fas fa-save me-2"></i>Crear Semana
                    </button>
                </div>
            </form>
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
    @if($tipoProduccion === 'carne')
    function calcularPesoTotal() {
        const cantidad = parseFloat($('#cantidad').val()) || 0;
        const pesoPromedio = parseFloat($('#peso_promedio').val()) || 0;
        const pesoTotal = cantidad * pesoPromedio;
        
        $('#peso_total_display').val(pesoTotal.toFixed(2) + ' kg');
        $('#peso_total').val(pesoTotal);
    }
    @endif



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
        console.log('=== VALIDACIÓN DEL FORMULARIO ===');
        console.log('Formulario enviándose...');
        console.log('Datos del formulario:', $(this).serialize());
        
        // Verificar campos requeridos
        const requiredFields = ['fecha', 'galpon_id', 'cantidad', 'valor_unidad', 'destino', 'firma_recibido'];
        let isValid = true;
        
        requiredFields.forEach(function(field) {
            const value = $('#' + field).val();
            console.log('Campo ' + field + ':', value);
            if (!value || value.trim() === '') {
                console.error('Campo requerido vacío:', field);
                isValid = false;
                $('#' + field).addClass('is-invalid');
            } else {
                $('#' + field).removeClass('is-invalid');
            }
        });
        
        // Validación específica para huevos
        @if($tipoProduccion === 'huevos')
            const tipo = $('#tipo').val();
            console.log('Campo tipo:', tipo);
            if (!tipo || tipo.trim() === '') {
                console.error('Campo tipo requerido para huevos');
                isValid = false;
                $('#tipo').addClass('is-invalid');
            } else {
                $('#tipo').removeClass('is-invalid');
            }
            
            const cantidad = parseInt($('#cantidad').val()) || 0;
            const huevosRotos = parseInt($('#huevos_rotos').val()) || 0;
            const huevosSucios = parseInt($('#huevos_sucios').val()) || 0;

            if (huevosRotos + huevosSucios > cantidad) {
                e.preventDefault();
                alert('La suma de huevos rotos y sucios no puede ser mayor que la cantidad total.');
                return false;
            }
        @endif
        
        if (!isValid) {
            e.preventDefault();
            console.error('Formulario inválido. No se puede enviar.');
            alert('Por favor complete todos los campos requeridos.');
            return false;
        }

        console.log('Formulario válido, enviando...');
        console.log('=== FIN VALIDACIÓN ===');
        // NO hacer preventDefault aquí - permitir envío normal
        return true;
    });

    // Funcionalidad para crear nueva semana de producción
    $('#btnNuevaSemana').on('click', function() {
        // Establecer fecha de inicio como hoy
        const today = new Date().toISOString().split('T')[0];
        $('#fecha_inicio_semana').val(today);
        
        // Calcular fecha de fin (7 días después)
        const endDate = new Date();
        endDate.setDate(endDate.getDate() + 6);
        $('#fecha_fin_semana').val(endDate.toISOString().split('T')[0]);
        
        // Limpiar formulario
        $('#formNuevaSemana')[0].reset();
        $('#nueva_semana').val('');
        $('#descripcion_semana').val('');
        
        // Mostrar modal
        $('#modalNuevaSemana').modal('show');
    });

    // Manejar envío del formulario de nueva semana
    $('#formNuevaSemana').on('submit', function(e) {
        e.preventDefault();
        
        const btnGuardar = $('#btnGuardarSemana');
        const btnOriginalText = btnGuardar.html();
        
        // Deshabilitar botón y mostrar loading
        btnGuardar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Creando...');
        
        // Validar fechas
        const fechaInicio = new Date($('#fecha_inicio_semana').val());
        const fechaFin = new Date($('#fecha_fin_semana').val());
        
        if (fechaInicio >= fechaFin) {
            alert('La fecha de inicio debe ser anterior a la fecha de fin.');
            btnGuardar.prop('disabled', false).html(btnOriginalText);
            return;
        }
        
        // Crear objeto de datos
        const semanaData = {
            nombre: $('#nueva_semana').val(),
            fecha_inicio: $('#fecha_inicio_semana').val(),
            fecha_fin: $('#fecha_fin_semana').val(),
            descripcion: $('#descripcion_semana').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        // Enviar petición AJAX
        $.ajax({
            url: '{{ route("avicontrol.admin.production.store-week") }}',
            method: 'POST',
            data: semanaData,
            success: function(response) {
                if (response.success) {
                    // Agregar nueva opción al select
                    const newOption = new Option(response.semana.nombre, response.semana.nombre, true, true);
                    $('#semana_produccion').append(newOption);
                    
                    // Cerrar modal
                    $('#modalNuevaSemana').modal('hide');
                    
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Semana de producción creada correctamente',
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    alert('Error al crear la semana: ' + response.message);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Error al crear la semana de producción';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                alert(errorMessage);
            },
            complete: function() {
                // Restaurar botón
                btnGuardar.prop('disabled', false).html(btnOriginalText);
            }
        });
    });

    // Inicializar cálculos
    calcularValorTotal();
    @if($tipoProduccion === 'carne')
        calcularPesoTotal();
    @endif
});
</script>
@endpush
