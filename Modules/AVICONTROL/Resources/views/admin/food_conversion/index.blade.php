@extends('avicontrol::layouts.admin')

@section('title', 'Control de Conversión Alimenticia')

@section('content')
<div class="container-fluid">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary">
                        <i class="fas fa-exchange-alt me-2"></i>
                        Control de Conversión Alimenticia
                    </h1>
                    <p class="text-muted mb-0">Monitoree la eficiencia de conversión de alimento a producto</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('avicontrol.admin.food_conversion.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nuevo Registro
                    </a>
                    <a href="{{ route('avicontrol.admin.food_conversion.reporte') }}" class="btn btn-info">
                        <i class="fas fa-chart-bar me-1"></i> Reporte
                    </a>
                    <a href="{{ route('avicontrol.admin.food_conversion.estadisticas') }}" class="btn btn-success">
                        <i class="fas fa-chart-pie me-1"></i> Estadísticas
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
                            <label for="filter_periodo" class="form-label fw-semibold">Período:</label>
                            <select id="filter_periodo" class="form-select">
                                <option value="">Todos los períodos</option>
                                <option value="diario">Diario</option>
                                <option value="semanal">Semanal</option>
                                <option value="mensual">Mensual</option>
                                <option value="acumulado">Acumulado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_tipo_produccion" class="form-label fw-semibold">Tipo de Producción:</label>
                            <select id="filter_tipo_produccion" class="form-select">
                                <option value="">Todos los tipos</option>
                                <option value="huevo">Huevo</option>
                                <option value="carne">Carne</option>
                                <option value="reproductor">Reproductor</option>
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
                        Registros de Conversión
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Galpón</th>
                                    <th class="border-0">Período</th>
                                    <th class="border-0">Fecha Inicio</th>
                                    <th class="border-0">Fecha Fin</th>
                                    <th class="border-0">Alimento Consumido (kg)</th>
                                    <th class="border-0">Producto Obtenido (kg)</th>
                                    <th class="border-0">Conversión</th>
                                    <th class="border-0">Eficiencia</th>
                                    <th class="border-0">Estado</th>
                                    <th class="border-0">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($conversiones ?? [] as $conversion)
                                    <tr>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary">#{{ $conversion->id }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-warehouse me-1 text-primary"></i>
                                            {{ $conversion->galpon->name ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-info">
                                                {{ ucfirst($conversion->periodo_tipo) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                {{ $conversion->fecha_inicio->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                {{ $conversion->fecha_fin->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-semibold text-warning">
                                                {{ number_format($conversion->total_alimento_consumido, 2) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-semibold text-success">
                                                {{ number_format($conversion->total_producto_obtenido, 2) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-{{ $conversion->color_eficiencia }}">
                                                {{ $conversion->conversion_alimenticia_formateada }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-{{ $conversion->color_eficiencia }}">
                                                {{ $conversion->nivel_eficiencia }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            @if($conversion->estado == 'active')
                                                <span class="badge bg-success">Activo</span>
                                            @elseif($conversion->estado == 'cancelled')
                                                <span class="badge bg-danger">Cancelado</span>
                                            @else
                                                <span class="badge bg-warning">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('avicontrol.admin.food_conversion.show', $conversion->id) }}" 
                                                   class="btn btn-outline-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('avicontrol.admin.food_conversion.edit', $conversion->id) }}" 
                                                   class="btn btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($conversion->estado == 'active')
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="cancelarConversion({{ $conversion->id }})" title="Cancelar">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-chart-line fa-3x mb-3"></i>
                                                <p class="h5">No hay registros de conversión alimenticia</p>
                                                <p class="text-muted">Comience creando un nuevo registro de conversión</p>
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
    @if(isset($conversiones) && $conversiones->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $conversiones->links() }}
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
                <p>¿Está seguro de que desea cancelar este registro de conversión alimenticia?</p>
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
        let periodo = $('#filter_periodo').val();
        let tipoProduccion = $('#filter_tipo_produccion').val();
        let fechaInicio = $('#filter_fecha_inicio').val();
        let fechaFin = $('#filter_fecha_fin').val();

        if (galpon) params.set('galpon', galpon);
        if (periodo) params.set('periodo', periodo);
        if (tipoProduccion) params.set('tipo_produccion', tipoProduccion);
        if (fechaInicio) params.set('fecha_inicio', fechaInicio);
        if (fechaFin) params.set('fecha_fin', fechaFin);

        url.search = params.toString();
        window.location.href = url.toString();
    }

    function limpiarFiltros() {
        $('#filter_galpon').val('');
        $('#filter_periodo').val('');
        $('#filter_tipo_produccion').val('');
        $('#filter_fecha_inicio').val('');
        $('#filter_fecha_fin').val('');
        
        window.location.href = window.location.pathname;
    }

    // Cargar filtros desde URL
    let urlParams = new URLSearchParams(window.location.search);
    $('#filter_galpon').val(urlParams.get('galpon') || '');
    $('#filter_periodo').val(urlParams.get('periodo') || '');
    $('#filter_tipo_produccion').val(urlParams.get('tipo_produccion') || '');
    $('#filter_fecha_inicio').val(urlParams.get('fecha_inicio') || '');
    $('#filter_fecha_fin').val(urlParams.get('fecha_fin') || '');
});

function cancelarConversion(id) {
    if (confirm('¿Está seguro de que desea cancelar este registro de conversión alimenticia?')) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = `/avicontrol/admin/food_conversion/${id}`;
        
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
    let url = new URL('{{ route("avicontrol.admin.food_conversion.exportar") }}');
    let params = new URLSearchParams();
    
    let galpon = $('#filter_galpon').val();
    let periodo = $('#filter_periodo').val();
    let tipoProduccion = $('#filter_tipo_produccion').val();
    let fechaInicio = $('#filter_fecha_inicio').val();
    let fechaFin = $('#filter_fecha_fin').val();

    if (galpon) params.set('galpon', galpon);
    if (periodo) params.set('periodo', periodo);
    if (tipoProduccion) params.set('tipo_produccion', tipoProduccion);
    if (fechaInicio) params.set('fecha_inicio', fechaInicio);
    if (fechaFin) params.set('fecha_fin', fechaFin);

    url.search = params.toString();
    window.open(url.toString(), '_blank');
}
</script>
@endsection
