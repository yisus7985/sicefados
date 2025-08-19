@extends('avicontrol::layouts.admin')

@section('title', 'Estadísticas de Control de Mermas')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">
<style>
    .stats-card {
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .chart-container {
        position: relative;
        height: 400px;
        margin: 20px 0;
    }
    .metric-value {
        font-size: 2rem;
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
    .border-left-primary {
        border-left: 4px solid #495057 !important;
    }
    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
    .border-left-danger {
        border-left: 4px solid #dc3545 !important;
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-pie" style="color: #495057;"></i>
            Estadísticas de Control de Mermas
        </h1>
        <div class="btn-group">
            <a href="{{ route('avicontrol.admin.food_waste.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Métricas Principales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 stats-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #dc3545;">
                                Total Mermas
                            </div>
                            <div class="metric-value" id="totalMermas">125.5 kg</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 stats-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #ffc107;">
                                Costo Total
                            </div>
                            <div class="metric-value" id="costoTotal">$2,450.00</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 stats-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #17a2b8;">
                                % Merma
                            </div>
                            <div class="metric-value" id="porcentajeMerma">3.2%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 stats-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #28a745;">
                                Registros
                            </div>
                            <div class="metric-value" id="totalRegistros">47</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="row">
        <!-- Gráfica de Causas de Merma -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Causas de Merma
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="causaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica por Galpón -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-warehouse me-2"></i>
                        Mermas por Galpón
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="galponChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Evolución Temporal -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>
                        Evolución de Mermas (Últimos 30 días)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="evolucionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mermas por Producto -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box me-2"></i>
                        Mermas por Producto
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="productoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Sistema -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Información del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Chart.js:</strong> <span id="chartjsStatus" class="badge bg-success">Cargado</span></p>
                            <p><strong>Vista:</strong> <span class="badge bg-info">estadisticas_independiente.blade.php</span></p>
                            <p><strong>Datos:</strong> <span class="badge bg-warning">Simulados</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha:</strong> <span id="fechaActual"></span></p>
                            <p><strong>Hora:</strong> <span id="horaActual"></span></p>
                            <p><strong>Estado:</strong> <span class="badge bg-success">Funcionando</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar fecha y hora
    function actualizarTiempo() {
        const ahora = new Date();
        document.getElementById('fechaActual').textContent = ahora.toLocaleDateString('es-ES');
        document.getElementById('horaActual').textContent = ahora.toLocaleTimeString('es-ES');
    }
    
    actualizarTiempo();
    setInterval(actualizarTiempo, 1000);

    // Verificar si Chart.js está cargado
    if (typeof Chart === 'undefined') {
        document.getElementById('chartjsStatus').textContent = 'Error';
        document.getElementById('chartjsStatus').className = 'badge bg-danger';
        console.error('Chart.js no está cargado');
        return;
    }

    // Datos simulados para las gráficas
    const datosSimulados = {
        causas: {
            labels: ['Derrame', 'Contaminación', 'Roedores', 'Humedad', 'Caducidad', 'Transporte'],
            data: [25, 20, 15, 18, 12, 10]
        },
        galpones: {
            labels: ['Galpón A', 'Galpón B', 'Galpón C', 'Galpón D'],
            data: [35, 28, 42, 20]
        },
        evolucion: {
            labels: Array.from({length: 30}, (_, i) => `${i + 1}/08`),
            data: Array.from({length: 30}, () => Math.floor(Math.random() * 20) + 5)
        },
        productos: {
            labels: ['Alimento A', 'Alimento B', 'Alimento C', 'Alimento D'],
            data: [30, 25, 35, 10]
        }
    };

    // Gráfica de Causas (Dona)
    const causaChart = new Chart(document.getElementById('causaChart'), {
        type: 'doughnut',
        data: {
            labels: datosSimulados.causas.labels,
            datasets: [{
                data: datosSimulados.causas.data,
                backgroundColor: [
                    '#dc3545', '#ffc107', '#6c757d', '#17a2b8', '#fd7e14', '#6f42c1'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Gráfica por Galpón (Barras)
    const galponChart = new Chart(document.getElementById('galponChart'), {
        type: 'bar',
        data: {
            labels: datosSimulados.galpones.labels,
            datasets: [{
                label: 'Kg de Merma',
                data: datosSimulados.galpones.data,
                backgroundColor: '#495057',
                borderColor: '#343a40',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Kilogramos'
                    }
                }
            }
        }
    });

    // Gráfica de Evolución (Línea)
    const evolucionChart = new Chart(document.getElementById('evolucionChart'), {
        type: 'line',
        data: {
            labels: datosSimulados.evolucion.labels,
            datasets: [{
                label: 'Kg de Merma',
                data: datosSimulados.evolucion.data,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Kilogramos'
                    }
                }
            }
        }
    });

    // Gráfica por Producto (Barras horizontales)
    const productoChart = new Chart(document.getElementById('productoChart'), {
        type: 'bar',
        data: {
            labels: datosSimulados.productos.labels,
            datasets: [{
                label: 'Kg de Merma',
                data: datosSimulados.productos.data,
                backgroundColor: '#28a745',
                borderColor: '#20c997',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Kilogramos'
                    }
                }
            }
        }
    });

    console.log('Todas las gráficas han sido inicializadas correctamente');
});
</script>
@endsection
