@extends('avicontrol::layouts.admin')

@section('title', 'Detalles de Producción')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-eye text-primary"></i> Detalles de Producción - {{ $production->tipo_produccion_display }}
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
                        <i class="fas fa-{{ $production->tipo_produccion === 'huevos' ? 'egg' : 'drumstick-bite' }} text-primary"></i> 
                        Información de Producción - {{ $production->tipo_produccion_display }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha de {{ $production->tipo_produccion === 'huevos' ? 'Recolección' : 'Producción' }}:</label>
                            <p class="mb-0">
                                <i class="fas fa-calendar text-success"></i>
                                {{ $production->fecha ? $production->fecha->format('d/m/Y') : 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Producción:</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $production->tipo_produccion === 'huevos' ? 'primary' : 'success' }} fs-6">
                                    {{ $production->tipo_produccion_display }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Galpón:</label>
                            <p class="mb-0">
                                @if($production->galpon)
                                    <i class="fas fa-home text-info"></i>
                                    <span class="badge bg-info">{{ $production->galpon->name }}</span>
                                    <small class="text-muted">({{ $production->galpon->tipo_display }})</small>
                                @else
                                    <span class="text-muted">No especificado</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo:</label>
                            <p class="mb-0">
                                <span class="badge bg-primary fs-6">{{ $production->tipo }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Cantidad:</label>
                            <p class="mb-0">
                                <i class="fas fa-{{ $production->tipo_produccion === 'huevos' ? 'egg' : 'drumstick-bite' }} text-warning"></i>
                                <span class="fs-5 fw-bold">{{ number_format($production->cantidad) }}</span> 
                                {{ $production->tipo_produccion === 'huevos' ? 'huevos' : 'aves' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Mortalidad de Aves:</label>
                            <p class="mb-0">
                                <i class="fas fa-heart-broken text-danger"></i>
                                <span class="fs-5 fw-bold text-danger">{{ number_format($production->mortalidad_aves ?? 0) }}</span> 
                                aves
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Valor por {{ $production->tipo_produccion === 'huevos' ? 'Unidad' : 'Kg' }}:</label>
                            <p class="mb-0">
                                <i class="fas fa-dollar-sign text-success"></i>
                                <span class="fs-5 fw-bold">${{ number_format($production->valor_unidad, 0) }}</span>
                            </p>
                        </div>

                        @if($production->tipo_produccion === 'carne')
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Peso Promedio:</label>
                                <p class="mb-0">
                                    <i class="fas fa-weight-hanging text-info"></i>
                                    <span class="fs-5 fw-bold">{{ $production->formatted_peso_promedio }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Peso Total:</label>
                                <p class="mb-0">
                                    <i class="fas fa-weight-hanging text-success"></i>
                                    <span class="fs-5 fw-bold text-success">{{ $production->formatted_peso_total }}</span>
                                </p>
                            </div>
                        @endif

                        @if($production->tipo_produccion === 'huevos')
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Huevos Rotos:</label>
                                <p class="mb-0">
                                    <i class="fas fa-times-circle text-danger"></i>
                                    <span class="fs-5 fw-bold text-danger">{{ number_format($production->huevos_rotos) }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Huevos Sucios:</label>
                                <p class="mb-0">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    <span class="fs-5 fw-bold text-warning">{{ number_format($production->huevos_sucios) }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Huevos Buenos:</label>
                                <p class="mb-0">
                                    <i class="fas fa-check-circle text-success"></i>
                                    <span class="fs-5 fw-bold text-success">{{ number_format($production->cantidad_huevos_buenos) }}</span>
                                </p>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Valor Total:</label>
                            <p class="mb-0">
                                <i class="fas fa-calculator text-info"></i>
                                <span class="fs-5 fw-bold text-success">${{ number_format($production->valor_total, 0) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Destino:</label>
                            <p class="mb-0">
                                <i class="fas fa-truck text-danger"></i>
                                {{ $production->destino ?? 'No especificado' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Semana de Producción:</label>
                            <p class="mb-0">
                                <i class="fas fa-calendar-week text-primary"></i>
                                {{ $production->semana_produccion ?? 'No especificada' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estado:</label>
                            <p class="mb-0">
                                @if($production->estado == 'activo')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check"></i> Activo
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-pause"></i> Inactivo
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Firma Recibido:</label>
                            <p class="mb-0">
                                <i class="fas fa-user text-primary"></i>
                                {{ $production->firma_recibido ?? 'No especificado' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Firma Líder:</label>
                            <p class="mb-0">
                                <i class="fas fa-user-tie text-info"></i>
                                {{ $production->firma_lider ?? 'No especificado' }}
                            </p>
                        </div>
                        @if($production->observaciones)
                            <div class="col-12">
                                <label class="form-label fw-bold">Observaciones:</label>
                                <p class="mb-0">
                                    <i class="fas fa-comment text-muted"></i>
                                    {{ $production->observaciones }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar con Información Adicional -->
        <div class="col-lg-4">
            <!-- Información del Sistema -->
            <div class="card mb-3">
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

                    @if($production->deleted_at)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Eliminado:</label>
                            <p class="mb-0">
                                <i class="fas fa-trash text-danger"></i>
                                {{ $production->deleted_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-tools text-warning"></i> Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('avicontrol.admin.production.edit', $production->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Editar Registro
                        </a>
                        
                        @if($production->estado === 'activo')
                            <a href="{{ url('avicontrol/admin/production/' . $production->id . '/toggle-status') }}" 
                               class="btn btn-warning btn-sm"
                               onclick="return confirm('¿Está seguro de que desea desactivar este registro?')">
                                <i class="fas fa-pause me-2"></i>Desactivar
                            </a>
                        @else
                            <a href="{{ url('avicontrol/admin/production/' . $production->id . '/toggle-status') }}" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('¿Está seguro de que desea activar este registro?')">
                                <i class="fas fa-play me-2"></i>Activar
                            </a>
                        @endif
                        
                        <form action="{{ route('avicontrol.admin.production.destroy', $production->id) }}" 
                              method="POST" style="display: inline;"
                              onsubmit="return confirm('¿Está seguro de que desea eliminar este registro? Esta acción no se puede deshacer.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                <i class="fas fa-trash me-2"></i>Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
