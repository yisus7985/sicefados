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
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card mb-4 filters-card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <!-- Botones de filtro por tipo de producción -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-outline-primary filter-btn" data-tipo="todos">
                            <i class="fas fa-list me-2"></i>Todos los Registros
                        </button>
                        <button type="button" class="btn btn-outline-primary filter-btn" data-tipo="huevos">
                            <i class="fas fa-egg me-2"></i>Producción de Huevos
                        </button>
                        <button type="button" class="btn btn-outline-success filter-btn" data-tipo="carne">
                            <i class="fas fa-drumstick-bite me-2"></i>Producción de Carne
                        </button>
                    </div>
                </div>
            </div>
            
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
                                {{ $galpon->name }} ({{ $galpon->tipo_display }}) - {{ ucfirst($galpon->status) }}
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
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registros de Producción</h6>
            <div class="d-flex align-items-center gap-2">
                <!-- Botones de acciones masivas -->
                <div class="btn-group bulk-actions" style="display: none;">
                    <button type="button" class="btn btn-success btn-sm" id="bulkActivate">
                        <i class="fas fa-check me-1"></i>Activar
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" id="bulkDeactivate">
                        <i class="fas fa-pause me-1"></i>Desactivar
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="bulkDelete">
                        <i class="fas fa-trash me-1"></i>Eliminar
                    </button>
                </div>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar registros...">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="bulkActionForm" method="POST" action="{{ route('avicontrol.admin.production.bulk-action') }}">
                @csrf
                <input type="hidden" name="action" id="bulkAction">
                <input type="hidden" name="ids" id="bulkIds">
                
                <div class="table-responsive">
                    <table class="table table-bordered" id="productionsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Galpón</th>
                                <th>Cantidad</th>
                                <th>Valor Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
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
                                        <strong class="text-dark">{{ $production->formatted_fecha }}</strong>
                                        @if($production->tipo_produccion === 'huevos')
                                            @if($production->semana_produccion && $production->semana_produccion !== '')
                                                <br><small class="text-dark font-weight-bold">{{ $production->semana_produccion }}</small>
                                            @else
                                                <br><small class="text-muted">Sin semana</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($production->tipo_produccion === 'huevos')
                                            <span class="badge badge-primary text-white">
                                                <i class="fas fa-egg me-1"></i>
                                                <strong>{{ $production->tipo_produccion_display }}</strong>
                                            </span>
                                            @if($production->tipo)
                                                <br><span class="badge badge-info text-white mt-1"><strong>Tipo {{ $production->tipo }}</strong></span>
                                            @endif
                                        @else
                                            <span class="badge badge-success text-white">
                                                <i class="fas fa-drumstick-bite me-1"></i>
                                                <strong>{{ $production->tipo_produccion_display }}</strong>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($production->galpon)
                                            <strong>{{ $production->galpon->name }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <strong>{{ number_format($production->cantidad) }}</strong>
                                            @if($production->peso_total)
                                                <br><small class="text-muted">{{ number_format($production->peso_total, 2) }} kg</small>
                                            @endif
                                        </div>
                                        
                                        <!-- Quality indicators for eggs -->
                                        @if($production->tipo_produccion === 'huevos')
                                            @if($production->mortalidad_aves > 0 || $production->huevos_rotos > 0 || $production->huevos_sucios > 0)
                                                <div class="mt-1">
                                                    @if($production->mortalidad_aves > 0)
                                                        <small class="text-danger"><i class="fas fa-exclamation-triangle"></i> M:{{ $production->mortalidad_aves }}</small>
                                                    @endif
                                                    @if($production->huevos_rotos > 0)
                                                        <small class="text-warning"><i class="fas fa-times-circle"></i> R:{{ $production->huevos_rotos }}</small>
                                                    @endif
                                                    @if($production->huevos_sucios > 0)
                                                        <small class="text-warning"><i class="fas fa-exclamation-circle"></i> S:{{ $production->huevos_sucios }}</small>
                                                    @endif
                                                </div>
                                            @else
                                                <small class="text-success"><i class="fas fa-check-circle"></i> Excelente</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ $production->formatted_valor_total }}</strong>
                                        <br><small class="text-muted">{{ $production->formatted_valor_unidad }}</small>
                                    </td>
                                    <td>
                                        @if($production->estado === 'activo')
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-secondary">Inactivo</span>
                                        @endif
                                        
                                        @if($production->destino)
                                            <br><small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $production->destino }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('avicontrol.admin.production.show', $production->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('avicontrol.admin.production.edit', $production->id) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                           
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-production" 
                                                    data-id="{{ $production->id }}" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
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
                <div class="d-flex justify-content-center mt-3">
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

@push('styles')
<style>
    /* Simple and clean styles similar to inventory */
    .filters-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .filters-card .card-header {
        background: #e9ecef;
        border-bottom: 1px solid #dee2e6;
        border-radius: 10px 10px 0 0;
    }

    .filter-btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        padding: 0.75rem 1.5rem;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .filter-btn.active {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .filter-btn[data-tipo="huevos"].active {
        background: #007bff;
        border-color: #007bff;
        color: white;
    }

    .filter-btn[data-tipo="carne"].active {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }

    .filter-btn[data-tipo="todos"].active {
        background: #6c757d;
        border-color: #6c757d;
        color: white;
    }

    /* Table styles */
    .table th {
        background: #f8f9fa;
        border-top: none;
        font-weight: 600;
        color: #495057;
    }

    .table td {
        vertical-align: middle;
    }

    /* Text colors for better visibility */
    .text-dark {
        color: #212529 !important;
    }

    .table td strong {
        color: #212529;
        font-weight: 600;
    }

    .table td small {
        color: #495057;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.5em 0.75em;
        border-radius: 4px;
    }

    .badge-primary {
        background-color: #007bff !important;
        color: white !important;
    }

    .badge-success {
        background-color: #28a745 !important;
        color: white !important;
    }

    .badge-info {
        background-color: #17a2b8 !important;
        color: white !important;
    }

    .text-white {
        color: white !important;
    }

    .font-weight-bold {
        font-weight: 600 !important;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    /* Counter & Messages */
    #visibleCount {
        border-radius: 10px;
        border: none;
        background: #e3f2fd;
        color: #1565c0;
        font-weight: 500;
    }

    #filterMessage {
        border-radius: 10px;
        border: none;
        font-weight: 500;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group .btn {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality similar to inventory
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.getElementById('productionsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let row of rows) {
            const cells = row.getElementsByTagName('td');
            let found = false;
            
            for (let cell of cells) {
                if (cell.textContent.toLowerCase().includes(searchTerm)) {
                    found = true;
                    break;
                }
            }
            
            row.style.display = found ? '' : 'none';
        }
    });

    // Función para filtrar registros por tipo de producción
    function filterProductionsByType(tipo) {
        const rows = $('#productionsTable tbody tr');
        let visibleCount = 0;
        
        rows.each(function() {
            const row = $(this);
            const tipoProduccion = row.find('td:nth-child(3) .badge').text().toLowerCase();
            
            if (tipo === 'todos') {
                row.show();
                visibleCount++;
            } else if (tipo === 'huevos' && tipoProduccion.includes('huevos')) {
                row.show();
                visibleCount++;
            } else if (tipo === 'carne' && tipoProduccion.includes('carne')) {
                row.show();
                visibleCount++;
            } else {
                row.hide();
            }
        });
        
        // Actualizar contador de registros visibles
        updateVisibleCount(visibleCount, rows.length);
    }

    // Función para actualizar contador de registros visibles
    function updateVisibleCount(visibleRows, totalRows) {
        // Mostrar contador en algún lugar de la interfaz
        if ($('#visibleCount').length === 0) {
            $('#productionsTable').before('<div id="visibleCount" class="alert alert-info mb-3"><i class="fas fa-info-circle me-2"></i>Mostrando <strong>' + visibleRows + '</strong> de <strong>' + totalRows + '</strong> registros</div>');
        } else {
            $('#visibleCount').html('<i class="fas fa-info-circle me-2"></i>Mostrando <strong>' + visibleRows + '</strong> de <strong>' + totalRows + '</strong> registros');
        }
    }

    // Event listeners para los botones de filtro
    $('.filter-btn').on('click', function() {
        const tipo = $(this).data('tipo');
        
        // Actualizar estado activo de los botones
        $('.filter-btn').removeClass('active btn-primary btn-success').addClass('btn-outline-primary btn-outline-success');
        $(this).removeClass('btn-outline-primary btn-outline-success').addClass('active');
        
        if (tipo === 'huevos') {
            $(this).addClass('btn-primary');
        } else if (tipo === 'carne') {
            $(this).addClass('btn-success');
        } else {
            $(this).addClass('btn-primary');
        }
        
        // Aplicar filtro
        filterProductionsByType(tipo);
        
        // Actualizar el select del filtro
        if (tipo === 'todos') {
            $('#tipo_produccion').val('');
        } else {
            $('#tipo_produccion').val(tipo);
        }
        
        // Mostrar mensaje de confirmación
        showFilterMessage(tipo);
    });

    // Función para mostrar mensaje de confirmación del filtro
    function showFilterMessage(tipo) {
        let message = '';
        let alertClass = '';
        
        if (tipo === 'huevos') {
            message = '✅ Filtrado: Mostrando solo registros de Producción de Huevos';
            alertClass = 'alert-primary';
        } else if (tipo === 'carne') {
            message = '✅ Filtrado: Mostrando solo registros de Producción de Carne';
            alertClass = 'alert-success';
        } else {
            message = '✅ Mostrando todos los registros de producción';
            alertClass = 'alert-info';
        }
        
        // Crear o actualizar mensaje
        if ($('#filterMessage').length === 0) {
            $('#productionsCards').before('<div id="filterMessage" class="alert ' + alertClass + ' alert-dismissible fade show mb-3"><i class="fas fa-filter me-2"></i>' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        } else {
            $('#filterMessage').removeClass().addClass('alert ' + alertClass + ' alert-dismissible fade show mb-3').html('<i class="fas fa-filter me-2"></i>' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
        }
        
        // Auto-ocultar mensaje después de 3 segundos
        setTimeout(function() {
            $('#filterMessage').fadeOut('slow');
        }, 3000);
    }

    // Sincronizar el select con los botones
    $('#tipo_produccion').on('change', function() {
        const selectedValue = $(this).val();
        
        if (selectedValue === 'huevos') {
            $('.filter-btn[data-tipo="huevos"]').click();
        } else if (selectedValue === 'carne') {
            $('.filter-btn[data-tipo="carne"]').click();
        } else {
            $('.filter-btn[data-tipo="todos"]').click();
        }
    });

    // Aplicar filtro inicial al cargar la página
    $(document).ready(function() {
        // Por defecto mostrar todos los registros
        $('.filter-btn[data-tipo="todos"]').click();
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
            $('.bulk-actions').show();
        } else {
            $('.bulk-actions').hide();
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
