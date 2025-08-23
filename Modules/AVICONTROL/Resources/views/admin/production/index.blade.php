@extends('avicontrol::layouts.admin')

@section('title', 'Control de Producción Avícola')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-egg text-primary me-3"></i>
                    Control de Producción Avícola
                </h2>
                <p class="text-muted mb-0">Sistema de registro y control de producción de huevos y carne</p>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <a href="{{ route('avicontrol.admin.production.create', ['tipo_produccion' => 'huevos']) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuevo Huevos
                    </a>
                    <a href="{{ route('avicontrol.admin.production.create', ['tipo_produccion' => 'carne']) }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Nuevo Carne
                    </a>
                    <a href="{{ route('avicontrol.admin.production.dashboard') }}" class="btn btn-info">
                        <i class="fas fa-chart-line me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('avicontrol.admin.production.report') }}" class="btn btn-warning">
                        <i class="fas fa-file-pdf me-2"></i>Reporte PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-egg"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['total_huevos'] ?? 0) }}</h3>
                    <p>Total {{ request('tipo_produccion') === 'carne' ? 'Kg' : 'Unidades' }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3>${{ number_format($stats['valor_total'] ?? 0, 0, ',', '.') }}</h3>
                    <p>Valor Total</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['promedio_por_dia'] ?? 0 }}</h3>
                    <p>Días Registrados</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ count($stats['tipos_disponibles'] ?? []) }}</h3>
                    <p>Tipos de {{ request('tipo_produccion') === 'carne' ? 'Carne' : 'Huevo' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('avicontrol.admin.production.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label for="tipo_produccion" class="form-label">Tipo de Producción</label>
                    <select name="tipo_produccion" id="tipo_produccion" class="form-select">
                        <option value="">Todos los tipos</option>
                        <option value="huevos" {{ request('tipo_produccion') == 'huevos' ? 'selected' : '' }}>Huevos</option>
                        <option value="carne" {{ request('tipo_produccion') == 'carne' ? 'selected' : '' }}>Carne</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="galpon_id" class="form-label">Galpón</label>
                    <select name="galpon_id" id="galpon_id" class="form-select">
                        <option value="">Todos los galpones</option>
                        @foreach($galpones as $galpon)
                            <option value="{{ $galpon->id }}" {{ request('galpon_id') == $galpon->id ? 'selected' : '' }}>
                                {{ $galpon->name }} ({{ $galpon->tipo_display }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="">Todos los tipos</option>
                        <option value="A" {{ request('tipo') == 'A' ? 'selected' : '' }}>Tipo A</option>
                        <option value="AA" {{ request('tipo') == 'AA' ? 'selected' : '' }}>Tipo AA</option>
                        <option value="B" {{ request('tipo') == 'B' ? 'selected' : '' }}>Tipo B</option>
                        <option value="C" {{ request('tipo') == 'C' ? 'selected' : '' }}>Tipo C</option>
                        <option value="D" {{ request('tipo') == 'D' ? 'selected' : '' }}>Tipo D</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="semana_produccion" class="form-label">Semana</label>
                    <select name="semana_produccion" id="semana_produccion" class="form-select">
                        <option value="">Todas las semanas</option>
                        @foreach($semanas as $semana)
                            <option value="{{ $semana }}" {{ request('semana_produccion') == $semana ? 'selected' : '' }}>
                                {{ $semana }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="fecha_desde" class="form-label">Fecha Desde</label>
                    <input type="date" class="form-control datepicker" name="fecha_desde" 
                           value="{{ request('fecha_desde') }}" id="fecha_desde">
                </div>
                <div class="col-md-2">
                    <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                    <input type="date" class="form-control datepicker" name="fecha_hasta" 
                           value="{{ request('fecha_hasta') }}" id="fecha_hasta">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100" role="group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filtrar
                        </button>
                        <a href="{{ route('avicontrol.admin.production.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Production Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>Registros de Producción
            </h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-warning btn-sm" id="bulkActivate" style="display: none;">
                    <i class="fas fa-check me-1"></i>Activar Seleccionados
                </button>
                <button type="button" class="btn btn-secondary btn-sm" id="bulkDeactivate" style="display: none;">
                    <i class="fas fa-pause me-1"></i>Desactivar Seleccionados
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="bulkDelete" style="display: none;">
                    <i class="fas fa-trash me-1"></i>Eliminar Seleccionados
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="bulkActionForm" method="POST" action="{{ route('avicontrol.admin.production.bulk-action') }}">
                @csrf
                <input type="hidden" name="action" id="bulkAction">
                <input type="hidden" name="ids" id="bulkIds">
                
                <div class="table-responsive">
                    <table class="table table-hover datatable" id="productionsTable">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>FECHA</th>
                                <th>TIPO PRODUCCIÓN</th>
                                <th>GALPÓN</th>
                                <th>TIPO</th>
                                <th>CANTIDAD</th>
                                <th>MORTALIDAD</th>
                                <th>PESO PROMEDIO</th>
                                <th>PESO TOTAL</th>
                                <th>HUEVOS ROTOS</th>
                                <th>HUEVOS SUCIOS</th>
                                <th>VALOR UNIDAD</th>
                                <th>VALOR TOTAL</th>
                                <th>DESTINO</th>
                                <th>OBSERVACIONES</th>
                                <th>FIRMA RECIBIDO</th>
                                <th>SEMANA</th>
                                <th>ESTADO</th>
                                <th width="150">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productions as $production)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_ids[]" value="{{ $production->id }}" 
                                               class="form-check-input production-checkbox">
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $production->formatted_fecha }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $production->tipo_produccion === 'huevos' ? 'primary' : 'success' }}">
                                            {{ $production->tipo_produccion_display }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($production->galpon)
                                            <span class="badge bg-info">{{ $production->galpon->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $production->tipo }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($production->cantidad) }}</strong>
                                    </td>
                                    <td>
                                        @if($production->mortalidad_aves > 0)
                                            <span class="badge bg-danger">{{ number_format($production->mortalidad_aves) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $production->formatted_peso_promedio }}</td>
                                    <td>{{ $production->formatted_peso_total }}</td>
                                    <td>
                                        @if($production->tipo_produccion === 'huevos')
                                            <span class="badge bg-danger">{{ $production->huevos_rotos }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($production->tipo_produccion === 'huevos')
                                            <span class="badge bg-warning">{{ $production->huevos_sucios }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $production->formatted_valor_unidad }}</td>
                                    <td>
                                        <strong class="text-success">{{ $production->formatted_valor_total }}</strong>
                                    </td>
                                    <td>{{ $production->destino }}</td>
                                    <td>
                                        @if($production->observaciones)
                                            <span class="text-muted">{{ $production->observaciones }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $production->firma_recibido }}</td>
                                    <td>
                                        @if($production->semana_produccion)
                                            <span class="badge bg-info">{{ $production->semana_produccion }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($production->estado === 'activo')
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('avicontrol.admin.production.show', $production->id) }}" 
                                               class="btn btn-outline-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('avicontrol.admin.production.edit', $production->id) }}" 
                                               class="btn btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-secondary toggle-status" 
                                                    data-id="{{ $production->id }}" 
                                                    data-status="{{ $production->estado }}"
                                                    title="{{ $production->estado === 'activo' ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas fa-{{ $production->estado === 'activo' ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger delete-production" 
                                                    data-id="{{ $production->id }}" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="18" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No hay registros de producción disponibles</p>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('avicontrol.admin.production.create', ['tipo_produccion' => 'huevos']) }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>Crear Registro Huevos
                                                </a>
                                                <a href="{{ route('avicontrol.admin.production.create', ['tipo_produccion' => 'carne']) }}" class="btn btn-success">
                                                    <i class="fas fa-plus me-2"></i>Crear Registro Carne
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>

            <!-- Pagination -->
            @if($productions->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $productions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este registro de producción?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Confirmation Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Acción Masiva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="bulkActionMessage"></p>
                <p class="text-warning"><small>Esta acción se aplicará a todos los registros seleccionados.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmBulkAction">Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#productionsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true,
        pageLength: 25,
        order: [[1, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 17] }
        ]
    });

    // Select All functionality
    $('#selectAll').change(function() {
        $('.production-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkActionButtons();
    });

    // Individual checkbox change
    $(document).on('change', '.production-checkbox', function() {
        updateBulkActionButtons();
        
        // Update select all checkbox
        const totalCheckboxes = $('.production-checkbox').length;
        const checkedCheckboxes = $('.production-checkbox:checked').length;
        
        if (checkedCheckboxes === 0) {
            $('#selectAll').prop('indeterminate', false).prop('checked', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            $('#selectAll').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#selectAll').prop('indeterminate', true);
        }
    });

    // Update bulk action buttons visibility
    function updateBulkActionButtons() {
        const checkedCount = $('.production-checkbox:checked').length;
        
        if (checkedCount > 0) {
            $('#bulkActivate, #bulkDeactivate, #bulkDelete').show();
        } else {
            $('#bulkActivate, #bulkDeactivate, #bulkDelete').hide();
        }
    }

    // Bulk Actions
    $('#bulkActivate').click(function() {
        showBulkActionModal('activate', '¿Está seguro de que desea activar los registros seleccionados?');
    });

    $('#bulkDeactivate').click(function() {
        showBulkActionModal('deactivate', '¿Está seguro de que desea desactivar los registros seleccionados?');
    });

    $('#bulkDelete').click(function() {
        showBulkActionModal('delete', '¿Está seguro de que desea eliminar los registros seleccionados?');
    });

    function showBulkActionModal(action, message) {
        $('#bulkAction').val(action);
        $('#bulkActionMessage').text(message);
        $('#bulkActionModal').modal('show');
    }

    $('#confirmBulkAction').click(function() {
        const action = $('#bulkAction').val();
        const ids = $('.production-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        $('#bulkIds').val(ids.join(','));
        $('#bulkActionForm').submit();
    });

    // Toggle Status
    $('.toggle-status').click(function() {
        const id = $(this).data('id');
        const currentStatus = $(this).data('status');
        const newStatus = currentStatus === 'activo' ? 'inactivo' : 'activo';
        const statusText = newStatus === 'activo' ? 'activar' : 'desactivar';

        if (confirm(`¿Está seguro de que desea ${statusText} este registro?`)) {
            window.location.href = `{{ url('avicontrol/admin/production') }}/${id}/toggle-status`;
        }
    });

    // Delete Production
    $('.delete-production').click(function() {
        const id = $(this).data('id');
        $('#deleteForm').attr('action', `{{ url('avicontrol/admin/production') }}/${id}`);
        $('#deleteModal').modal('show');
    });

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush
