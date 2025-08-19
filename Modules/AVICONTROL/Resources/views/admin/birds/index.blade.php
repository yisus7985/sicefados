@extends('avicontrol::layouts.admin')

@section('title', 'Gestión de Aves - AVICONTROL')

@section('content')
<div class="container-fluid">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary">
                        <i class="fas fa-dove me-2"></i>
                        Gestión de Aves
                    </h1>
                    <p class="text-muted mb-0">Administre los lotes de aves del sistema</p>
                </div>
                <div>
                    <a href="{{ route('avicontrol.admin.birds.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nuevo Lote de Aves
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-dove fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-0">Total de Aves</h6>
                            <h4 class="mb-0">{{ $birds->count() }}</h4>
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
                            <i class="fas fa-egg fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-0">Ponedoras</h6>
                            <h4 class="mb-0">{{ $birds->where('bird_type', 'layer')->count() }}</h4>
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
                            <i class="fas fa-drumstick-bite fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-0">Pollo de Engorde</h6>
                            <h4 class="mb-0">{{ $birds->where('bird_type', 'broiler')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-venus-mars fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-0">Reproductoras</h6>
                            <h4 class="mb-0">{{ $birds->where('bird_type', 'breeder')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Birds Management -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Lotes de Aves
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('avicontrol.admin.birds.index') }}" class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label for="facility_id" class="form-label fw-semibold">Instalación</label>
                            <select name="facility_id" id="facility_id" class="form-select">
                                <option value="">Todas las instalaciones</option>
                                @foreach($poultryFacilities as $facility)
                                    <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                        {{ $facility->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="bird_type" class="form-label fw-semibold">Tipo de Ave</label>
                            <select name="bird_type" id="bird_type" class="form-select">
                                <option value="">Todos</option>
                                <option value="layer" {{ request('bird_type') == 'layer' ? 'selected' : '' }}>Ponedoras</option>
                                <option value="broiler" {{ request('bird_type') == 'broiler' ? 'selected' : '' }}>Pollo de Engorde</option>
                                <option value="breeder" {{ request('bird_type') == 'breeder' ? 'selected' : '' }}>Reproductoras</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label fw-semibold">Estado</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Vendido</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_from" class="form-label fw-semibold">Desde</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label fw-semibold">Hasta</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Birds Table -->
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="birdsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Lote</th>
                                    <th class="border-0">Instalación</th>
                                    <th class="border-0">Tipo</th>
                                    <th class="border-0">Cantidad</th>
                                    <th class="border-0">Edad (días)</th>
                                    <th class="border-0">Estado</th>
                                    <th class="border-0">Fecha de Llegada</th>
                                    <th class="border-0">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($birds as $bird)
                                    <tr>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary">#{{ $bird->id }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <strong>{{ $bird->batch_name }}</strong>
                                        </td>
                                        <td class="align-middle">
                                            <i class="fas fa-warehouse me-1 text-primary"></i>
                                            {{ $bird->poultryFacility->name ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle">
                                            @if($bird->bird_type == 'layer')
                                                <span class="badge bg-success">Ponedoras</span>
                                            @elseif($bird->bird_type == 'broiler')
                                                <span class="badge bg-warning">Pollo de Engorde</span>
                                            @elseif($bird->bird_type == 'breeder')
                                                <span class="badge bg-primary">Reproductoras</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $bird->bird_type }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-info">{{ $bird->quantity }} aves</span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-muted">{{ $bird->age_days ?? 'N/A' }} días</span>
                                        </td>
                                        <td class="align-middle">
                                            @if($bird->status == 'active')
                                                <span class="badge bg-success">Activo</span>
                                            @elseif($bird->status == 'inactive')
                                                <span class="badge bg-danger">Inactivo</span>
                                            @elseif($bird->status == 'sold')
                                                <span class="badge bg-warning">Vendido</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $bird->status }}</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                {{ $bird->arrival_date ? $bird->arrival_date->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('avicontrol.admin.birds.show', $bird->id) }}" 
                                                   class="btn btn-outline-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('avicontrol.admin.birds.edit', $bird->id) }}" 
                                                   class="btn btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" title="Eliminar" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $bird->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $bird->id }}" tabindex="-1" 
                                                 aria-labelledby="deleteModalLabel{{ $bird->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $bird->id }}">Confirmar Eliminación</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Está seguro de que desea eliminar el lote <strong>{{ $bird->batch_name }}</strong>?
                                                            <p class="text-danger mt-2">Esta acción no se puede deshacer.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('avicontrol.admin.birds.destroy', $bird->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-dove fa-3x mb-3"></i>
                                                <p class="h5">No hay lotes de aves registrados</p>
                                                <p class="text-muted">Comience creando un nuevo lote de aves</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($birds instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $birds->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection