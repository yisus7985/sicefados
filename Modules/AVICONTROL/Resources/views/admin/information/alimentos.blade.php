@extends('avicontrol::layouts.admin')

@section('title', 'Informes de Alimentos - AVICONTROL')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Informes de Alimentos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.welcome') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.information.index') }}">Información</a></li>
                        <li class="breadcrumb-item active">Alimentos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Filtros -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-2"></i>
                        Filtros de Búsqueda
                    </h3>
                </div>
                <div class="card-body">
                    <form id="filtro-alimentos">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="galpon">Galpón</label>
                                    <select class="form-control" id="galpon" name="galpon">
                                        <option value="">Todos los galpones</option>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="insumo">Tipo de Insumo</label>
                                    <select class="form-control" id="insumo" name="insumo">
                                        <option value="">Todos los insumos</option>
                                        <option value="concentrado">Concentrado</option>
                                        <option value="maiz">Maíz</option>
                                        <option value="soya">Soya</option>
                                        <option value="vitaminas">Vitaminas</option>
                                        <option value="minerales">Minerales</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha-inicio">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="fecha-inicio" name="fecha_inicio">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fecha-fin">Fecha Fin</label>
                                    <input type="date" class="form-control" id="fecha-fin" name="fecha_fin">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="costo-min">Costo Mínimo</label>
                                    <input type="number" class="form-control" id="costo-min" name="costo_min" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="costo-max">Costo Máximo</label>
                                    <input type="number" class="form-control" id="costo-max" name="costo_max" placeholder="999999.99">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search mr-2"></i>
                                            Filtrar
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="limpiar-filtros">
                                            <i class="fas fa-times mr-2"></i>
                                            Limpiar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resumen de Costos -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>${{ number_format($estadisticasAlimentos['total_gasto'] ?? 0, 2) }}</h3>
                            <p>Total Gastado</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>${{ number_format($estadisticasAlimentos['promedio_galpon'] ?? 0, 2) }}</h3>
                            <p>Promedio por Galpón</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasAlimentos['total_kilos'] ?? 0) }} kg</h3>
                            <p>Total Kilogramos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-weight-hanging"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>${{ number_format($estadisticasAlimentos['costo_ave'] ?? 0, 2) }}</h3>
                            <p>Costo por Ave</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-kiwi-bird"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Adicionales -->
            @if(isset($estadisticasAlimentos['consumo_hoy']) || isset($estadisticasAlimentos['consumo_mes']))
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="text-warning">Consumo del Día</h5>
                            <h3 class="text-warning">{{ number_format($estadisticasAlimentos['consumo_hoy'] ?? 0, 1) }} kg</h3>
                            <small class="text-muted">Kg consumidos hoy</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="text-info">Consumo del Mes</h5>
                            <h3 class="text-info">{{ number_format($estadisticasAlimentos['consumo_mes'] ?? 0, 1) }} kg</h3>
                            <small class="text-muted">Kg consumidos este mes</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tabla de Alimentos -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-2"></i>
                        Registros de Alimentos por Galpón
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
                        <table class="table table-bordered table-striped" id="tabla-alimentos">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Galpón</th>
                                    <th>Insumo</th>
                                    <th>Cantidad (kg)</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th>
                                    <th>Proveedor</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($datosAlimentos) && count($datosAlimentos) > 0)
                                    @foreach($datosAlimentos as $alimento)
                                    <tr>
                                        <td>{{ $alimento['fecha'] ? $alimento['fecha']->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $alimento['galpon'] }}</td>
                                        <td>{{ $alimento['insumo'] }}</td>
                                        <td>{{ number_format($alimento['cantidad'], 1) }}</td>
                                        <td>${{ number_format($alimento['precio_unitario'], 2) }}</td>
                                        <td>${{ number_format($alimento['total'], 2) }}</td>
                                        <td>{{ $alimento['proveedor'] }}</td>
                                        <td>{{ $alimento['observaciones'] }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No hay datos de alimentos disponibles
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Gráficas de Análisis -->
            <div class="row">
                <!-- Gráfica de Costos por Galpón -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Distribución de Costos por Galpón
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="grafica-costos-galpon" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de Consumo por Insumo -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Consumo por Tipo de Insumo
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="grafica-consumo-insumo" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Análisis de Costos por Período -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-2"></i>
                        Evolución de Costos por Período
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="grafica-evolucion-costos" style="height: 400px;"></canvas>
                </div>
            </div>

            <!-- Información de Desperdicios de Alimentos -->
            @if(isset($estadisticasAlimentos['desperdicios']) && count($estadisticasAlimentos['desperdicios']) > 0)
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-trash-alt mr-2 text-danger"></i>
                        Análisis de Desperdicios de Alimentos
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($estadisticasAlimentos['desperdicios'] as $desperdicio)
                        <div class="col-md-4 mb-3">
                            <div class="card border-danger">
                                <div class="card-body text-center">
                                    <h5 class="text-danger">{{ $desperdicio['galpon'] }}</h5>
                                    <h4 class="text-danger">{{ number_format($desperdicio['total_desperdicio'], 1) }} kg</h4>
                                    <small class="text-muted">Desperdicio total</small>
                                    <div class="mt-2">
                                        <span class="badge bg-warning">
                                            {{ number_format($desperdicio['porcentaje_desperdicio'], 1) }}% del consumo
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
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
    // Inicializar fecha fin como hoy
    $('#fecha-fin').val(new Date().toISOString().split('T')[0]);
    
    // Cargar galpones
    cargarGalpones();
    
    // Cargar datos iniciales desde el servidor
    cargarDatosIniciales();
    
    // Cargar datos iniciales desde el servidor
    cargarDatosIniciales();
    
    // Event listeners
    $('#filtro-alimentos').on('submit', function(e) {
        e.preventDefault();
        cargarDatosAlimentos();
    });
    
    $('#limpiar-filtros').on('click', function() {
        $('#filtro-alimentos')[0].reset();
        $('#fecha-fin').val(new Date().toISOString().split('T')[0]);
        cargarDatosAlimentos();
    });
    
    function cargarDatosIniciales() {
        // Cargar datos iniciales desde el servidor
        $.ajax({
            url: '{{ route("avicontrol.admin.information.alimentos") }}',
            method: 'GET',
            data: { ajax: true, action: 'datos_iniciales' },
            success: function(response) {
                if (response.success) {
                    mostrarDatos(response.datos);
                    actualizarResumen(response.datos);
                    generarGraficas(response.datos);
                }
            },
            error: function() {
                console.error('Error cargando datos iniciales');
            }
        });
    }
    
    function cargarGalpones() {
        // Cargar galpones desde el backend
        $.ajax({
            url: '{{ route("avicontrol.admin.information.alimentos") }}',
            method: 'GET',
            data: { ajax: true, action: 'galpones' },
            success: function(response) {
                if (response.galpones) {
                    response.galpones.forEach(galpon => {
                        $('#galpon').append(`<option value="${galpon.id}">${galpon.name}</option>`);
                    });
                }
            },
            error: function() {
                console.error('Error cargando galpones');
            }
        });
    }
    
    function cargarDatosAlimentos() {
        mostrarCargando();
        
        // Obtener parámetros del filtro
        const filtros = {
            galpon: $('#galpon').val(),
            insumo: $('#insumo').val(),
            fecha_inicio: $('#fecha-inicio').val(),
            fecha_fin: $('#fecha-fin').val(),
            costo_min: $('#costo-min').val(),
            costo_max: $('#costo-max').val(),
            ajax: true
        };
        
        // Hacer petición AJAX para obtener datos filtrados
        $.ajax({
            url: '{{ route("avicontrol.admin.information.alimentos.filtrar") }}',
            method: 'POST',
            data: filtros,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    mostrarDatos(response.datos);
                    actualizarResumen(response.datos);
                    generarGraficas(response.datos);
                } else {
                    mostrarError(response.message || 'Error al cargar datos');
                }
            },
            error: function() {
                mostrarError('Error de conexión al servidor');
            }
        });
    }
    
    function mostrarCargando() {
        $('#tabla-alimentos tbody').html('<tr><td colspan="8" class="text-center">Cargando datos...</td></tr>');
    }
    
    function mostrarDatos(datos) {
        if (!datos || datos.length === 0) {
            $('#tabla-alimentos tbody').html(`
                <tr>
                    <td colspan="8" class="text-center text-muted">
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
                    <td>${dato.insumo}</td>
                    <td>${dato.cantidad.toLocaleString()}</td>
                    <td>$${dato.precio_unitario.toFixed(2)}</td>
                    <td>$${dato.total.toFixed(2)}</td>
                    <td>${dato.proveedor}</td>
                    <td>${dato.observaciones}</td>
                </tr>
            `;
        });
        $('#tabla-alimentos tbody').html(html);
    }
    
    function mostrarError(mensaje) {
        $('#tabla-alimentos tbody').html(`
            <tr>
                <td colspan="8" class="text-center text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${mensaje}
                </td>
            </tr>
        `);
    }
    
    function actualizarResumen(datos) {
        const totalGasto = datos.reduce((sum, dato) => sum + dato.total, 0);
        const totalKilos = datos.reduce((sum, dato) => sum + dato.cantidad, 0);
        const galponesUnicos = [...new Set(datos.map(d => d.galpon))];
        const promedioGalpon = totalGasto / galponesUnicos.length;
        const costoAve = totalGasto / 9800; // Total de aves del sistema
        
        $('#total-gasto').text('$' + totalGasto.toFixed(2));
        $('#promedio-galpon').text('$' + promedioGalpon.toFixed(2));
        $('#total-kilos').text(totalKilos.toLocaleString() + ' kg');
        $('#costo-ave').text('$' + costoAve.toFixed(2));
    }
    
    function generarGraficas(datos) {
        generarGraficaCostosGalpon(datos);
        generarGraficaConsumoInsumo(datos);
        generarGraficaEvolucionCostos(datos);
    }
    
    function generarGraficaCostosGalpon(datos) {
        const ctx = document.getElementById('grafica-costos-galpon').getContext('2d');
        
        if (window.graficaCostosGalpon) {
            window.graficaCostosGalpon.destroy();
        }
        
        const galpones = [...new Set(datos.map(d => d.galpon))];
        const costos = galpones.map(galpon => {
            const galponDatos = datos.filter(d => d.galpon === galpon);
            return galponDatos.reduce((sum, d) => sum + d.total, 0);
        });
        
        window.graficaCostosGalpon = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: galpones,
                datasets: [{
                    data: costos,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': $' + context.parsed.toFixed(2) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    
    function generarGraficaConsumoInsumo(datos) {
        const ctx = document.getElementById('grafica-consumo-insumo').getContext('2d');
        
        if (window.graficaConsumoInsumo) {
            window.graficaConsumoInsumo.destroy();
        }
        
        const insumos = [...new Set(datos.map(d => d.insumo))];
        const consumos = insumos.map(insumo => {
            const insumoDatos = datos.filter(d => d.insumo === insumo);
            return insumoDatos.reduce((sum, d) => sum + d.cantidad, 0);
        });
        
        window.graficaConsumoInsumo = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: insumos,
                datasets: [{
                    label: 'Consumo (kg)',
                    data: consumos,
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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
    
    function generarGraficaEvolucionCostos(datos) {
        const ctx = document.getElementById('grafica-evolucion-costos').getContext('2d');
        
        if (window.graficaEvolucionCostos) {
            window.graficaEvolucionCostos.destroy();
        }
        
        const fechas = [...new Set(datos.map(d => d.fecha))].sort();
        const costos = fechas.map(fecha => {
            const fechaDatos = datos.filter(d => d.fecha === fecha);
            return fechaDatos.reduce((sum, d) => sum + d.total, 0);
        });
        
        window.graficaEvolucionCostos = new Chart(ctx, {
            type: 'line',
            data: {
                labels: fechas.map(f => formatearFecha(f)),
                datasets: [{
                    label: 'Costos Diarios',
                    data: costos,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    }
    
    function formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString('es-ES');
    }
});
</script>
@endpush

@push('styles')
<style>
/* Estilos comunes para el módulo de información AVICONTROL */

/* Estilos generales */
.content-wrapper {
    background-color: #f4f6f9;
    min-height: 100vh;
    padding: 20px;
    width: calc(100% - 250px);
    margin-left: 250px;
    position: relative;
}

/* Ajuste para el contenido principal */
.container-fluid {
    max-width: 1200px;
    margin: 0 auto;
    padding-left: 20px;
    padding-right: 20px;
    position: relative;
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

/* Ajustes adicionales para centrado */
.row {
    margin-left: 0;
    margin-right: 0;
}

.col-md-3, .col-md-6, .col-md-4, .col-md-8, .col-md-12 {
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
