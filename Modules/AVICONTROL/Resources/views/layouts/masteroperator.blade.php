@extends('avicontrol::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1"><i class="fas fa-feather-alt me-2"></i>Panel de Administración</h2>
                            <p class="mb-0">Bienvenido al sistema de gestión avícola AVICONTROL</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-0">Fecha: {{ date('d/m/Y') }}</p>
                            <p class="mb-0">Usuario: {{ Auth::user()->nickname }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Galpones</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Producción Diaria</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">2,500 huevos</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-egg fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tasa de Postura</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">85%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 85%"
                                            aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Alertas Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Tablas -->
    <div class="row">
        <!-- Gráfico de Producción -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Resumen de Producción</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Opciones:</div>
                            <a class="dropdown-item" href="#">Ver Detalles</a>
                            <a class="dropdown-item" href="#">Exportar Datos</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Configurar Alertas</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="productionChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Distribución -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Distribución por Tipo</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Opciones:</div>
                            <a class="dropdown-item" href="#">Ver Detalles</a>
                            <a class="dropdown-item" href="#">Exportar Datos</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="distributionChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Módulos del Sistema -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Módulos del Sistema</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-warehouse fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Gestión de Galpones</h5>
                                    <p class="card-text">Administre sus galpones, capacidad y condiciones.</p>
                                    <a href="#" class="btn btn-primary">Acceder</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-egg fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">Producción</h5>
                                    <p class="card-text">Registre y analice la producción diaria de huevos.</p>
                                    <a href="#" class="btn btn-success">Acceder</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-utensils fa-3x text-info mb-3"></i>
                                    <h5 class="card-title">Alimentación</h5>
                                    <p class="card-text">Gestione el consumo y stock de alimentos.</p>
                                    <a href="#" class="btn btn-info">Acceder</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card text-center h-100">
                                <div class="card-body">
                                    <i class="fas fa-heartbeat fa-3x text-danger mb-3"></i>
                                    <h5 class="card-title">Salud y Bioseguridad</h5>
                                    <p class="card-text">Controle vacunaciones y protocolos sanitarios.</p>
                                    <a href="#" class="btn btn-danger">Acceder</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividades Recientes y Tareas Pendientes -->
    <div class="row">
        <!-- Actividades Recientes -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actividades Recientes</h6>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        <div class="feed-item">
                            <div class="date">Hoy, 10:30 AM</div>
                            <div class="text"><strong>Juan Pérez</strong> registró la producción del Galpón #3</div>
                        </div>
                        <div class="feed-item">
                            <div class="date">Hoy, 09:15 AM</div>
                            <div class="text"><strong>María López</strong> actualizó el inventario de alimentos</div>
                        </div>
                        <div class="feed-item">
                            <div class="date">Ayer, 04:30 PM</div>
                            <div class="text"><strong>Carlos Rodríguez</strong> reportó un problema en el Galpón #5</div>
                        </div>
                        <div class="feed-item">
                            <div class="date">Ayer, 02:00 PM</div>
                            <div class="text"><strong>Ana Martínez</strong> completó la vacunación programada</div>
                        </div>
                        <div class="feed-item">
                            <div class="date">15/04/2023, 11:45 AM</div>
                            <div class="text"><strong>Sistema</strong> generó alerta por baja en la producción</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tareas Pendientes -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tareas Pendientes</h6>
                </div>
                <div class="card-body">
                    <div class="todo-list">
                        <div class="todo-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="task1">
                                <label class="form-check-label" for="task1">
                                    Revisar inventario de alimentos
                                </label>
                            </div>
                            <span class="badge bg-warning text-dark">Hoy</span>
                        </div>
                        <div class="todo-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="task2">
                                <label class="form-check-label" for="task2">
                                    Programar vacunación para el Galpón #2
                                </label>
                            </div>
                            <span class="badge bg-danger">Urgente</span>
                        </div>
                        <div class="todo-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="task3">
                                <label class="form-check-label" for="task3">
                                    Generar informe mensual de producción
                                </label>
                            </div>
                            <span class="badge bg-info">Esta semana</span>
                        </div>
                        <div class="todo-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="task4">
                                <label class="form-check-label" for="task4">
                                    Mantenimiento de equipos de ventilación
                                </label>
                            </div>
                            <span class="badge bg-primary">Programado</span>
                        </div>
                        <div class="todo-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="task5">
                                <label class="form-check-label" for="task5">
                                    Actualizar protocolos de bioseguridad
                                </label>
                            </div>
                            <span class="badge bg-secondary">Pendiente</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos adicionales para el dashboard -->
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .activity-feed {
        padding: 15px;
    }
    .feed-item {
        padding-bottom: 15px;
        margin-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }
    .feed-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .feed-item .date {
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .todo-list {
        padding: 15px;
    }
    .todo-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 12px;
        margin-bottom: 12px;
        border-bottom: 1px solid #e9ecef;
    }
    .todo-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
</style>

<!-- Scripts para los gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Producción
    var productionCtx = document.getElementById('productionChart').getContext('2d');
    var productionChart = new Chart(productionCtx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Producción Diaria (Huevos)',
                data: [2300, 2450, 2380, 2500, 2600, 2400, 2350],
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 2000,
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

    // Gráfico de Distribución
    var distributionCtx = document.getElementById('distributionChart').getContext('2d');
    var distributionChart = new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tipo A', 'Tipo B', 'Tipo C', 'Defectuosos'],
            datasets: [{
                data: [55, 30, 10, 5],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '70%'
        }
    });
});
</script>
@endsection