@extends('avicontrol::layouts.admin')

@section('title', 'Informes de Inventario')

@push('styles')
    {{-- Si hay estilos personalizados estrictamente necesarios, colócalos aquí --}}
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#inventoryTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "pageLength": 10,
            "order": [[ 0, "asc" ]], // Ordenar por código de lote ascendente
            "columnDefs": [
                { "orderable": false, "targets": 6 } // Deshabilitar ordenamiento en columna de acciones
            ]
        });
    });
</script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="mb-0">Informes de Inventario</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-boxes text-primary me-2"></i>
                    Gestión de Inventario
                </h1>
                <p class="text-muted">Genere y consulte informes detallados del inventario de aves</p>
            </div>
            <div>
                <a href="{{ route('avicontrol.admin.information.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Informes
                </a>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total de Lotes
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ count($birds) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dove fa-2x text-gray-300"></i>
                            </div>
                        </div>
                </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total de Aves
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($birds->sum('quantity')) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-egg fa-2x text-gray-300"></i>
                            </div>
                        </div>
                </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Lotes Activos
                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                        @php
                            $activosCount = $birds->where('status', 'active')->count();
                        @endphp
                        {{ $activosCount }}
                    </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Valor Total
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ${{ number_format($birds->sum(function($bird) { return $bird->quantity * ($bird->purchase_price ?: 0); }), 2) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Inventario de Aves</h6>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('all')">Todos</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('active')">Activos</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('sold')">Vendidos</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('deceased')">Fallecidos</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterByStatus('transferred')">Transferidos</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="inventoryTable">
                    <thead>
                        <tr>
                            <th>Código Lote</th>
                            <th>Tipo de Ave</th>
                            <th>Raza</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th>Valor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                        <tbody>
                        @foreach($birds as $bird)
                            <tr class="bird-row" data-status="{{ $bird->status }}">
                                <td class="bird-code">
                                    <strong>{{ $bird->batch_code }}</strong>
                                </td>
                                <td>
                                    @switch($bird->bird_type)
                                        @case('laying_hens')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-egg me-1"></i> Gallinas Ponedoras
                                            </span>
                                            @break
                                        @case('broilers')
                                            <span class="badge bg-info">
                                                <i class="fas fa-drumstick-bite me-1"></i> Pollos de Engorde
                                            </span>
                                            @break
                                        @case('chicks')
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-baby me-1"></i> Pollitos
                                            </span>
                                            @break
                                        @case('breeders')
                                            <span class="badge bg-primary">
                                                <i class="fas fa-heart me-1"></i> Reproductores
                                            </span>
                                            @break
                                        @case('roosters')
                                            <span class="badge bg-success">
                                                <i class="fas fa-cocktail me-1"></i> Gallos
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $bird->breed ?: 'No especificada' }}</td>
                                <td class="quantity-cell">
                                    <strong class="text-success">{{ number_format($bird->quantity) }}</strong> aves
                                </td>
                                <td>
                                    @switch($bird->status)
                                        @case('active')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i> Activo
                                            </span>
                                            @break
                                        @case('sold')
                                            <span class="badge bg-primary">
                                                <i class="fas fa-dollar-sign me-1"></i> Vendido
                                            </span>
                                            @break
                                        @case('deceased')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-skull me-1"></i> Fallecido
                                            </span>
                                            @break
                                        @case('transferred')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-exchange-alt me-1"></i> Transferido
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="value-cell">
                                    <strong class="text-primary">${{ number_format($bird->quantity * ($bird->purchase_price ?: 0), 2) }}</strong>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('avicontrol.admin.birds.show', $bird->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('avicontrol.admin.birds.edit', $bird->id) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-chart-bar me-2"></i>Resumen de Inventario</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-dove text-success me-2"></i> Lotes: <strong>{{ count($birds) }}</strong></li>
                    <li class="mb-2"><i class="fas fa-chart-bar text-info me-2"></i> Activos: <strong>{{ $activosCount }}</strong></li>
                    <li class="mb-2"><i class="fas fa-boxes text-warning me-2"></i> Total Aves: <strong>{{ number_format($birds->sum('quantity')) }}</strong></li>
                    <li><i class="fas fa-dollar-sign text-primary me-2"></i> Valor: <strong>${{ number_format($birds->sum(function($bird) { return $bird->quantity * ($bird->purchase_price ?: 0); }), 2) }}</strong></li>
                </ul>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header bg-light py-3">
                <h6 class="m-0 fw-bold text-success"><i class="fas fa-chart-pie me-2"></i>Distribución por Tipo</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @php
                        $typeStats = $birds->groupBy('bird_type')->map(function($group) {
                            return [
                                'count' => $group->count(),
                                'quantity' => $group->sum('quantity'),
                                'value' => $group->sum(function($bird) { return $bird->quantity * ($bird->purchase_price ?: 0); })
                            ];
                        });
                    @endphp
                    
                    @foreach($typeStats as $type => $stats)
                        <li class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary me-2">
                                    @switch($type)
                                        @case('laying_hens')
                                            <i class="fas fa-egg"></i>
                                            @break
                                        @case('broilers')
                                            <i class="fas fa-drumstick-bite"></i>
                                            @break
                                        @case('chicks')
                                            <i class="fas fa-baby"></i>
                                            @break
                                        @case('breeders')
                                            <i class="fas fa-heart"></i>
                                            @break
                                        @case('roosters')
                                            <i class="fas fa-cocktail"></i>
                                            @break
                                    @endswitch
                                </span>
                                <strong>{{ $stats['count'] }} lotes</strong>
                            </div>
                            <small class="text-muted">{{ number_format($stats['quantity']) }} aves - ${{ number_format($stats['value'], 2) }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function filterByStatus(status) {
    const rows = document.querySelectorAll('.bird-row');
    
    rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        
        if (status === 'all' || rowStatus === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Actualizar la tabla de DataTables
    $('#inventoryTable').DataTable().draw();
}
</script>
@endsection 