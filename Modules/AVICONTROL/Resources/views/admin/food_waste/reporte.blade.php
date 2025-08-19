@extends('avicontrol::layouts.admin')

@section('title', 'Reporte de Control de Mermas')

@section('styles')
<style>
    .report-card {
        transition: transform 0.2s;
    }
    .report-card:hover {
        transform: translateY(-2px);
    }
    .metric-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #28a745;
    }
    .metric-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .text-primary {
        color: #495057 !important;
    }
    .border-left-danger {
        border-left: 4px solid #dc3545 !important;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }
    .btn-primary {
        background-color: #495057;
        border-color: #495057;
    }
    .btn-primary:hover {
        background-color: #343a40;
        border-color: #343a40;
    }
    .card-header {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6 !important;
    }
    .card-header h6 {
        color: #495057 !important;
    }
    .table th {
        background-color: #f8f9fa;
        color: #495057;
        border-color: #dee2e6;
    }
    .badge-caducidad { background-color: #dc3545; }
    .badge-manejo { background-color: #ffc107; color: #000; }
    .badge-almacenamiento { background-color: #17a2b8; }
    .badge-transporte { background-color: #6f42c1; }
    .badge-otras { background-color: #6c757d; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt" style="color: #495057;"></i>
            Reporte de Control de Mermas
        </h1>
        <div>
            <button type="button" class="btn btn-success me-2" onclick="exportarReporte()">
                <i class="fas fa-download"></i> Exportar
            </button>
            <a href="{{ route('avicontrol.admin.food_waste.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Filtros del Reporte</h6>
        </div>
        <div class="card-body">
            <form id="filtrosForm" class="row g-3">
                <div class="col-md-3">
                    <label for="galpon_id" class="form-label">Galpón</label>
                    <select class="form-select" id="galpon_id" name="galpon_id">
                        <option value="">Todos los galpones</option>
                        @if(isset($galpones) && $galpones->count() > 0)
                            @foreach($galpones as $galpon)
                                <option value="{{ $galpon->id }}">{{ $galpon->name }}</option>
                            @endforeach
                        @else
                            <option value="">No hay galpones disponibles</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                </div>
                <div class="col-md-3">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                </div>
                <div class="col-md-3">
                    <label for="causa_merma" class="form-label">Causa de Merma</label>
                    <select class="form-select" id="causa_merma" name="causa_merma">
                        <option value="">Todas las causas</option>
                        @if(isset($causasMerma))
                            @foreach($causasMerma as $key => $causa)
                                <option value="{{ $key }}">{{ $causa }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Generar Reporte
                    </button>
                    <button type="button" class="btn btn-outline-secondary ms-2" onclick="limpiarFiltros()">
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen Ejecutivo -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 report-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #dc3545;">
                                Total Mermas
                            </div>
                            <div class="metric-value" id="totalMermasReporte">-</div>
                            <div class="metric-label">Kilogramos</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 report-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #ffc107;">
                                Costo Total
                            </div>
                            <div class="metric-value" id="costoTotalReporte">-</div>
                            <div class="metric-label">Dólares</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 report-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #17a2b8;">
                                Total Registros
                            </div>
                            <div class="metric-value" id="totalRegistrosReporte">-</div>
                            <div class="metric-label">Entradas</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 report-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #495057;">
                                Promedio Diario
                            </div>
                            <div class="metric-value" id="promedioDiarioReporte">-</div>
                            <div class="metric-label">kg/día</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Análisis por Causa -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Mermas por Causa</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Causa</th>
                                    <th>Cantidad (kg)</th>
                                    <th>Porcentaje</th>
                                    <th>Costo</th>
                                </tr>
                            </thead>
                            <tbody id="tablaCausas">
                                <!-- Se llenará dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Mermas por Galpón</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Galpón</th>
                                    <th>Cantidad (kg)</th>
                                    <th>Porcentaje</th>
                                    <th>Registros</th>
                                </tr>
                            </thead>
                            <tbody id="tablaGalpones">
                                <!-- Se llenará dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Detallada -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Detalle de Mermas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tablaDetalle">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Galpón</th>
                            <th>Producto</th>
                            <th>Cantidad (kg)</th>
                            <th>Causa</th>
                            <th>Costo</th>
                            <th>Responsable</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaDetalleBody">
                        @if(isset($mermas) && $mermas->count() > 0)
                            @foreach($mermas as $merma)
                                <tr>
                                    <td>{{ $merma->fecha_registro ? \Carbon\Carbon::parse($merma->fecha_registro)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $merma->galpon ? $merma->galpon->name : 'N/A' }}</td>
                                    <td>{{ $merma->producto ? $merma->producto->name : 'N/A' }}</td>
                                    <td>{{ number_format($merma->cantidad_perdida, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ strtolower(str_replace(' ', '-', $merma->causa_merma)) }}">
                                            {{ $merma->causa_merma }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($merma->costo_perdida ?? 0, 2) }}</td>
                                    <td>{{ $merma->responsable ?? 'N/A' }}</td>
                                    <td>{{ $merma->observaciones ?? 'Sin observaciones' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center text-muted">
                                    <i class="fas fa-info-circle"></i> No hay datos de mermas para mostrar
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recomendaciones -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-lightbulb text-warning"></i>
                Recomendaciones y Acciones Correctivas
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-danger">🔴 Acciones Inmediatas:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Revisar procedimientos de almacenamiento</li>
                        <li><i class="fas fa-check text-success"></i> Capacitar personal en manejo de alimentos</li>
                        <li><i class="fas fa-check text-success"></i> Implementar controles de temperatura</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-warning">🟡 Acciones a Mediano Plazo:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-clock text-warning"></i> Establecer métricas de seguimiento</li>
                        <li><i class="fas fa-clock text-warning"></i> Implementar sistema de alertas</li>
                        <li><i class="fas fa-clock text-warning"></i> Revisar proveedores de alimentos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
console.log('🚀 SCRIPT CARGADO - REPORTE DE MERMAS');

// Función para limpiar filtros
function limpiarFiltros() {
    document.getElementById('galpon_id').value = '';
    document.getElementById('fecha_inicio').value = '';
    document.getElementById('fecha_fin').value = '';
    document.getElementById('causa_merma').value = '';
    
    // Recargar la página para mostrar todos los datos
    window.location.reload();
}

// Función para exportar reporte
function exportarReporte() {
    console.log('📊 Exportando reporte de mermas...');
    
    // Obtener valores de los filtros
    const galponId = document.getElementById('galpon_id').value;
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const causaMerma = document.getElementById('causa_merma').value;
    
    // Construir URL de exportación
    let url = `{{ route('avicontrol.admin.food_waste.exportar') }}?`;
    if (galponId) url += `galpon_id=${galponId}&`;
    if (fechaInicio) url += `fecha_inicio=${fechaInicio}&`;
    if (fechaFin) url += `fecha_fin=${fechaFin}&`;
    if (causaMerma) url += `causa_merma=${causaMerma}&`;
    
    // Hacer petición de exportación
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('✅ Reporte exportado:', data);
        alert('Reporte exportado exitosamente');
    })
    .catch(error => {
        console.error('❌ Error exportando reporte:', error);
        alert('Error al exportar el reporte');
    });
}

// Función para actualizar métricas del reporte
function actualizarMetricasReporte() {
    // Aquí podrías hacer una petición AJAX para obtener métricas actualizadas
    // Por ahora usamos los datos que ya están en la vista
    
    console.log('📊 Actualizando métricas del reporte...');
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM cargado, configurando reporte de mermas...');
    
    // Configurar formulario de filtros
    const form = document.getElementById('filtrosForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('🔄 Generando reporte con filtros...');
            
            // Aquí podrías hacer una petición AJAX para actualizar el reporte
            // Por ahora recargamos la página con los filtros
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            window.location.href = window.location.pathname + '?' + params.toString();
        });
    }
    
    // Actualizar métricas
    actualizarMetricasReporte();
});

console.log('✅ SCRIPT DE REPORTE COMPLETAMENTE CARGADO');
</script>
@endpush

