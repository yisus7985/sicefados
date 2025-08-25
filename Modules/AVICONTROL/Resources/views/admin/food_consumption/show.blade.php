@extends('avicontrol::layouts.admin')

@section('title', 'Detalles del Consumo de Alimento')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-eye text-primary me-3"></i>
                    Detalles del Consumo de Alimento
                </h2>
                <p class="text-muted mb-0">Información completa del registro de consumo</p>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <a href="{{ route('avicontrol.admin.food_consumption.edit', $consumo->id) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('avicontrol.admin.food_consumption.index') }}" 
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Información Principal -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Información del Consumo
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Fecha de Registro</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-calendar me-2 text-primary"></i>
                                    {{ $consumo->fecha_registro->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Estado</label>
                                <p class="form-control-plaintext">
                                    @if($consumo->estado === 'active')
                                        <span class="badge bg-success fs-6">
                                            <i class="fas fa-check-circle me-1"></i>Activo
                                        </span>
                                    @elseif($consumo->estado === 'cancelled')
                                        <span class="badge bg-danger fs-6">
                                            <i class="fas fa-times-circle me-1"></i>Cancelado
                                        </span>
                                    @else
                                        <span class="badge bg-warning fs-6">
                                            <i class="fas fa-clock me-1"></i>Pendiente
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Galpón</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-warehouse me-2 text-primary"></i>
                                    {{ $consumo->galpon->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Producto</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-box me-2 text-info"></i>
                                    {{ $consumo->producto->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Número de Aves</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-kiwi-bird me-2 text-success"></i>
                                    {{ number_format($consumo->numero_aves) }} aves
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Responsable</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-user me-2 text-secondary"></i>
                                    {{ $consumo->responsable ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($consumo->observaciones)
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Observaciones</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-comment me-2 text-info"></i>
                                    {{ $consumo->observaciones }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estadísticas y Métricas -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar me-2"></i>
                        Métricas del Consumo
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-success mb-1">
                                    {{ number_format($consumo->getTotalConsumoAttribute(), 2) }}
                                </h4>
                                <small class="text-muted">Total Consumido (kg)</small>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-warning mb-1">
                                    {{ number_format($consumo->getConsumoPorAveAttribute(), 2) }}
                                </h4>
                                <small class="text-muted">Consumo por Ave (g)</small>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-info mb-1">
                                    {{ number_format($consumo->cantidad_bultos ?? 0, 2) }}
                                </h4>
                                <small class="text-muted">Bultos Utilizados</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-center p-3 bg-light rounded">
                                <h4 class="text-primary mb-1">
                                    {{ number_format($consumo->peso_por_bulto ?? 0, 2) }}
                                </h4>
                                <small class="text-muted">Peso por Bulto (kg)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles Adicionales -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list me-2"></i>
                        Detalles Adicionales
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Cantidad en Kilogramos</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-weight-hanging me-2 text-success"></i>
                                    {{ number_format($consumo->cantidad_kg ?? 0, 2) }} kg
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Cantidad de Bultos</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-boxes me-2 text-info"></i>
                                    {{ number_format($consumo->cantidad_bultos ?? 0, 2) }} bultos
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Peso por Bulto</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-balance-scale me-2 text-warning"></i>
                                    {{ number_format($consumo->peso_por_bulto ?? 0, 2) }} kg
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Fecha de Creación</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-calendar-plus me-2 text-muted"></i>
                                    {{ $consumo->created_at->format('d/m/Y H:i:s') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-muted">Última Actualización</label>
                                <p class="form-control-plaintext">
                                    <i class="fas fa-calendar-check me-2 text-muted"></i>
                                    {{ $consumo->updated_at->format('d/m/Y H:i:s') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Cambios (si existe) -->
    @if(isset($consumo->audits) && $consumo->audits->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history me-2"></i>
                        Historial de Cambios
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Cambios</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consumo->audits as $audit)
                                <tr>
                                    <td>{{ $audit->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $audit->user->name ?? 'Sistema' }}</td>
                                    <td>
                                        @if($audit->event === 'created')
                                            <span class="badge bg-success">Creado</span>
                                        @elseif($audit->event === 'updated')
                                            <span class="badge bg-warning">Actualizado</span>
                                        @elseif($audit->event === 'deleted')
                                            <span class="badge bg-danger">Eliminado</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($audit->event === 'updated')
                                            <small class="text-muted">
                                                @foreach($audit->getModified() as $field => $values)
                                                    <strong>{{ $field }}:</strong> 
                                                    {{ $values['old'] ?? 'N/A' }} → {{ $values['new'] ?? 'N/A' }}<br>
                                                @endforeach
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.page-header {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 25px;
}

.page-title {
    color: #495057;
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.page-subtitle {
    color: #6c757d;
    margin: 10px 0 0 0;
    font-size: 1rem;
}

.form-control-plaintext {
    padding: 0.5rem 0;
    margin-bottom: 0;
    color: #495057;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.badge {
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .page-header {
        padding: 15px;
    }
    
    .page-title {
        font-size: 1.25rem;
        text-align: center;
    }
    
    .page-subtitle {
        text-align: center;
        font-size: 0.9rem;
    }
    
    .btn-group {
        width: 100%;
        margin-top: 15px;
    }
    
    .btn-group .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endpush
