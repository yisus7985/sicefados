@extends('avicontrol::layouts.admin')

@section('title', 'Prueba de Gráficas - Mermas')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-pie"></i>
            Prueba de Gráficas - Mermas
        </h1>
        <a href="{{ route('avicontrol.admin.food_waste.estadisticas') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Estadísticas
        </a>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Mermas por Causa</h6>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="testCausaChart"></canvas>
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
                    <div style="height: 400px;">
                        <canvas id="testGalponChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Evolución Temporal</h6>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="testEvolucionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Mermas por Producto</h6>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="testProductoChart"></canvas>
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
    console.log('🚀 Iniciando prueba de gráficas de mermas...');

    // Gráfica de Mermas por Causa
    const causaCtx = document.getElementById('testCausaChart');
    if (causaCtx) {
        new Chart(causaCtx, {
            type: 'doughnut',
            data: {
                labels: ['Caducidad', 'Contaminación', 'Manejo', 'Otros'],
                datasets: [{
                    data: [45.2, 32.8, 28.5, 19.0],
                    backgroundColor: ['#dc3545', '#ffc107', '#17a2b8', '#6f42c1'],
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
                        text: 'Distribución de Mermas por Causa',
                        color: '#495057',
                        font: { size: 16 }
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        console.log('✅ Gráfica de causa creada');
    }

    // Gráfica de Mermas por Galpón
    const galponCtx = document.getElementById('testGalponChart');
    if (galponCtx) {
        new Chart(galponCtx, {
            type: 'bar',
            data: {
                labels: ['Galpón 1', 'Galpón 2', 'Galpón 3', 'Galpón 4'],
                datasets: [{
                    label: 'Cantidad de Mermas (kg)',
                    data: [35.2, 28.8, 42.5, 19.0],
                    backgroundColor: 'rgba(220, 53, 69, 0.8)',
                    borderColor: '#dc3545',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Mermas por Galpón',
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
        console.log('✅ Gráfica de galpón creada');
    }

    // Gráfica de Evolución Temporal
    const evolucionCtx = document.getElementById('testEvolucionChart');
    if (evolucionCtx) {
        new Chart(evolucionCtx, {
            type: 'line',
            data: {
                labels: ['Ene 2025', 'Feb 2025', 'Mar 2025', 'Abr 2025', 'May 2025', 'Jun 2025'],
                datasets: [{
                    label: 'Cantidad de Mermas (kg)',
                    data: [15.2, 18.8, 22.5, 19.0, 25.3, 24.7],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
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
                        text: 'Evolución Temporal de Mermas',
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
        console.log('✅ Gráfica de evolución creada');
    }

    // Gráfica de Mermas por Producto
    const productoCtx = document.getElementById('testProductoChart');
    if (productoCtx) {
        new Chart(productoCtx, {
            type: 'bar',
            data: {
                labels: ['Alimento A', 'Alimento B', 'Alimento C'],
                datasets: [{
                    label: 'Cantidad de Mermas (kg)',
                    data: [45.2, 38.8, 41.5],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)'
                    ],
                    borderColor: ['#ff6384', '#36a2eb', '#ffce56'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Mermas por Producto',
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
        console.log('✅ Gráfica de producto creada');
    }

    console.log('🎉 Todas las gráficas de prueba de mermas creadas exitosamente');
});
</script>
@endpush
