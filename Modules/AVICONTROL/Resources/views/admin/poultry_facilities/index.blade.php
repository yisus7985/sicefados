@extends('avicontrol::layouts.admin')

@section('title', 'Gestión de Instalaciones Avícolas - AVICONTROL')

@section('content')
<div class="container-fluid">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary">
                        <i class="fas fa-warehouse me-2"></i>
                        Gestión de Instalaciones Avícolas
                    </h1>
                    <p class="text-muted mb-0">Administre las instalaciones y galpones del sistema</p>
                </div>
                <div>
                    <a href="{{ route('avicontrol.admin.poultry_facilities.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nueva Instalación
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
    
    <!-- Poultry Facilities List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Lista de Instalaciones Avícolas
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="poultryFacilitiesTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Nombre</th>
                                    <th class="border-0">Tipo de Producción</th>
                                    <th class="border-0">Dimensiones (m)</th>
                                    <th class="border-0">Capacidad</th>
                                    <th class="border-0">Estado</th>
                                    <th class="border-0">Fecha de Creación</th>
                                    <th class="border-0">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($poultryFacilities as $facility)
                                    <tr>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary">#{{ $facility->id }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <strong>{{ $facility->name }}</strong>
                                        </td>
                                        <td class="align-middle">
                                            @if($facility->tipo == 'gallinas_ponedoras')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-egg me-1"></i>Huevos
                                                </span>
                                            @elseif($facility->tipo == 'pollos_engorde')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-drumstick-bite me-1"></i>Carne
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">No definido</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-muted">
                                                {{ $facility->length }} x {{ $facility->width }} x {{ $facility->height }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-info">
                                                {{ $facility->capacity }} aves
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            @if($facility->status == 'active')
                                                <span class="badge bg-success">Activo</span>
                                            @elseif($facility->status == 'inactive')
                                                <span class="badge bg-danger">Inactivo</span>
                                            @else
                                                <span class="badge bg-warning">Mantenimiento</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                {{ $facility->creation_date->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('avicontrol.admin.poultry_facilities.show', $facility->id) }}" 
                                                   class="btn btn-outline-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('avicontrol.admin.poultry_facilities.edit', $facility->id) }}" 
                                                   class="btn btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" title="Eliminar" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $facility->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $facility->id }}" tabindex="-1" 
                                                 aria-labelledby="deleteModalLabel{{ $facility->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel{{ $facility->id }}">Confirmar Eliminación</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Está seguro de que desea eliminar la instalación <strong>{{ $facility->name }}</strong>?
                                                            <p class="text-danger mt-2">Esta acción no se puede deshacer.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('avicontrol.admin.poultry_facilities.destroy', $facility->id) }}" method="POST">
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
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-warehouse fa-3x mb-3"></i>
                                                <p class="h5">No hay instalaciones registradas</p>
                                                <p class="text-muted">Comience creando una nueva instalación</p>
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
