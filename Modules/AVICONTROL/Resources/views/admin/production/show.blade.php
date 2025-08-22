@extends('avicontrol::layouts.admin')

@section('title', 'Detalles de Producción')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-eye text-primary"></i> Detalles de Producción
            </h1>
            <p class="text-muted mb-0">Información completa del registro de producción</p>
        </div>
        <div>
            <a href="{{ route('avicontrol.admin.production.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
            <a href="{{ route('avicontrol.admin.production.edit', $production->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </div>

    <!-- Información Principal -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-egg text-primary"></i> Información de Producción
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Fecha de Producción:</label>
                            <p class="mb-0">
                                <i class="fas fa-calendar text-success"></i>
                                {{ $production->fecha ? $production->fecha->format('d/m/Y') : 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tipo de Huevo:</label>
                            <p class="mb-0">
                                <span class="badge bg-primary fs-6">{{ $production->tipo }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Cantidad:</label>
                            <p class="mb-0">
                                <i class="fas fa-egg text-warning"></i>
                                <span class="fs-5 fw-bold">{{ number_format($production->cantidad) }}</span> huevos
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Valor por Unidad:</label>
                            <p class="mb-0">
                                <i class="fas fa-dollar-sign text-success"></i>
                                <span class="fs-5 fw-bold">${{ number_format($production->valor_unidad, 0) }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Valor Total:</label>
                            <p class="mb-0">
                                <i class="fas fa-calculator text-info"></i>
                                <span class="fs-5 fw-bold text-success">${{ number_format($production->valor_total, 0) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Destino:</label>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                {{ $production->destino ?? 'No especificado' }}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Semana de Producción:</label>
                            <p class="mb-0">
                                <i class="fas fa-calendar-week text-primary"></i>
                                {{ $production->semana_produccion ?? 'No especificada' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Estado:</label>
                            <p class="mb-0">
                                @if($production->estado == 'activo')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check"></i> Activo
                                    </span>
                                @else
                                    <span class="badge bg-danger fs-6">
                                        <i class="fas fa-times"></i> Inactivo
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($production->observaciones)
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Observaciones:</label>
                            <p class="mb-0 p-3 bg-light rounded">
                                <i class="fas fa-comment text-muted"></i>
                                {{ $production->observaciones }}
                            </p>
                        </div>
                    </div>
                    @endif

                    <hr class="my-4">

                    <h6 class="text-primary mb-3"><i class="fas fa-users"></i> Responsables</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Recibido por:</label>
                            <p class="mb-0">
                                <i class="fas fa-user-check text-success"></i>
                                {{ $production->firma_recibido ?? 'No especificado' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Líder:</label>
                            <p class="mb-0">
                                <i class="fas fa-user-tie text-primary"></i>
                                {{ $production->firma_lider ?? 'No especificado' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar con información adicional -->
        <div class="col-lg-4">
            <!-- Información del Sistema -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle text-info"></i> Información del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ID del Registro:</label>
                        <p class="mb-0">
                            <code class="bg-light px-2 py-1 rounded">{{ $production->id }}</code>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Código de Lote:</label>
                        <p class="mb-0">
                            @if($production->batch_code)
                                <span class="badge bg-info">{{ $production->batch_code }}</span>
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Creado:</label>
                        <p class="mb-0">
                            <i class="fas fa-clock text-muted"></i>
                            {{ $production->created_at ? $production->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Última Actualización:</label>
                        <p class="mb-0">
                            <i class="fas fa-edit text-muted"></i>
                            {{ $production->updated_at ? $production->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt text-warning"></i> Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('avicontrol.admin.production.edit', $production->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar Registro
                        </a>
                        
                        <form action="{{ route('avicontrol.admin.production.toggle-status', $production->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-{{ $production->estado == 'activo' ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $production->estado == 'activo' ? 'pause' : 'play' }}"></i>
                                {{ $production->estado == 'activo' ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>

                        <form action="{{ route('avicontrol.admin.production.destroy', $production->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este registro?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Histórica (Columnas Antiguas) -->
    @if($production->egg_type_a || $production->egg_type_aa || $production->egg_type_b || $production->egg_type_c || $production->egg_type_d)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history text-secondary"></i> Información Histórica
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="text-success mb-1">{{ number_format($production->egg_type_a ?? 0) }}</h5>
                                <p class="mb-0 text-muted small">Tipo A</p>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="text-primary mb-1">{{ number_format($production->egg_type_aa ?? 0) }}</h5>
                                <p class="mb-0 text-muted small">Tipo AA</p>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="text-info mb-1">{{ number_format($production->egg_type_b ?? 0) }}</h5>
                                <p class="mb-0 text-muted small">Tipo B</p>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="text-warning mb-1">{{ number_format($production->egg_type_c ?? 0) }}</h5>
                                <p class="mb-0 text-muted small">Tipo C</p>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="text-secondary mb-1">{{ number_format($production->egg_type_d ?? 0) }}</h5>
                                <p class="mb-0 text-muted small">Tipo D</p>
                            </div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="text-success mb-1">{{ number_format($production->egg_count_total ?? 0) }}</h5>
                                <p class="mb-0 text-muted small">Total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Scripts adicionales si son necesarios
    $(document).ready(function() {
        // Inicializar tooltips si es necesario
        if (typeof bootstrap !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });
</script>
@endpush
