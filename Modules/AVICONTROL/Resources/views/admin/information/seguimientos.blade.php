@extends('avicontrol::layouts.admin')

@section('title', 'Seguimientos de Galpón - AVICONTROL')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-chart-line text-primary me-3"></i>
                    Seguimientos de Galpón
                </h2>
                <p class="text-muted mb-0">Sistema de monitoreo y análisis de crecimiento avícola</p>
            </div>
        </div>
    </div>
            

            <!-- Resumen de Seguimientos -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasSeguimientos['total_aves_seguimiento'] ?? 0) }}</h3>
                            <p>Total Aves en Seguimiento</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-kiwi-bird"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasSeguimientos['promedio_peso'] ?? 0, 0) }} g</h3>
                            <p>Peso Promedio Actual</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-weight-hanging"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasSeguimientos['edad_promedio'] ?? 0, 0) }} días</h3>
                            <p>Edad Promedio</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasSeguimientos['tasa_crecimiento'] ?? 0, 1) }}%</h3>
                            <p>Tasa de Crecimiento</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Adicionales -->
            @if(isset($estadisticasSeguimientos['aves_por_galpon']) && count($estadisticasSeguimientos['aves_por_galpon']) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>
                                Aves por Galpón
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($estadisticasSeguimientos['aves_por_galpon'] as $galpon)
                                <div class="col-md-3 mb-3">
                                    <div class="text-center">
                                        <h6 class="text-primary">{{ $galpon['nombre'] }}</h6>
                                        <h4 class="text-success">{{ number_format($galpon['total_aves']) }}</h4>
                                        <small class="text-muted">Aves activas</small>
                                        <div class="mt-2">
                                            <span class="badge bg-info">
                                                {{ number_format($galpon['peso_promedio'], 0) }} g promedio
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Gráfica Principal de Crecimiento -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-area mr-2"></i>
                        Curva de Crecimiento por Galpón
                    </h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-tipo="peso">Peso</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary active" data-tipo="crecimiento">Crecimiento</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-tipo="comparacion">Comparación</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="grafica-crecimiento" style="height: 500px;"></canvas>
                </div>
            </div>

            <!-- Gráficas Comparativas -->
            <div class="row">
                <!-- Gráfica de Peso por Edad -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Peso por Edad (Días)
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="grafica-peso-edad" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de Distribución de Pesos -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Distribución de Pesos Actual
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="grafica-distribucion-pesos" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Seguimientos Detallados -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-2"></i>
                        Registros de Seguimiento Detallado
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabla-seguimientos">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Galpón</th>
                                    <th>Lote</th>
                                    <th>Edad (días)</th>
                                    <th>Peso Promedio (g)</th>
                                    <th>Peso Mínimo (g)</th>
                                    <th>Peso Máximo (g)</th>
                                    <th>Desviación Estándar</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($datosSeguimientos) && count($datosSeguimientos) > 0)
                                    @foreach($datosSeguimientos as $seguimiento)
                                    <tr>
                                        <td>{{ $seguimiento['fecha'] ? $seguimiento['fecha']->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $seguimiento['galpon'] }}</td>
                                        <td>{{ $seguimiento['lote'] }}</td>
                                        <td>{{ $seguimiento['edad'] }}</td>
                                        <td>{{ number_format($seguimiento['peso_promedio'], 0) }}</td>
                                        <td>{{ number_format($seguimiento['peso_minimo'], 0) }}</td>
                                        <td>{{ number_format($seguimiento['peso_maximo'], 0) }}</td>
                                        <td>{{ number_format($seguimiento['desviacion'], 0) }}</td>
                                        <td>{{ $seguimiento['observaciones'] }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No hay datos de seguimientos disponibles
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Análisis de Tendencias -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-2"></i>
                        Análisis de Tendencias y Proyecciones
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Proyección de Crecimiento</h5>
                            <p>Basado en los datos históricos, se proyecta que las aves alcancen un peso promedio de <strong id="peso-proyectado">0 g</strong> a los <strong id="dias-proyeccion">0 días</strong>.</p>
                            <div class="progress">
                                <div class="progress-bar bg-success" id="barra-progreso" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Recomendaciones</h5>
                            <ul id="recomendaciones">
                                <li>Analizando datos de crecimiento...</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de Distribución de Pesos -->
            @if(isset($estadisticasSeguimientos['distribucion_pesos']) && count($estadisticasSeguimientos['distribucion_pesos']) > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2 text-info"></i>
                        Distribución de Pesos por Rango
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $rangos = ['0-1000g', '1000-1500g', '1500-2000g', '2000-2500g', '2500g+'];
                            $distribucion = $estadisticasSeguimientos['distribucion_pesos'];
                        @endphp
                        @foreach($rangos as $index => $rango)
                        <div class="col-md-2 mb-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="text-info">{{ $rango }}</h6>
                                    <h4 class="text-info">{{ number_format($distribucion[$index] ?? 0) }}</h4>
                                    <small class="text-muted">Aves</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Cargar datos iniciales desde el servidor
    cargarDatosIniciales();
    
    // Se removieron filtros y eventos asociados
    
    // Cambio de tipo de gráfica
    $('.btn-group .btn').on('click', function() {
        $('.btn-group .btn').removeClass('active');
        $(this).addClass('active');
        actualizarGraficaPrincipal();
    });
    
    function cargarDatosIniciales() {
        // Cargar datos iniciales desde el servidor
        $.ajax({
            url: '{{ route("avicontrol.admin.information.seguimientos") }}',
            method: 'GET',
            data: { ajax: true, action: 'datos_iniciales' },
            success: function(response) {
                if (response.success) {
                    mostrarDatos(response.datos);
                    actualizarResumen(response.datos);
                    generarGraficas(response.datos);
                    actualizarAnalisis(response.datos);
                }
            },
            error: function() {
                console.error('Error cargando datos iniciales');
            }
        });
    }
    
    function mostrarDatos(datos) {
        if (!datos || datos.length === 0) {
            $('#tabla-seguimientos tbody').html(`
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay datos disponibles para los filtros seleccionados
                    </td>
                </tr>
            `);
            return;
        }
        
        let html = '';
        datos.forEach(dato => {
            html += `
                <tr>
                    <td>${formatearFecha(dato.fecha)}</td>
                    <td>${dato.galpon}</td>
                    <td>${dato.lote}</td>
                    <td>${dato.edad}</td>
                    <td>${dato.peso_promedio.toLocaleString()}</td>
                    <td>${dato.peso_minimo.toLocaleString()}</td>
                    <td>${dato.peso_maximo.toLocaleString()}</td>
                    <td>${dato.desviacion.toLocaleString()}</td>
                    <td>${dato.observaciones}</td>
                </tr>
            `;
        });
        $('#tabla-seguimientos tbody').html(html);
    }
    
    function mostrarError(mensaje) {
        $('#tabla-seguimientos tbody').html(`
            <tr>
                <td colspan="9" class="text-center text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${mensaje}
                </td>
            </tr>
        `);
    }
    
    function actualizarResumen(datos) {
        const totalAves = datos.length * 5000; // Estimación
        const pesoPromedio = datos.reduce((sum, d) => sum + d.peso_promedio, 0) / datos.length;
        const edadPromedio = datos.reduce((sum, d) => sum + d.edad, 0) / datos.length;
        
        $('#total-aves-seguimiento').text(totalAves.toLocaleString());
        $('#promedio-peso').text(pesoPromedio.toFixed(0) + ' g');
        $('#edad-promedio').text(edadPromedio.toFixed(0) + ' días');
        $('#tasa-crecimiento').text('15.2%');
    }
    
    function generarGraficas(datos) {
        generarGraficaCrecimiento(datos);
        generarGraficaPesoEdad(datos);
        generarGraficaDistribucionPesos(datos);
    }
    
    function generarGraficaCrecimiento(datos) {
        const ctx = document.getElementById('grafica-crecimiento').getContext('2d');
        
        if (window.graficaCrecimiento) {
            window.graficaCrecimiento.destroy();
        }
        
        // Agrupar datos por galpón y fecha
        const galpones = [...new Set(datos.map(d => d.galpon))];
        const fechas = [...new Set(datos.map(d => d.fecha))].sort();
        
        const datasets = galpones.map((galpon, index) => {
            const galponDatos = datos.filter(d => d.galpon === galpon);
            const pesos = fechas.map(fecha => {
                const dato = galponDatos.find(d => d.fecha === fecha);
                return dato ? dato.peso_promedio : null;
            });
            
            return {
                label: galpon,
                data: pesos,
                borderColor: getColor(index),
                backgroundColor: getColor(index, 0.1),
                tension: 0.4,
                fill: false
            };
        });
        
        window.graficaCrecimiento = new Chart(ctx, {
            type: 'line',
            data: {
                labels: fechas.map(f => formatearFecha(f)),
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Peso (g)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Fecha'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }
    
    function generarGraficaPesoEdad(datos) {
        const ctx = document.getElementById('grafica-peso-edad').getContext('2d');
        
        if (window.graficaPesoEdad) {
            window.graficaPesoEdad.destroy();
        }
        
        const edades = [...new Set(datos.map(d => d.edad))].sort();
        const pesos = edades.map(edad => {
            const edadDatos = datos.filter(d => d.edad === edad);
            return edadDatos.reduce((sum, d) => sum + d.peso_promedio, 0) / edadDatos.length;
        });
        
        window.graficaPesoEdad = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: edades.map(e => e + ' días'),
                datasets: [{
                    label: 'Peso Promedio (g)',
                    data: pesos,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    function generarGraficaDistribucionPesos(datos) {
        const ctx = document.getElementById('grafica-distribucion-pesos').getContext('2d');
        
        if (window.graficaDistribucionPesos) {
            window.graficaDistribucionPesos.destroy();
        }
        
        // Crear rangos de peso
        const rangos = [
            {min: 0, max: 1000, label: '0-1000g'},
            {min: 1000, max: 1500, label: '1000-1500g'},
            {min: 1500, max: 2000, label: '1500-2000g'},
            {min: 2000, max: 2500, label: '2000-2500g'},
            {min: 2500, max: 9999, label: '2500g+'}
        ];
        
        const conteos = rangos.map(rango => {
            return datos.filter(d => d.peso_promedio >= rango.min && d.peso_promedio < rango.max).length;
        });
        
        window.graficaDistribucionPesos = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: rangos.map(r => r.label),
                datasets: [{
                    data: conteos,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    function actualizarAnalisis(datos) {
        // Proyección simple basada en tendencia
        const ultimoPeso = Math.max(...datos.map(d => d.peso_promedio));
        const pesoProyectado = ultimoPeso * 1.15; // 15% de crecimiento estimado
        const diasProyeccion = 60; // Días para alcanzar el peso proyectado
        
        $('#peso-proyectado').text(pesoProyectado.toFixed(0) + ' g');
        $('#dias-proyeccion').text(diasProyeccion);
        
        // Barra de progreso
        const progreso = Math.min((ultimoPeso / pesoProyectado) * 100, 100);
        $('#barra-progreso').css('width', progreso + '%');
        
        // Recomendaciones
        const recomendaciones = [
            'Mantener programa de alimentación actual',
            'Monitorear consumo de agua diariamente',
            'Verificar temperatura del galpón',
            'Revisar densidad de población'
        ];
        
        let html = '';
        recomendaciones.forEach(rec => {
            html += `<li>${rec}</li>`;
        });
        $('#recomendaciones').html(html);
    }
    
    function actualizarGraficaPrincipal() {
        // Aquí se actualizaría la gráfica principal según el tipo seleccionado
        console.log('Actualizando gráfica principal...');
    }
    
    function getColor(index, alpha = 1) {
        const colors = [
            'rgba(255, 99, 132, ' + alpha + ')',
            'rgba(54, 162, 235, ' + alpha + ')',
            'rgba(255, 206, 86, ' + alpha + ')',
            'rgba(75, 192, 192, ' + alpha + ')',
            'rgba(153, 102, 255, ' + alpha + ')'
        ];
        return colors[index % colors.length];
    }
    
    function formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString('es-ES');
    }
});
</script>
@endpush

@push('styles')
<style>
/* Estilos para el módulo de información AVICONTROL */

/* Page Header */
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

/* Ajuste para las secciones */
section.content {
    margin-left: 0;
    margin-right: 0;
    position: relative;
}

/* Contenedor más ancho para aprovechar el espacio */
.content-wrapper > * {
    max-width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

/* Elementos específicos más anchos */
.content-wrapper .row {
    max-width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

.content-wrapper .card {
    max-width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

.content-wrapper .table-responsive {
    max-width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

/* Ajuste específico para el contenido principal */
.content-wrapper section.content {
    max-width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 20px !important;
    padding-right: 20px !important;
}

/* Ajuste específico para el sidebar */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100vh;
    z-index: 1000;
}

/* Ajuste del contenido principal */
.main-content {
    margin-left: 250px;
    width: calc(100% - 250px);
    min-height: 100vh;
    background-color: #f4f6f9;
    padding: 20px;
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
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    background: white;
}

.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
    color: #495057;
    padding: 18px 15px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: scale(1.01);
    transition: all 0.2s ease;
}

/* Cards */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    margin-bottom: 25px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}

.card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
    padding: 20px 25px;
}

.card-title {
    margin: 0;
    color: #495057;
    font-weight: 600;
    font-size: 1.1rem;
}

.card-body {
    padding: 25px;
}

/* Botones */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 10px 20px;
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.btn-sm {
    margin-left: 8px;
    padding: 8px 16px;
    font-size: 0.875rem;
}

/* Filtros */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 12px 16px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
    transform: translateY(-1px);
}

/* Grupo de botones */
.btn-group .btn {
    border-radius: 0;
    border: 1px solid #dee2e6;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

.btn-group .btn.active {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

/* Barra de progreso */
.progress {
    height: 25px;
    border-radius: 8px;
    background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
}

.progress-bar {
    line-height: 25px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

/* Gráficas */
canvas {
    border-radius: 12px;
    background: white;
    padding: 20px;
}

/* Cards de estadísticas adicionales */
.bg-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    border: 1px solid #dee2e6;
    border-radius: 12px;
}

.bg-light .card-body {
    padding: 20px;
}

/* Cards de distribución de pesos */
.border-info {
    border-color: #17a2b8 !important;
}

.border-info .card-body {
    padding: 15px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 0.5rem;
    }
    
    .page-header {
        padding: 15px;
    }
    
    .page-title {
        font-size: 1.25rem;
    }
}

/* Ajustes adicionales para centrado */
.row {
    margin-left: 0;
    margin-right: 0;
}

.col-md-3, .col-md-6, .col-md-4, .col-md-8, .col-md-12, .col-md-2 {
    padding-left: 15px;
    padding-right: 15px;
}

/* Centrado de cards */
.card {
    margin-left: auto;
    margin-right: auto;
}

/* Ajuste de breadcrumbs */
.breadcrumb {
    margin-left: 0;
    margin-right: 0;
}

/* Estados de carga */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

/* Mensajes de estado */
.status-message {
    padding: 30px;
    text-align: center;
    color: #6c757d;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
}

.status-message i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.6;
    color: #007bff;
}

/* Animaciones */
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(30px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes slideIn {
    from { 
        opacity: 0; 
        transform: translateX(-30px); 
    }
    to { 
        opacity: 1; 
        transform: translateX(0); 
    }
}

.card {
    animation: fadeIn 0.6s ease-out;
}

.small-box {
    animation: slideIn 0.6s ease-out;
}

/* Hover effects */
.card:hover {
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

/* Breadcrumbs */
.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 25px;
    font-size: 0.9rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
    font-weight: bold;
}

.breadcrumb-item a {
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: #0056b3;
}

/* Page header */
.page-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
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

/* Estados especiales */
.text-success {
    color: #28a745 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-info {
    color: #17a2b8 !important;
}

.text-danger {
    color: #dc3545 !important;
}

/* Sombras personalizadas */
.shadow-sm {
    box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
}

.shadow {
    box-shadow: 0 4px 6px rgba(0,0,0,0.07) !important;
}

.shadow-lg {
    box-shadow: 0 10px 15px rgba(0,0,0,0.1) !important;
}
</style>
@endpush
@endsection
