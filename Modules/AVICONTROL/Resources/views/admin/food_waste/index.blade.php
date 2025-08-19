@extends('avicontrol::layouts.admin')

@section('title', 'Control de Mermas de Alimento')

@section('content')
<div class="container-fluid">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Control de Mermas de Alimento
                    </h1>
                    <p class="text-muted mb-0">Monitoree y controle las pérdidas de alimento</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('avicontrol.admin.food_waste.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nuevo Registro
                    </a>
                    <a href="{{ route('avicontrol.admin.food_waste.reporte') }}" class="btn btn-info">
                        <i class="fas fa-chart-bar me-1"></i> Reporte
                    </a>
                    <a href="{{ route('avicontrol.admin.food_waste.estadisticas_directo') }}" class="btn btn-success" target="_blank">
                        <i class="fas fa-chart-pie me-1"></i> Estadísticas
                    </a>
                    <a href="{{ route('avicontrol.admin.food_waste.dashboard') }}" class="btn btn-warning">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Resumen de mermas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-0">Total Mermas</h6>
                            <h4 class="mb-0">{{ number_format($totalMermas ?? 0, 2) }} kg</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-0">Costo Total</h6>
                            <h4 class="mb-0">${{ number_format($costoTotal ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-percentage fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-0">% Merma</h6>
                            <h4 class="mb-0">{{ number_format($porcentajeMerma ?? 0, 2) }}%</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-list fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-0">Registros</h6>
                            <h4 class="mb-0">{{ $totalRegistros ?? 0 }}</h4>
                        </div>
                    </div>
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
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_galpon" class="form-label fw-semibold">Galpón:</label>
                            <select id="filter_galpon" class="form-select">
                                <option value="">Todos los galpones</option>
                                @foreach($galpones ?? [] as $galpon)
                                    <option value="{{ $galpon->id }}">{{ $galpon->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_producto" class="form-label fw-semibold">Producto:</label>
                            <select id="filter_producto" class="form-select">
                                <option value="">Todos los productos</option>
                                @foreach($productos ?? [] as $producto)
                                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_causa" class="form-label fw-semibold">Causa de Merma:</label>
                            <select id="filter_causa" class="form-select">
                                <option value="">Todas las causas</option>
                                <option value="derrame">Derrame</option>
                                <option value="contaminacion">Contaminación</option>
                                <option value="roedores">Roedores</option>
                                <option value="humedad">Humedad</option>
                                <option value="caducidad">Caducidad</option>
                                <option value="transporte">Transporte</option>
                                <option value="almacenamiento">Almacenamiento</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_fecha_inicio" class="form-label fw-semibold">Fecha Inicio:</label>
                            <input type="date" id="filter_fecha_inicio" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="filter_fecha_fin" class="form-label fw-semibold">Fecha Fin:</label>
                            <input type="date" id="filter_fecha_fin" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="button" id="btn_filtrar" class="btn btn-info">
                                    <i class="fas fa-filter me-1"></i> Filtrar
                                </button>
                                <button type="button" id="btn_limpiar" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de registros -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2 text-success"></i>
                        Registros de Mermas
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Fecha</th>
                                    <th class="border-0">Galpón</th>
                                    <th class="border-0">Producto</th>
                                    <th class="border-0">Cantidad Perdida (kg)</th>
                                    <th class="border-0">Causa</th>
                                    <th class="border-0">Costo Pérdida</th>
                                    <th class="border-0">Responsable</th>
                                    <th class="border-0">Estado</th>
                                    <th class="border-0">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mermas ?? [] as $merma)
                                    <tr>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary">#{{ $merma->id }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                {{ $merma->fecha_registro->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-warehouse me-1 text-primary"></i>
                                            {{ $merma->galpon->name ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-box me-1 text-info"></i>
                                            {{ $merma->producto->nombre ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-semibold text-danger">
                                                {{ number_format($merma->cantidad_perdida, 2) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-{{ $merma->causa_merma == 'derrame' ? 'danger' : 
                                                                   ($merma->causa_merma == 'contaminacion' ? 'warning' : 
                                                                   ($merma->causa_merma == 'roedores' ? 'dark' : 'info')) }}">
                                                {{ ucfirst($merma->causa_merma_nombre) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-semibold text-warning">
                                                ${{ number_format($merma->costo_perdida, 2) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-user me-1 text-secondary"></i>
                                            {{ $merma->responsable }}
                                        </td>
                                        <td class="align-middle">
                                            @if($merma->estado == 'active')
                                                <span class="badge bg-success">Activo</span>
                                            @elseif($merma->estado == 'cancelled')
                                                <span class="badge bg-danger">Cancelado</span>
                                            @else
                                                <span class="badge bg-warning">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('avicontrol.admin.food_waste.show', $merma->id) }}" 
                                                   class="btn btn-outline-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('avicontrol.admin.food_waste.edit', $merma->id) }}" 
                                                   class="btn btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($merma->estado == 'active')
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="cancelarMerma({{ $merma->id }})" title="Cancelar">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                                <p class="h5">No hay registros de mermas de alimento</p>
                                                <p class="text-muted">Comience creando un nuevo registro de merma</p>
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
    @if(isset($mermas) && $mermas->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $mermas->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal de confirmación para cancelar -->
<div class="modal fade" id="modalCancelar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Cancelación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea cancelar este registro de merma?</p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formCancelar" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Filtros
    $('#btn_filtrar').click(function() {
        aplicarFiltros();
    });

    $('#btn_limpiar').click(function() {
        limpiarFiltros();
    });

    // Aplicar filtros con Enter
    $('.form-control, .form-select').keypress(function(e) {
        if(e.which == 13) {
            aplicarFiltros();
        }
    });

    function aplicarFiltros() {
        let url = new URL(window.location);
        let params = new URLSearchParams(url.search);
        
        let galpon = $('#filter_galpon').val();
        let producto = $('#filter_producto').val();
        let causa = $('#filter_causa').val();
        let fechaInicio = $('#filter_fecha_inicio').val();
        let fechaFin = $('#filter_fecha_fin').val();

        if (galpon) params.set('galpon', galpon);
        if (producto) params.set('producto', producto);
        if (causa) params.set('causa', causa);
        if (fechaInicio) params.set('fecha_inicio', fechaInicio);
        if (fechaFin) params.set('fecha_fin', fechaFin);

        url.search = params.toString();
        window.location.href = url.toString();
    }

    function limpiarFiltros() {
        $('#filter_galpon').val('');
        $('#filter_producto').val('');
        $('#filter_causa').val('');
        $('#filter_fecha_inicio').val('');
        $('#filter_fecha_fin').val('');
        
        window.location.href = window.location.pathname;
    }

    // Cargar filtros desde URL
    let urlParams = new URLSearchParams(window.location.search);
    $('#filter_galpon').val(urlParams.get('galpon') || '');
    $('#filter_producto').val(urlParams.get('producto') || '');
    $('#filter_causa').val(urlParams.get('causa') || '');
    $('#filter_fecha_inicio').val(urlParams.get('fecha_inicio') || '');
    $('#filter_fecha_fin').val(urlParams.get('fecha_fin') || '');
});

function cancelarMerma(id) {
    if (confirm('¿Está seguro de que desea cancelar este registro de merma?')) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = `/avicontrol/admin/food_waste/${id}`;
        
        let csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        let methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Exportar datos
function exportarDatos() {
    let url = new URL('{{ route("avicontrol.admin.food_waste.exportar") }}');
    let params = new URLSearchParams();
    
    let galpon = $('#filter_galpon').val();
    let producto = $('#filter_producto').val();
    let causa = $('#filter_causa').val();
    let fechaInicio = $('#filter_fecha_inicio').val();
    let fechaFin = $('#filter_fecha_fin').val();

    if (galpon) params.set('galpon', galpon);
    if (producto) params.set('producto', producto);
    if (causa) params.set('causa', causa);
    if (fechaInicio) params.set('fecha_inicio', fechaInicio);
    if (fechaFin) params.set('fecha_fin', fechaFin);

    url.search = params.toString();
    window.open(url.toString(), '_blank');
}
</script>
@endsection
