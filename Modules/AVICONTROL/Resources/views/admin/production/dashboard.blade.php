@extends('avicontrol::layouts.admin')

@section('title', 'Dashboard de Producción')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">
                    <i class="fas fa-chart-line text-primary"></i>
                    Dashboard de Producción
                </h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.welcome') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.production.index') }}">Producción</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('avicontrol.admin.production.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
                <a href="{{ route('avicontrol.admin.production.report') }}" class="btn btn-success">
                    <i class="fas fa-file-pdf"></i> Generar Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon primary">
                        <i class="fas fa-egg"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalStats['total_eggs'] ?? 0) }}</h3>
                        <p>Total Huevos (30 días)</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon success">
                        <i class="fas fa-weight-hanging"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalStats['total_weight'] ?? 0, 2) }} kg</h3>
                        <p>Peso Total</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon warning">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalStats['total_productions'] ?? 0 }}</h3>
                        <p>Días Registrados</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-icon info">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalStats['avg_weight'] ?? 0, 1) }} g</h3>
                        <p>Peso Promedio</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico de Tendencia -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-area text-primary"></i> Tendencia de Producción (Últimos 30 días)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="productionTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Producción por Instalación -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-warehouse text-success"></i> Producción por Instalación
                    </h5>
                </div>
                <div class="card-body">
                    @if($facilityStats->count() > 0)
                        <div class="chart-container" style="position: relative; height: 300px;">
                            <canvas id="facilityChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No hay datos disponibles</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Producción Mensual -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt text-info"></i> Producción del Mes Actual
                    </h5>
                </div>
                <div class="card-body">
                    @if($currentMonthProduction->count() > 0)
                        <div class="chart-container" style="position: relative; height: 300px;">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No hay producción registrada este mes</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Instalaciones -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table text-secondary"></i> Resumen por Instalación
                    </h5>
                </div>
                <div class="card-body">
                    @if($facilityStats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Instalación</th>
                                        <th>Total Huevos</th>
                                        <th>Peso Promedio</th>
                                        <th>Días Registrados</th>
                                        <th>Promedio Diario</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($facilityStats as $facility)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $facility->name }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">{{ number_format($facility->total_eggs) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-info">{{ number_format($facility->avg_weight, 1) }} g</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $facility->total_days }}</span>
                                            </td>
                                            <td>
                                                <span class="text-primary">{{ number_format($facility->total_eggs / $facility->total_days, 0) }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('avicontrol.admin.production.index', ['facility_id' => $facility->id]) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-table fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No hay datos de instalaciones disponibles</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Gráfico de tendencia de producción
    const trendCtx = document.getElementById('productionTrendChart').getContext('2d');
    const productionTrendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($productionTrend->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('d/m'); })->toArray()) !!},
            datasets: [{
                label: 'Producción Diaria (Huevos)',
                data: {!! json_encode($productionTrend->pluck('total_eggs')->toArray()) !!},
                backgroundColor: 'rgba(77, 124, 15, 0.1)',
                borderColor: 'rgba(77, 124, 15, 1)',
                pointBackgroundColor: 'rgba(77, 124, 15, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(77, 124, 15, 1)',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        maxTicksLimit: 5
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    @if($facilityStats->count() > 0)
    // Gráfico de producción por instalación
    const facilityCtx = document.getElementById('facilityChart').getContext('2d');
    const facilityChart = new Chart(facilityCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($facilityStats->pluck('name')->toArray()) !!},
            datasets: [{
                data: {!! json_encode($facilityStats->pluck('total_eggs')->toArray()) !!},
                backgroundColor: [
                    'rgba(77, 124, 15, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(180, 83, 9, 0.8)'
                ],
                borderColor: [
                    'rgba(77, 124, 15, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(239, 68, 68, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(180, 83, 9, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
    @endif

    @if($currentMonthProduction->count() > 0)
    // Gráfico de producción mensual
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($currentMonthProduction->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('d/m'); })->toArray()) !!},
            datasets: [{
                label: 'Producción Diaria',
                data: {!! json_encode($currentMonthProduction->pluck('total_eggs')->toArray()) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        maxTicksLimit: 5
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush
@endsection
