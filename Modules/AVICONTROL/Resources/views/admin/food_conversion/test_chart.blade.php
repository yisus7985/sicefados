@extends('avicontrol::layouts.admin')

@section('title', 'Prueba de Gráficas')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line"></i>
            Prueba de Gráficas
        </h1>
        <a href="{{ route('avicontrol.admin.food_conversion.estadisticas') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Estadísticas
        </a>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Gráfica de Línea</h6>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="testLineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Gráfica de Barras</h6>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="testBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Gráfica de Dona</h6>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="testDoughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Gráfica de Área</h6>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="testAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Iniciando prueba de gráficas...');

    // Gráfica de Línea
    const lineCtx = document.getElementById('testLineChart');
    if (lineCtx) {
        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                datasets: [{
                    label: 'Conversión Alimenticia',
                    data: [2.1, 2.3, 1.9, 2.0, 2.2, 2.1],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Evolución Mensual de Conversión',
                        color: '#495057',
                        font: { size: 16 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#495057' }
                    },
                    x: {
                        ticks: { color: '#495057' }
                    }
                }
            }
        });
        console.log('✅ Gráfica de línea creada');
    }

    // Gráfica de Barras
    const barCtx = document.getElementById('testBarChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Galpón 1', 'Galpón 2', 'Galpón 3', 'Galpón 4'],
                datasets: [{
                    label: 'Conversión Promedio',
                    data: [2.1, 2.3, 1.9, 2.0],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: ['#28a745', '#007bff', '#ffc107', '#dc3545'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Conversión por Galpón',
                        color: '#495057',
                        font: { size: 16 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#495057' }
                    },
                    x: {
                        ticks: { color: '#495057' }
                    }
                }
            }
        });
        console.log('✅ Gráfica de barras creada');
    }

    // Gráfica de Dona
    const doughnutCtx = document.getElementById('testDoughnutChart');
    if (doughnutCtx) {
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Huevo', 'Carne', 'Mixto'],
                datasets: [{
                    data: [45, 35, 20],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribución por Tipo',
                        color: '#495057',
                        font: { size: 16 }
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        console.log('✅ Gráfica de dona creada');
    }

    // Gráfica de Área
    const areaCtx = document.getElementById('testAreaChart');
    if (areaCtx) {
        new Chart(areaCtx, {
            type: 'line',
            data: {
                labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'],
                datasets: [{
                    label: 'Eficiencia',
                    data: [85, 88, 92, 89],
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.3)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Eficiencia Semanal',
                        color: '#495057',
                        font: { size: 16 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { color: '#495057' }
                    },
                    x: {
                        ticks: { color: '#495057' }
                    }
                }
            }
        });
        console.log('✅ Gráfica de área creada');
    }

    console.log('🎉 Todas las gráficas de prueba creadas exitosamente');
});
</script>
@endpush

