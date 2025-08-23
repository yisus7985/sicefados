@extends('avicontrol::layouts.admin')

@section('title', 'Detalle de Producción - ' . $galpon->name . ' - AVICONTROL')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-building text-primary mr-2"></i>
                        Detalle de Producción: {{ $galpon->name }}
                    </h1>
                    <p class="text-muted mb-0">Historial completo y estadísticas acumuladas de producción</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.welcome') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.information.index') }}">Información</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.information.produccion') }}">Producción</a></li>
                        <li class="breadcrumb-item active">{{ $galpon->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Información del Galpón -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="text-white mb-2">{{ $galpon->name }}</h3>
                                    <p class="text-white-50 mb-1">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        {{ $galpon->location ?? 'Ubicación no especificada' }}
                                    </p>
                                    <p class="text-white-50 mb-0">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        {{ $galpon->description ?? 'Sin descripción' }}
                                    </p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="text-white mb-1">{{ number_format($galpon->capacity ?? 0) }}</h4>
                                            <small class="text-white-50">Capacidad Total</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-white mb-1">{{ number_format($estadisticasAcumuladas['dias_registrados']) }}</h4>
                                            <small class="text-white-50">Días Registrados</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Acumuladas -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasAcumuladas['total_produccion']) }}</h3>
                            <p>Total Producción Acumulada</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-egg"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasAcumuladas['total_huevos_buenos']) }}</h3>
                            <p>Total Huevos Buenos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasAcumuladas['promedio_diario']) }}</h3>
                            <p>Promedio Diario</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>${{ number_format($estadisticasAcumuladas['total_valor'], 0, ',', '.') }}</h3>
                            <p>Valor Total Acumulado</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas por Tipo de Huevo -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-layer-group mr-2"></i>
                                Producción Acumulada por Tipo
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border rounded p-3">
                                        <h5 class="text-success mb-1">{{ number_format($produccionPorTipo['tipo_a']) }}</h5>
                                        <small class="text-muted">Tipo A</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-3">
                                        <h5 class="text-warning mb-1">{{ number_format($produccionPorTipo['tipo_aa']) }}</h5>
                                        <small class="text-muted">Tipo AA</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-3">
                                        <h5 class="text-info mb-1">{{ number_format($produccionPorTipo['tipo_b']) }}</h5>
                                        <small class="text-muted">Tipo B</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center mt-3">
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <h5 class="text-secondary mb-1">{{ number_format($produccionPorTipo['tipo_c']) }}</h5>
                                        <small class="text-muted">Tipo C</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <h5 class="text-dark mb-1">{{ number_format($produccionPorTipo['tipo_d']) }}</h5>
                                        <small class="text-muted">Tipo D</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Resumen de Calidad Acumulada
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border rounded p-3 bg-success text-white">
                                        <h5 class="mb-1">{{ number_format($estadisticasAcumuladas['total_huevos_buenos']) }}</h5>
                                        <small>Buenos</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-3 bg-warning text-white">
                                        <h5 class="mb-1">{{ number_format($estadisticasAcumuladas['total_huevos_sucios']) }}</h5>
                                        <small>Sucios</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-3 bg-danger text-white">
                                        <h5 class="mb-1">{{ number_format($estadisticasAcumuladas['total_huevos_rotos']) }}</h5>
                                        <small>Rotos</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-center mt-3">
                                <div class="col-6">
                                    <div class="border rounded p-3 bg-info text-white">
                                        <h5 class="mb-1">{{ number_format($estadisticasAcumuladas['mejor_dia']) }}</h5>
                                        <small>Mejor Día</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3 bg-secondary text-white">
                                        <h5 class="mb-1">{{ number_format($estadisticasAcumuladas['peor_dia']) }}</h5>
                                        <small>Peor Día</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Producción -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>
                        Historial Completo de Producción
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm" id="exportar-excel">
                            <i class="fas fa-file-excel mr-2"></i>
                            Exportar Excel
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" id="exportar-pdf">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Exportar PDF
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabla-produccion-galpon">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Producción Total</th>
                                    <th>Huevos Buenos</th>
                                    <th>Huevos Rotos</th>
                                    <th>Huevos Sucios</th>
                                    <th>Porcentaje</th>
                                    <th>Peso Promedio</th>
                                    <th>Peso Total</th>
                                    <th>Valor Unidad</th>
                                    <th>Valor Total</th>
                                    <th>Destino</th>
                                    <th>Semana</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($produccionGalpon) > 0)
                                    @foreach($produccionGalpon as $produccion)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $produccion->fecha ? $produccion->fecha->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $produccion->tipo == 'A' ? 'success' : ($produccion->tipo == 'AA' ? 'warning' : 'secondary') }}">
                                                {{ $produccion->tipo }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ number_format($produccion->cantidad) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ number_format($produccion->cantidad - ($produccion->huevos_rotos ?? 0) - ($produccion->huevos_sucios ?? 0)) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">{{ number_format($produccion->huevos_rotos ?? 0) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ number_format($produccion->huevos_sucios ?? 0) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $avesEnGalpon = $produccion->galpon ? $produccion->galpon->capacity : 0;
                                                $porcentaje = $avesEnGalpon > 0 ? round(($produccion->cantidad / $avesEnGalpon) * 100, 2) : 0;
                                            @endphp
                                            <span class="badge bg-{{ $porcentaje > 90 ? 'success' : ($porcentaje > 80 ? 'warning' : 'danger') }}">
                                                {{ $porcentaje }}%
                                            </span>
                                        </td>
                                        <td>{{ number_format($produccion->peso_promedio ?? 0, 1) }} g</td>
                                        <td>{{ number_format($produccion->peso_total ?? 0, 1) }} kg</td>
                                        <td>${{ number_format($produccion->valor_unidad ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            <strong class="text-success">${{ number_format($produccion->valor_total ?? 0, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>{{ $produccion->destino ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $produccion->semana_produccion ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $produccion->estado == 'activo' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($produccion->estado ?? 'N/A') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="14" class="text-center text-muted">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No hay registros de producción para este galpón
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gráfica de Producción por Semana -->
            @if(count($produccionPorSemana) > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Producción Acumulada por Semana
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="grafica-produccion-semana" style="height: 400px;"></canvas>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Generar gráfica de producción por semana si hay datos
    @if(count($produccionPorSemana) > 0)
    generarGraficaSemana();
    @endif
    
    // Event listeners para exportación
    $('#exportar-excel').on('click', function() {
        exportarExcel();
    });
    
    $('#exportar-pdf').on('click', function() {
        exportarPdf();
    });
    
    function generarGraficaSemana() {
        const ctx = document.getElementById('grafica-produccion-semana').getContext('2d');
        
        const semanas = @json(array_keys($produccionPorSemana->toArray()));
        const datos = @json($produccionPorSemana->values());
        
        const producciones = datos.map(d => d.total);
        const huevosBuenos = datos.map(d => d.huevos_buenos);
        const huevosRotos = datos.map(d => d.huevos_rotos);
        const huevosSucios = datos.map(d => d.huevos_sucios);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: semanas,
                datasets: [
                    {
                        label: 'Total Producción',
                        data: producciones,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Huevos Buenos',
                        data: huevosBuenos,
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Huevos Rotos',
                        data: huevosRotos,
                        backgroundColor: 'rgba(220, 53, 69, 0.8)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Huevos Sucios',
                        data: huevosSucios,
                        backgroundColor: 'rgba(255, 193, 7, 0.8)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    function exportarExcel() {
        // Implementar exportación a Excel
        alert('Función de exportación a Excel en desarrollo');
    }
    
    function exportarPdf() {
        // Implementar exportación a PDF
        alert('Función de exportación a PDF en desarrollo');
    }
});
</script>
@endpush

@push('styles')
<style>
/* Estilos para la vista de detalle del galpón */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
    color: white;
}

.bg-gradient-primary .card-body {
    color: white;
}

.bg-gradient-primary .card-body h3,
.bg-gradient-primary .card-body h4,
.bg-gradient-primary .card-body p {
    color: white;
}

.bg-gradient-primary .card-body .text-white-50 {
    color: rgba(255, 255, 255, 0.7) !important;
}

/* Cards de estadísticas */
.small-box {
    margin-bottom: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.small-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.small-box .inner {
    padding: 15px;
}

.small-box .icon {
    font-size: 2.5rem;
    opacity: 0.8;
    transition: all 0.3s ease;
}

.small-box:hover .icon {
    transform: scale(1.05);
}

/* Tabla */
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    background: white;
}

.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
    color: #495057;
    padding: 12px 10px;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.table td {
    padding: 10px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
    font-size: 0.85rem;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: scale(1.005);
    transition: all 0.2s ease;
}

/* Cards */
.card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
    padding: 15px 20px;
}

.card-title {
    margin: 0;
    color: #495057;
    font-weight: 600;
    font-size: 1rem;
}

.card-body {
    padding: 20px;
}

/* Botones */
.btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 8px 16px;
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.15);
}

.btn-sm {
    margin-left: 5px;
    padding: 6px 12px;
    font-size: 0.8rem;
}

/* Tipos de huevo */
.border.rounded {
    transition: all 0.3s ease;
    border-width: 2px !important;
}

.border.rounded:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Resumen de calidad */
.bg-success, .bg-warning, .bg-danger, .bg-info, .bg-secondary {
    transition: all 0.3s ease;
}

.bg-success:hover, .bg-warning:hover, .bg-danger:hover, .bg-info:hover, .bg-secondary:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .content-wrapper {
        padding: 15px;
        margin-left: 0;
        max-width: 100%;
    }
    
    .small-box .inner {
        padding: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .btn-sm {
        margin-left: 5px;
        margin-bottom: 8px;
    }
    
    .col-md-3, .col-md-6, .col-md-2 {
        margin-bottom: 20px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush
@endsection
