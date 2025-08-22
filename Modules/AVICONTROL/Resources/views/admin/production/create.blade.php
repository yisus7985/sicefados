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
                    Nuevo Registro de Producción
                </h2>
                <p class="text-muted mb-0">Complete el formulario para registrar la producción de huevos</p>
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
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-egg me-2"></i>Información de Producción
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('avicontrol.admin.production.store') }}" id="productionForm">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Fecha -->
                            <div class="col-md-6">
                                <label for="fecha" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Fecha de Recolección <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                       name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipo de Huevo -->
                            <div class="col-md-6">
                                <label for="tipo" class="form-label">
                                    <i class="fas fa-layer-group me-1"></i>Tipo de Huevo <span class="text-danger">*</span>
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
                                    <i class="fas fa-sort-numeric-up me-1"></i>Cantidad de Huevos <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('cantidad') is-invalid @enderror" 
                                       name="cantidad" id="cantidad" value="{{ old('cantidad') }}" 
                                       min="1" step="1" required>
                                @error('cantidad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Valor por Unidad -->
                            <div class="col-md-6">
                                <label for="valor_unidad" class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>Valor por Unidad <span class="text-danger">*</span>
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
                                    <input type="text" class="form-control" id="valor_total_display" 
                                           value="0" readonly>
                                    <input type="hidden" name="valor_total" id="valor_total">
                                </div>
                                <small class="text-muted">Calculado automáticamente</small>
                            </div>

                            <!-- Destino -->
                            <div class="col-md-6">
                                <label for="destino" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>Destino <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('destino') is-invalid @enderror" 
                                       name="destino" id="destino" value="{{ old('destino', 'Punto venta') }}" 
                                       maxlength="100" required>
                                @error('destino')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Semana de Producción -->
                            <div class="col-md-6">
                                <label for="semana_produccion" class="form-label">
                                    <i class="fas fa-calendar-week me-1"></i>Semana de Producción
                                </label>
                                <select name="semana_produccion" id="semana_produccion" class="form-select">
                                    <option value="">Seleccione la semana</option>
                                    @foreach($semanas as $semana)
                                        <option value="{{ $semana }}" {{ old('semana_produccion') == $semana ? 'selected' : '' }}>
                                            {{ $semana }}
                                        </option>
                                    @endforeach
                                    <option value="nueva" {{ old('semana_produccion') == 'nueva' ? 'selected' : '' }}>
                                        + Nueva Semana
                                    </option>
                                </select>
                            </div>

                            <!-- Nueva Semana (Campo oculto) -->
                            <div class="col-md-6" id="nuevaSemanaField" style="display: none;">
                                <label for="nueva_semana" class="form-label">
                                    <i class="fas fa-plus me-1"></i>Nueva Semana
                                </label>
                                <input type="text" class="form-control" name="nueva_semana" id="nueva_semana" 
                                       placeholder="Ej: Semana 43" maxlength="50">
                            </div>

                            <!-- Observaciones -->
                            <div class="col-md-6">
                                <label for="observaciones" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>Observaciones
                                </label>
                                <input type="text" class="form-control @error('observaciones') is-invalid @enderror" 
                                       name="observaciones" id="observaciones" value="{{ old('observaciones') }}" 
                                       placeholder="Ej: Semana 43" maxlength="500">
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Firma Recibido -->
                            <div class="col-md-6">
                                <label for="firma_recibido" class="form-label">
                                    <i class="fas fa-signature me-1"></i>Firma Recibido <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('firma_recibido') is-invalid @enderror" 
                                       name="firma_recibido" id="firma_recibido" value="{{ old('firma_recibido') }}" 
                                       placeholder="Nombre de quien recibe" maxlength="100" required>
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
                                       name="firma_lider" id="firma_lider" value="{{ old('firma_lider') }}" 
                                       placeholder="Nombre del líder" maxlength="100">
                                @error('firma_lider')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Summary Card -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fas fa-info-circle me-2"></i>Resumen del Registro
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <small class="text-muted">Tipo de Huevo:</small>
                                                <p class="mb-0 fw-bold" id="summaryTipo">-</p>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Cantidad:</small>
                                                <p class="mb-0 fw-bold" id="summaryCantidad">-</p>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Valor Total:</small>
                                                <p class="mb-0 fw-bold text-success" id="summaryValorTotal">-</p>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Fecha:</small>
                                                <p class="mb-0 fw-bold" id="summaryFecha">-</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('avicontrol.admin.production.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info" id="previewBtn">
                                            <i class="fas fa-eye me-2"></i>Vista Previa
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Guardar Registro
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>Vista Previa del Registro
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Información Básica</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Fecha:</strong></td><td id="previewFecha">-</td></tr>
                            <tr><td><strong>Tipo:</strong></td><td id="previewTipo">-</td></tr>
                            <tr><td><strong>Cantidad:</strong></td><td id="previewCantidad">-</td></tr>
                            <tr><td><strong>Valor Unidad:</strong></td><td id="previewValorUnidad">-</td></tr>
                            <tr><td><strong>Valor Total:</strong></td><td id="previewValorTotal">-</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Información Adicional</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Destino:</strong></td><td id="previewDestino">-</td></tr>
                            <tr><td><strong>Observaciones:</strong></td><td id="previewObservaciones">-</td></tr>
                            <tr><td><strong>Firma Recibido:</strong></td><td id="previewFirmaRecibido">-</td></tr>
                            <tr><td><strong>Semana:</strong></td><td id="previewSemana">-</td></tr>
                            <tr><td><strong>Firma Líder:</strong></td><td id="previewFirmaLider">-</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('productionForm').submit()">
                    <i class="fas fa-save me-2"></i>Confirmar y Guardar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize form elements
    initializeForm();
    
    // Event listeners
    $('#cantidad, #valor_unidad').on('input', calculateTotal);
    $('#semana_produccion').on('change', handleSemanaChange);
    $('#previewBtn').on('click', showPreview);
    
    // Form validation
    $('#productionForm').on('submit', validateForm);
});

function initializeForm() {
    // Set default date to today
    if (!$('#fecha').val()) {
        $('#fecha').val(new Date().toISOString().split('T')[0]);
    }
    
    // Calculate initial total
    calculateTotal();
    
    // Update summary
    updateSummary();
}

function calculateTotal() {
    const cantidad = parseInt($('#cantidad').val()) || 0;
    const valorUnidad = parseFloat($('#valor_unidad').val()) || 0;
    const valorTotal = cantidad * valorUnidad;
    
    $('#valor_total').val(valorTotal.toFixed(2));
    $('#valor_total_display').val('$' + valorTotal.toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }));
    
    updateSummary();
}

function handleSemanaChange() {
    const selectedValue = $('#semana_produccion').val();
    
    if (selectedValue === 'nueva') {
        $('#nuevaSemanaField').show();
        $('#nueva_semana').prop('required', true);
    } else {
        $('#nuevaSemanaField').hide();
        $('#nueva_semana').prop('required', false);
        $('#nueva_semana').val('');
    }
}

function updateSummary() {
    const fecha = $('#fecha').val();
    const tipo = $('#tipo').val();
    const cantidad = $('#cantidad').val();
    const valorTotal = $('#valor_total').val();
    
    $('#summaryFecha').text(fecha ? new Date(fecha).toLocaleDateString('es-CO') : '-');
    $('#summaryTipo').text(tipo ? 'Tipo ' + tipo : '-');
    $('#summaryCantidad').text(cantidad ? parseInt(cantidad).toLocaleString('es-CO') : '-');
    $('#summaryValorTotal').text(valorTotal ? '$' + parseFloat(valorTotal).toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }) : '-');
}

function showPreview() {
    // Validate required fields first
    if (!validateRequiredFields()) {
        return;
    }
    
    // Update preview modal with current form data
    $('#previewFecha').text($('#fecha').val() ? new Date($('#fecha').val()).toLocaleDateString('es-CO') : '-');
    $('#previewTipo').text($('#tipo').val() ? 'Tipo ' + $('#tipo').val() : '-');
    $('#previewCantidad').text($('#cantidad').val() ? parseInt($('#cantidad').val()).toLocaleString('es-CO') : '-');
    $('#previewValorUnidad').text($('#valor_unidad').val() ? '$' + parseFloat($('#valor_unidad').val()).toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }) : '-');
    $('#previewValorTotal').text($('#valor_total').val() ? '$' + parseFloat($('#valor_total').val()).toLocaleString('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }) : '-');
    $('#previewDestino').text($('#destino').val() || '-');
    $('#previewObservaciones').text($('#observaciones').val() || '-');
    $('#previewFirmaRecibido').text($('#firma_recibido').val() || '-');
    
    const semana = $('#semana_produccion').val();
    if (semana === 'nueva') {
        $('#previewSemana').text($('#nueva_semana').val() || '-');
    } else {
        $('#previewSemana').text(semana || '-');
    }
    
    $('#previewFirmaLider').text($('#firma_lider').val() || '-');
    
    // Show modal
    $('#previewModal').modal('show');
}

function validateRequiredFields() {
    let isValid = true;
    const requiredFields = ['fecha', 'tipo', 'cantidad', 'valor_unidad', 'destino', 'firma_recibido'];
    
    requiredFields.forEach(field => {
        const element = $('#' + field);
        if (!element.val()) {
            element.addClass('is-invalid');
            isValid = false;
        } else {
            element.removeClass('is-invalid');
        }
    });
    
    // Check if nueva_semana is required
    if ($('#semana_produccion').val() === 'nueva' && !$('#nueva_semana').val()) {
        $('#nueva_semana').addClass('is-invalid');
        isValid = false;
    }
    
    if (!isValid) {
        // Scroll to first error
        $('html, body').animate({
            scrollTop: $('.is-invalid').first().offset().top - 100
        }, 500);
        
        // Show error message
        if (!$('.alert-danger').length) {
            $('.page-header').after(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Por favor complete todos los campos obligatorios marcados con <span class="text-danger">*</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
        }
    }
    
    return isValid;
}

function validateForm(e) {
    if (!validateRequiredFields()) {
        e.preventDefault();
        return false;
    }
    
    // Handle nueva_semana field
    if ($('#semana_produccion').val() === 'nueva' && $('#nueva_semana').val()) {
        $('#semana_produccion').val($('#nueva_semana').val());
    }
    
    return true;
}

// Auto-hide alerts
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);
</script>
@endpush
