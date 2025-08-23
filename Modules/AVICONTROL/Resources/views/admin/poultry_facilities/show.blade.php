@extends('avicontrol::layouts.admin')

@section('title', 'Detalles de Instalación Avícola - AVICONTROL')

@section('content')
<div class="container-fluid">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary">
                        <i class="fas fa-warehouse me-2"></i>
                        Detalles de Instalación Avícola
                    </h1>
                    <p class="text-muted mb-0">Información detallada de la instalación</p>
                </div>
                <div>
                    <a href="{{ route('avicontrol.admin.poultry_facilities.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                    <a href="{{ route('avicontrol.admin.poultry_facilities.edit', $poultryFacility->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la Instalación -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Nombre de la Instalación</label>
                            <p class="h5 mb-0">{{ $poultryFacility->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Estado</label>
                            <div>
                                @if($poultryFacility->status == 'active')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Activo
                                    </span>
                                @elseif($poultryFacility->status == 'maintenance')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-tools me-1"></i> En Mantenimiento
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Inactivo
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Código</label>
                            <p class="mb-0">{{ $poultryFacility->code }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Tipo de Producción</label>
                            <div>
                                @if($poultryFacility->tipo == 'gallinas_ponedoras')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-egg me-1"></i>Gallinas Ponedoras (Huevos)
                                    </span>
                                @elseif($poultryFacility->tipo == 'pollos_engorde')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-drumstick-bite me-1"></i>Pollos de Engorde (Carne)
                                    </span>
                                @else
                                    <span class="badge bg-secondary">No definido</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Capacidad</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ number_format($poultryFacility->capacity) }} aves</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Dimensiones</label>
                            <p class="mb-0">{{ $poultryFacility->length }}m × {{ $poultryFacility->width }}m × {{ $poultryFacility->height }}m</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Área Total</label>
                            <p class="mb-0">{{ $poultryFacility->length * $poultryFacility->width }}m²</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Ubicación</label>
                            <p class="mb-0">{{ $poultryFacility->location ?? 'No especificada' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">Fecha de Registro</label>
                            <p class="mb-0">{{ $poultryFacility->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small">Descripción</label>
                            <p class="mb-0">{{ $poultryFacility->description ?: 'No hay descripción disponible.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2 text-primary"></i>
                        Actividad Reciente
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($recent_activities ?? []) > 0)
                        @foreach($recent_activities as $activity)
                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="mb-1">{{ $activity->title ?? 'Actividad' }}</h6>
                            <p class="text-muted mb-1">{{ $activity->description ?? 'Sin descripción' }}</p>
                            <small class="text-muted">{{ $activity->created_at->format('d/m/Y H:i') ?? 'Fecha no disponible' }}</small>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">No hay actividades recientes registradas.</p>
                    @endif
                    
                    <div class="mt-3">
                        <a href="#" class="btn btn-sm btn-outline-primary">Ver todas las actividades</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Estadísticas -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Estadísticas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded p-3 mb-3">
                            <i class="fas fa-egg fa-2x text-warning mb-2"></i>
                            <h3 class="text-primary mb-1">{{ number_format($current_production ?? 0) }}</h3>
                            <p class="text-muted mb-0 small">Producción Actual (Huevos/Día)</p>
                        </div>
                        
                        <div class="bg-light rounded p-3 mb-3">
                            <i class="fas fa-percentage fa-2x text-info mb-2"></i>
                            <h3 class="text-primary mb-1">{{ number_format($production_rate ?? 0, 1) }}%</h3>
                            <p class="text-muted mb-0 small">Tasa de Postura</p>
                        </div>
                        
                        <div class="bg-light rounded p-3 mb-3">
                            <i class="fas fa-dove fa-2x text-success mb-2"></i>
                            <h3 class="text-primary mb-1">{{ number_format($current_birds ?? 0) }}</h3>
                            <p class="text-muted mb-0 small">Aves Actuales</p>
                        </div>
                        
                        <div class="bg-light rounded p-3">
                            <i class="fas fa-weight fa-2x text-secondary mb-2"></i>
                            <h3 class="text-primary mb-1">{{ number_format($avg_weight ?? 0, 2) }}g</h3>
                            <p class="text-muted mb-0 small">Peso Promedio del Huevo</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Acciones Rápidas -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-primary"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary mb-2">
                            <i class="fas fa-clipboard-list me-2"></i> Registrar Producción
                        </a>
                        <a href="#" class="btn btn-outline-info mb-2">
                            <i class="fas fa-print me-2"></i> Generar Reporte
                        </a>
                        <a href="{{ route('avicontrol.admin.poultry_facilities.edit', $poultryFacility->id) }}" class="btn btn-outline-warning mb-2">
                            <i class="fas fa-edit me-2"></i> Editar Instalación
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i> Eliminar Instalación
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea eliminar la instalación <strong>{{ $poultryFacility->name }}</strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i> Esta acción no se puede deshacer y eliminará todos los datos asociados.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('avicontrol.admin.poultry_facilities.destroy', $poultryFacility->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
