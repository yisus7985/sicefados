@extends('avicontrol::layouts.admin')

@section('title', 'Editar Producción')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-edit text-primary"></i> Editar Registro de Producción
            </h1>
            <p class="text-muted mb-0">Modifique la información del registro de producción</p>
        </div>
        <div>
            <a href="{{ route('avicontrol.admin.production.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
        </div>
    </div>

    <!-- Formulario de Edición -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-egg text-primary"></i> Información de Producción
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('avicontrol.admin.production.update', $production->id) }}" method="POST" id="editProductionForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Fecha de Recolección -->
                            <div class="col-md-6 mb-3">
                                <label for="fecha" class="form-label">
                                    <i class="fas fa-calendar text-success"></i> Fecha de Recolección <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha" id="fecha" 
                                       class="form-control @error('fecha') is-invalid @enderror" 
                                       value="{{ old('fecha', $production->fecha ? $production->fecha->format('Y-m-d') : '') }}" 
                                       max="{{ date('Y-m-d') }}" required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipo de Huevo -->
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">
                                    <i class="fas fa-egg text-warning"></i> Tipo de Huevo <span class="text-danger">*</span>
                                </label>
                                <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                    <option value="">Seleccione el tipo</option>
                                    @foreach($tipos as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo', $production->tipo) == $tipo ? 'selected' : '' }}>
                                            Tipo {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Cantidad de Huevos -->
                            <div class="col-md-6 mb-3">
                                <label for="cantidad" class="form-label">
                                    <i class="fas fa-egg text-success"></i> Cantidad de Huevos <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="cantidad" id="cantidad" 
                                       class="form-control @error('cantidad') is-invalid @enderror" 
                                       value="{{ old('cantidad', $production->cantidad) }}" 
                                       min="1" step="1" required>
                                @error('cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Valor por Unidad -->
                            <div class="col-md-6 mb-3">
                                <label for="valor_unidad" class="form-label">
                                    <i class="fas fa-dollar-sign text-success"></i> Valor por Unidad <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="valor_unidad" id="valor_unidad" 
                                           class="form-control @error('valor_unidad') is-invalid @enderror" 
                                           value="{{ old('valor_unidad', $production->valor_unidad) }}" 
                                           min="0" step="0.01" required>
                                </div>
                                @error('valor_unidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Valor Total (Calculado) -->
                            <div class="col-md-6 mb-3">
                                <label for="valor_total" class="form-label">
                                    <i class="fas fa-calculator text-info"></i> Valor Total
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="text" id="valor_total_display" class="form-control" readonly>
                                    <input type="hidden" name="valor_total" id="valor_total">
                                </div>
                                <small class="text-muted">Se calcula automáticamente</small>
                            </div>

                            <!-- Destino -->
                            <div class="col-md-6 mb-3">
                                <label for="destino" class="form-label">
                                    <i class="fas fa-map-marker-alt text-danger"></i> Destino <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="destino" id="destino" 
                                       class="form-control @error('destino') is-invalid @enderror" 
                                       value="{{ old('destino', $production->destino) }}" 
                                       placeholder="Ej: Punto venta, Distribuidor, etc." required>
                                @error('destino')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Semana de Producción -->
                            <div class="col-md-6 mb-3">
                                <label for="semana_produccion" class="form-label">
                                    <i class="fas fa-calendar-week text-primary"></i> Semana de Producción
                                </label>
                                <select name="semana_produccion" id="semana_produccion" class="form-select @error('semana_produccion') is-invalid @enderror">
                                    <option value="">Seleccione una semana</option>
                                    @foreach($semanas as $semana)
                                        <option value="{{ $semana }}" {{ old('semana_produccion', $production->semana_produccion) == $semana ? 'selected' : '' }}>
                                            {{ $semana }}
                                        </option>
                                    @endforeach
                                    <option value="nueva_semana" {{ old('semana_produccion') == 'nueva_semana' ? 'selected' : '' }}>Nueva Semana</option>
                                </select>
                                @error('semana_produccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nueva Semana (Campo dinámico) -->
                            <div class="col-md-6 mb-3" id="nueva_semana_container" style="display: none;">
                                <label for="nueva_semana_input" class="form-label">
                                    <i class="fas fa-plus text-success"></i> Nueva Semana
                                </label>
                                <input type="text" name="nueva_semana_input" id="nueva_semana_input" 
                                       class="form-control" placeholder="Ej: Semana 45">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Firma Recibido -->
                            <div class="col-md-6 mb-3">
                                <label for="firma_recibido" class="form-label">
                                    <i class="fas fa-user-check text-success"></i> Firma Recibido <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="firma_recibido" id="firma_recibido" 
                                       class="form-control @error('firma_recibido') is-invalid @enderror" 
                                       value="{{ old('firma_recibido', $production->firma_recibido) }}" 
                                       placeholder="Nombre de quien recibe" required>
                                @error('firma_recibido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Firma Líder -->
                            <div class="col-md-6 mb-3">
                                <label for="firma_lider" class="form-label">
                                    <i class="fas fa-user-tie text-primary"></i> Firma Líder
                                </label>
                                <input type="text" name="firma_lider" id="firma_lider" 
                                       class="form-control @error('firma_lider') is-invalid @enderror" 
                                       value="{{ old('firma_lider', $production->firma_lider) }}" 
                                       placeholder="Nombre del líder">
                                @error('firma_lider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">
                                <i class="fas fa-comment text-muted"></i> Observaciones
                            </label>
                            <textarea name="observaciones" id="observaciones" rows="3" 
                                      class="form-control @error('observaciones') is-invalid @enderror" 
                                      placeholder="Observaciones adicionales...">{{ old('observaciones', $production->observaciones) }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('avicontrol.admin.production.show', $production->id) }}" class="btn btn-secondary">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </a>
                            <div>
                                <button type="button" class="btn btn-info me-2" id="btnVistaPrevia">
                                    <i class="fas fa-eye"></i> Vista Previa
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Actualizar Registro
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar con Resumen -->
        <div class="col-lg-4">
            <!-- Resumen del Registro -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle text-info"></i> Resumen del Registro
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ID del Registro:</label>
                        <p class="mb-0">
                            <code class="bg-light px-2 py-1 rounded">{{ $production->id }}</code>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estado Actual:</label>
                        <p class="mb-0">
                            @if($production->estado == 'activo')
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Creado:</label>
                        <p class="mb-0">
                            <i class="fas fa-clock text-muted"></i>
                            {{ $production->created_at ? $production->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Última Actualización:</label>
                        <p class="mb-0">
                            <i class="fas fa-edit text-muted"></i>
                            {{ $production->updated_at ? $production->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                    </div>

                    <hr class="my-3">

                    <!-- Información Histórica -->
                    @if($production->egg_type_a || $production->egg_type_aa || $production->egg_type_b || $production->egg_type_c || $production->egg_type_d)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Información Histórica:</label>
                        <div class="row text-center">
                            <div class="col-4">
                                <small class="text-muted">Tipo A</small>
                                <div class="fw-bold text-success">{{ $production->egg_type_a ?? 0 }}</div>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Tipo AA</small>
                                <div class="fw-bold text-primary">{{ $production->egg_type_aa ?? 0 }}</div>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Tipo B</small>
                                <div class="fw-bold text-info">{{ $production->egg_type_b ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="row text-center mt-2">
                            <div class="col-4">
                                <small class="text-muted">Tipo C</small>
                                <div class="fw-bold text-warning">{{ $production->egg_type_c ?? 0 }}</div>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Tipo D</small>
                                <div class="fw-bold text-secondary">{{ $production->egg_type_d ?? 0 }}</div>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Total</small>
                                <div class="fw-bold text-success">{{ $production->egg_count_total ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Vista Previa -->
<div class="modal fade" id="vistaPreviaModal" tabindex="-1" aria-labelledby="vistaPreviaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vistaPreviaModalLabel">
                    <i class="fas fa-eye text-primary"></i> Vista Previa del Registro
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Fecha:</strong> <span id="preview_fecha"></span></p>
                        <p><strong>Tipo:</strong> <span id="preview_tipo"></span></p>
                        <p><strong>Cantidad:</strong> <span id="preview_cantidad"></span></p>
                        <p><strong>Valor por Unidad:</strong> <span id="preview_valor_unidad"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Valor Total:</strong> <span id="preview_valor_total"></span></p>
                        <p><strong>Destino:</strong> <span id="preview_destino"></span></p>
                        <p><strong>Semana:</strong> <span id="preview_semana"></span></p>
                        <p><strong>Recibido por:</strong> <span id="preview_firma_recibido"></span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p><strong>Observaciones:</strong> <span id="preview_observaciones"></span></p>
                        <p><strong>Líder:</strong> <span id="preview_firma_lider"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" form="editProductionForm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Confirmar Cambios
                </button>
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
        
        $('#valor_total_display').val('$' + valorTotal.toLocaleString('es-CO'));
        $('#valor_total').val(valorTotal);
    }

    // Eventos para calcular valor total
    $('#cantidad, #valor_unidad').on('input', calcularValorTotal);

    // Mostrar/ocultar campo de nueva semana
    $('#semana_produccion').on('change', function() {
        if ($(this).val() === 'nueva_semana') {
            $('#nueva_semana_container').show();
            $('#nueva_semana_input').prop('required', true);
        } else {
            $('#nueva_semana_container').hide();
            $('#nueva_semana_input').prop('required', false);
        }
    });

    // Vista previa
    $('#btnVistaPrevia').on('click', function() {
        // Obtener valores del formulario
        const fecha = $('#fecha').val();
        const tipo = $('#tipo option:selected').text();
        const cantidad = $('#cantidad').val();
        const valorUnidad = $('#valor_unidad').val();
        const valorTotal = $('#valor_total').val();
        const destino = $('#destino').val();
        const semana = $('#semana_produccion').val() === 'nueva_semana' ? $('#nueva_semana_input').val() : $('#semana_produccion option:selected').text();
        const firmaRecibido = $('#firma_recibido').val();
        const observaciones = $('#observaciones').val();
        const firmaLider = $('#firma_lider').val();

        // Actualizar modal
        $('#preview_fecha').text(fecha ? new Date(fecha).toLocaleDateString('es-CO') : 'No especificada');
        $('#preview_tipo').text(tipo || 'No especificado');
        $('#preview_cantidad').text(cantidad ? cantidad + ' huevos' : 'No especificada');
        $('#preview_valor_unidad').text(valorUnidad ? '$' + parseFloat(valorUnidad).toLocaleString('es-CO') : 'No especificado');
        $('#preview_valor_total').text(valorTotal ? '$' + parseFloat(valorTotal).toLocaleString('es-CO') : 'No especificado');
        $('#preview_destino').text(destino || 'No especificado');
        $('#preview_semana').text(semana || 'No especificada');
        $('#preview_firma_recibido').text(firmaRecibido || 'No especificado');
        $('#preview_observaciones').text(observaciones || 'Sin observaciones');
        $('#preview_firma_lider').text(firmaLider || 'No especificado');

        // Mostrar modal
        $('#vistaPreviaModal').modal('show');
    });

    // Calcular valor total al cargar la página
    calcularValorTotal();
});
</script>
@endpush
