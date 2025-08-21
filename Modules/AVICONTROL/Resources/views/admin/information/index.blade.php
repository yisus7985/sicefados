@extends('avicontrol::layouts.admin')

@section('title', 'Informes de Galpones')

@section('styles')
    {{-- Si hay estilos personalizados estrictamente necesarios, colócalos aquí --}}
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#galponesTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "pageLength": 10,
            "order": [[ 0, "asc" ]], // Ordenar por nombre de galpón ascendente
            "columnDefs": [
                { "orderable": false, "targets": 3 } // Deshabilitar ordenamiento en columna de acciones
            ]
        });
    });
</script>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Mensaje de error si las tablas no existen -->
    @if(isset($error))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>⚠️ Advertencia:</strong> {{ $error }}
            <br><br>
            <strong>Para solucionar esto:</strong>
            <ol>
                <li>Ejecute el comando: <code>php artisan migrate --path=Modules/AVICONTROL/Database/Migrations</code></li>
                <li>O ejecute: <code>php artisan avicontrol:install-costos</code></li>
            </ol>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Encabezado de la Página -->
<div class="card shadow-sm mb-2">
    <div class="card-body p-2 d-flex flex-wrap justify-content-between align-items-center">
        <div class="mb-1 mb-md-0">
            <h1 class="h5 mb-0 text-gray-800">
                <i class="fas fa-clipboard-list text-primary me-1"></i>
                Gestión de Informes
            </h1>
            <p class="text-muted small mb-0">Consulte y genere informes detallados.</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('avicontrol.admin.information.inventory') }}" class="btn btn-sm btn-outline-success">
                <i class="fas fa-boxes me-1"></i> Inventario
            </a>
            <a href="{{ route('avicontrol.admin.information.pdf.all_galpones') }}" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-file-pdf me-1"></i> Reporte Completo
            </a>
            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#dateFilterModal">
                <i class="fas fa-calendar-alt me-1"></i> Reporte por Fecha
            </button>
        </div>
    </div>
</div>

<div class="row">
    <!-- Columna Principal -->
    <div class="col-lg-9">
        <!-- Fila de Estadísticas -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-success shadow-sm h-100">
                    <div class="card-body p-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Galpones</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">{{ count($galpones) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-warehouse fa-lg text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-info shadow-sm h-100">
                    <div class="card-body p-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Aves Activas</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $totalAvesActivas ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-kiwi-bird fa-lg text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-warning shadow-sm h-100">
                    <div class="card-body p-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Capacidad Total</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">{{ number_format($totalCapacidad ?? 0) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-pie fa-lg text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-primary shadow-sm h-100">
                    <div class="card-body p-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ocupación</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">{{ number_format($ocupacionPromedio ?? 0, 1) }}%</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-percent fa-lg text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Galpones -->
        <div class="card shadow-sm">
            <div class="card-header py-2">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-warehouse me-1"></i>Lista de Galpones</h6>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="galponesTable" width="100%" cellspacing="0">
                        <thead class="table-success">
                            <tr>
                                <th>Nombre</th>
                                <th>Capacidad</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($galpones as $galpon)
                                <tr>
                                    <td>{{ $galpon->name }}</td>
                                    <td>{{ number_format($galpon->capacity) }}</td>
                                    <td><span class="badge bg-{{ $galpon->status === 'Activo' ? 'success' : 'secondary' }}">{{ $galpon->status }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('avicontrol.admin.information.show', $galpon->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Ver Reporte"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('avicontrol.admin.information.pdf.galpon', $galpon->id) }}" class="btn btn-sm btn-danger" target="_blank" data-bs-toggle="tooltip" title="Descargar PDF"><i class="fas fa-file-pdf"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay galpones registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna Lateral -->
    <div class="col-lg-3">
        <div class="card shadow-sm mb-2">
            <div class="card-header py-2 bg-light">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-bar me-1"></i>Resumen</h6>
            </div>
            <div class="card-body p-2">
                <ul class="list-unstyled mb-0 small">
                    @php
                        $activosCount = $galpones->filter(function($galpon) {
                            return stripos($galpon->status, 'activ') !== false;
                        })->count();
                    @endphp
                    <li class="mb-1 d-flex justify-content-between"><span><i class="fas fa-warehouse text-muted me-2"></i>Galpones</span> <strong>{{ count($galpones) }}</strong></li>
                    <li class="mb-1 d-flex justify-content-between"><span><i class="fas fa-check-circle text-muted me-2"></i>Activos</span> <strong>{{ $activosCount }}</strong></li>
                    <li class="d-flex justify-content-between"><span><i class="fas fa-egg text-muted me-2"></i>Capacidad Total</span> <strong>{{ number_format($galpones->sum('capacity')) }}</strong></li>
                </ul>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header py-2 bg-light">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-1"></i>Actividad Reciente</h6>
            </div>
            <div class="card-body p-2">
                <small class="text-muted">No hay actividad reciente.</small>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Filtro por Fechas -->
<div class="modal fade" id="dateFilterModal" tabindex="-1" aria-labelledby="dateFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dateFilterModalLabel">Filtrar Informe por Fecha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('avicontrol.admin.information.pdf.galpones_by_date') }}" method="GET" target="_blank">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Generar PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection