@extends('avicontrol::layouts.admin')

@section('title', 'Consumo de Alimento')

@section('content')
<div class="container-fluid">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary">
                        <i class="fas fa-utensils me-2"></i>
                        Consumo de Alimento
                    </h1>
                    <p class="text-muted mb-0">Gestione el consumo de alimento de las aves</p>
                </div>
                <div>
                    <a href="{{ route('avicontrol.admin.food_consumption.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Nuevo Consumo
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2 text-info"></i>
                        Filtros de Búsqueda
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('avicontrol.admin.food_consumption.index') }}" class="mb-3">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="galpon_id" class="form-label fw-semibold">Galpón</label>
                                    <select name="galpon_id" id="galpon_id" class="form-select">
                                        <option value="">Todos los galpones</option>
                                        @foreach($galpones as $galpon)
                                            <option value="{{ $galpon->id }}" {{ request('galpon_id') == $galpon->id ? 'selected' : '' }}>
                                                {{ $galpon->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="producto_id" class="form-label fw-semibold">Producto</label>
                                    <select name="producto_id" id="producto_id" class="form-select">
                                        <option value="">Todos los productos</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}" {{ request('producto_id') == $producto->id ? 'selected' : '' }}>
                                                {{ $producto->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fecha_inicio" class="form-label fw-semibold">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fecha_fin" class="form-label fw-semibold">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-search me-1"></i>
                                            Filtrar
                                        </button>
                                        <a href="{{ route('avicontrol.admin.food_consumption.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i>
                                            Limpiar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de consumos -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2 text-success"></i>
                        Registros de Consumo
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Fecha</th>
                                    <th class="border-0">Galpón</th>
                                    <th class="border-0">Producto</th>
                                    <th class="border-0">Cantidad (kg)</th>
                                    <th class="border-0">Número Aves</th>
                                    <th class="border-0">Consumo/Ave (g)</th>
                                    <th class="border-0">Responsable</th>
                                    <th class="border-0">Estado</th>
                                    <th class="border-0">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($consumos as $consumo)
                                    <tr data-consumo-id="{{ $consumo->id }}">
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                {{ $consumo->fecha_registro->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-warehouse me-1 text-primary"></i>
                                            {{ $consumo->galpon->name ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-box me-1 text-info"></i>
                                            {{ $consumo->producto->name ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-semibold text-success">
                                                {{ number_format($consumo->getTotalConsumoAttribute(), 2) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-info">
                                                {{ number_format($consumo->numero_aves) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-semibold text-warning">
                                                {{ number_format($consumo->getConsumoPorAveAttribute(), 2) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-user me-1 text-secondary"></i>
                                            {{ $consumo->responsable }}
                                        </td>
                                        <td class="align-middle">
                                            @if($consumo->estado === 'active')
                                                <span class="badge bg-success">Activo</span>
                                            @elseif($consumo->estado === 'cancelled')
                                                <span class="badge bg-danger">Cancelado</span>
                                            @else
                                                <span class="badge bg-warning">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('avicontrol.admin.food_consumption.show', $consumo->id) }}" 
                                                   class="btn btn-outline-info" title="Ver Detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($consumo->estado === 'active')
                                                    <a href="{{ route('avicontrol.admin.food_consumption.edit', $consumo->id) }}" 
                                                       class="btn btn-outline-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" title="Eliminar"
                                                            onclick="eliminarConsumo({{ $consumo->id }}, '{{ $consumo->galpon->name ?? 'N/A' }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p class="h5">No hay registros de consumo de alimento</p>
                                                <p class="text-muted">Comience creando un nuevo registro de consumo</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paginación -->
    @if($consumos->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $consumos->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal de Confirmación para Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Confirmar Eliminación Permanente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>¡Atención!</strong> Esta acción eliminará permanentemente el registro.
                </div>
                <p>¿Está seguro de que desea eliminar este registro de consumo?</p>
                <p class="text-muted mb-0">
                    <strong>Galpón:</strong> <span id="galponEliminar"></span><br>
                    <strong>Fecha:</strong> <span id="fechaEliminar"></span><br>
                    <strong>Esta acción no se puede deshacer y el registro desaparecerá completamente.</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <form id="formEliminar" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Mejorar la experiencia de usuario con los filtros
    $('#galpon_id, #producto_id').select2({
        placeholder: 'Seleccione una opción',
        allowClear: true
    });
});

// Función para eliminar consumo
function eliminarConsumo(id, galpon) {
    // Obtener la fecha de la fila correspondiente
    const fila = $(`tr[data-consumo-id="${id}"]`);
    const fecha = fila.find('td:first-child .badge').text();
    
    // Configurar el modal
    $('#galponEliminar').text(galpon);
    $('#fechaEliminar').text(fecha);
    $('#formEliminar').attr('action', '{{ url("avicontrol/admin/food_consumption") }}/' + id);
    
    // Mostrar el modal
    $('#modalEliminar').modal('show');
}

// Manejar el envío del formulario de eliminación
$('#formEliminar').on('submit', function(e) {
    e.preventDefault();
    
    const form = $(this);
    const submitBtn = form.find('button[type="submit"]');
    const originalText = submitBtn.html();
    
    // Cambiar el botón a estado de carga
    submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Eliminando...');
    submitBtn.prop('disabled', true);
    
    // Enviar la petición AJAX
    $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        success: function(response) {
            if (response.success) {
                // Mostrar mensaje de éxito
                mostrarNotificacion(response.message || 'Registro eliminado exitosamente', 'success');
                
                // Cerrar el modal
                $('#modalEliminar').modal('hide');
                
                // Eliminar la fila de la tabla sin recargar la página
                eliminarFilaDeTabla();
                
                // Verificar si no hay más registros
                if ($('tbody tr').length === 0) {
                    mostrarMensajeSinRegistros();
                }
            } else {
                mostrarNotificacion('Error al eliminar el registro', 'error');
            }
            
            // Restaurar el botón
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        },
        error: function(xhr) {
            let mensaje = 'Error al eliminar el registro';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                mensaje = xhr.responseJSON.message;
            }
            
            // Mostrar mensaje de error
            mostrarNotificacion(mensaje, 'error');
            
            // Restaurar el botón
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        }
    });
});

// Función para eliminar la fila de la tabla
function eliminarFilaDeTabla() {
    // Obtener el ID del consumo que se está eliminando
    const actionUrl = $('#formEliminar').attr('action');
    const consumoId = actionUrl.split('/').pop();
    
    // Encontrar y eliminar la fila correspondiente
    $(`tr[data-consumo-id="${consumoId}"]`).fadeOut(400, function() {
        $(this).remove();
    });
}

// Función para mostrar mensaje cuando no hay registros
function mostrarMensajeSinRegistros() {
    const tbody = $('tbody');
    const mensaje = `
        <tr>
            <td colspan="9" class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p class="h5">No hay registros de consumo de alimento</p>
                    <p class="text-muted">Comience creando un nuevo registro de consumo</p>
                </div>
            </td>
        </tr>
    `;
    tbody.html(mensaje);
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo) {
    const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const notificacion = $(`
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas ${iconClass} me-2"></i>
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    $('body').append(notificacion);
    
    // Auto-ocultar después de 5 segundos
    setTimeout(function() {
        notificacion.fadeOut();
    }, 5000);
}
</script>
@endpush
