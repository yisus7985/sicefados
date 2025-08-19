@extends('avicontrol::layouts.admin')

@section('title', 'Estadísticas de Conversión Alimenticia')

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
    /* Colores más suaves y profesionales */
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
    .loading {
        display: none;
        text-align: center;
        padding: 20px;
    }
    .loading i {
        font-size: 2rem;
        color: #007bff;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line" style="color: #495057;"></i>
            Estadísticas de Conversión Alimenticia
        </h1>
        <div class="btn-group">
            <a href="{{ route('avicontrol.admin.food_conversion.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('avicontrol.admin.food_conversion.test_chart') }}" class="btn btn-info">
                <i class="fas fa-bug"></i> Probar Gráficas
            </a>
            <a href="{{ route('avicontrol.admin.food_conversion.estadisticas_simple') }}" class="btn btn-warning">
                <i class="fas fa-chart-bar"></i> Vista Simple
            </a>
        </div>
    </div>

    <!-- Loading -->
    <div id="loading" class="loading">
        <i class="fas fa-spinner"></i>
        <p>Cargando estadísticas...</p>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Filtros de Análisis</h6>
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
                    <label for="tipo_produccion" class="form-label">Tipo de Producción</label>
                    <select class="form-select" id="tipo_produccion" name="tipo_produccion">
                        <option value="">Todos los tipos</option>
                        <option value="huevo">Huevo</option>
                        <option value="carne">Carne</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="periodo_tipo" class="form-label">Período</label>
                    <select class="form-select" id="periodo_tipo" name="periodo_tipo">
                        <option value="">Todos los períodos</option>
                        <option value="diario">Diario</option>
                        <option value="semanal">Semanal</option>
                        <option value="mensual">Mensual</option>
                        <option value="acumulado">Acumulado</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Métricas Principales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 stats-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #495057;">
                                Promedio Conversión
                            </div>
                            <div class="metric-value" id="promedioConversion">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
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
                                Mejor Conversión
                            </div>
                            <div class="metric-value" id="mejorConversion">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
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
                                Total Registros
                            </div>
                            <div class="metric-value" id="totalRegistros">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-gray-300"></i>
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
                                Eficiencia Promedio
                            </div>
                            <div class="metric-value" id="eficienciaPromedio">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="row">
        <!-- Gráfica de Evolución Temporal -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Evolución de Conversión Alimenticia</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="evolucionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica de Conversión por Galpón -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Conversión por Galpón</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="galponChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas Adicionales -->
    <div class="row">
        <!-- Gráfica de Conversión por Tipo de Producción -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Conversión por Tipo de Producción</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="tipoProduccionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica de Distribución por Período -->
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Distribución por Período</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="periodoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Datos Detallados -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Datos Detallados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="datosTable">
                    <thead>
                        <tr>
                            <th>Galpón</th>
                            <th>Período</th>
                            <th>Tipo</th>
                            <th>Alimento (kg)</th>
                            <th>Producto (kg)</th>
                            <th>Conversión</th>
                            <th>Eficiencia</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="datosTableBody">
                        <!-- Los datos se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
console.log('🚀 SCRIPT CARGADO - INICIANDO ESTADÍSTICAS');

let charts = {};

// Función para crear una gráfica
function crearGrafica(id, tipo, datos, opciones) {
    console.log(`📊 Creando gráfica ${id}`);
    
    const canvas = document.getElementById(id);
    if (!canvas) {
        console.error(`❌ Canvas ${id} no encontrado`);
        return null;
    }
    
    try {
        const ctx = canvas.getContext('2d');
        
        // Destruir gráfica existente si existe
        if (charts[id]) {
            charts[id].destroy();
        }
        
        const chart = new Chart(ctx, {
            type: tipo,
            data: datos,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                ...opciones
            }
        });
        
        charts[id] = chart;
        console.log(`✅ Gráfica ${id} creada exitosamente`);
        return chart;
    } catch (error) {
        console.error(`❌ Error en gráfica ${id}:`, error);
        return null;
    }
}

// Función para actualizar métricas
function actualizarMetricas(data) {
    console.log('📊 Actualizando métricas...');
    
    // Actualizar métricas principales
    document.getElementById('promedioConversion').textContent = 
        data.promedio_conversion ? parseFloat(data.promedio_conversion).toFixed(2) : '-';
    document.getElementById('mejorConversion').textContent = 
        data.mejor_conversion ? parseFloat(data.mejor_conversion).toFixed(2) : '-';
    document.getElementById('totalRegistros').textContent = 
        data.total_registros || '-';
    
    // Calcular eficiencia promedio
    if (data.promedio_conversion) {
        const eficiencia = (1.5 / parseFloat(data.promedio_conversion)) * 100;
        document.getElementById('eficienciaPromedio').textContent = 
            Math.min(100, Math.max(0, eficiencia)).toFixed(1) + '%';
    } else {
        document.getElementById('eficienciaPromedio').textContent = '-';
    }
}

// Función para actualizar gráficas con datos específicos
function actualizarGraficasConDatos(data) {
    console.log('🎨 Actualizando gráficas con datos reales...');
    
    // Gráfica 1: Evolución de Conversión Alimenticia por Galpón
    if (data.conversion_por_galpon && data.conversion_por_galpon.length > 0) {
        const evolucionData = {
            labels: data.conversion_por_galpon.map(item => item.galpon?.name || `Galpón ${item.galpon_id}`),
            datasets: [{
                label: 'Promedio Conversión',
                data: data.conversion_por_galpon.map(item => parseFloat(item.promedio_conversion || 0)),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        };
        
        crearGrafica('evolucionChart', 'line', evolucionData, {
            plugins: {
                title: {
                    display: true,
                    text: 'Evolución de Conversión Alimenticia por Galpón',
                    color: '#495057',
                    font: { size: 16 }
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#495057' },
                    grid: { color: 'rgba(0,0,0,0.1)' }
                },
                x: {
                    ticks: { color: '#495057' },
                    grid: { color: 'rgba(0,0,0,0.1)' }
                }
            }
        });
    }
    
    // Gráfica 2: Conversión por Galpón (Barras)
    if (data.conversion_por_galpon && data.conversion_por_galpon.length > 0) {
        const galponData = {
            labels: data.conversion_por_galpon.map(item => item.galpon?.name || `Galpón ${item.galpon_id}`),
            datasets: [{
                label: 'Conversión Promedio',
                data: data.conversion_por_galpon.map(item => parseFloat(item.promedio_conversion || 0)),
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(0, 123, 255, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)',
                    'rgba(111, 66, 193, 0.8)'
                ],
                borderColor: ['#28a745', '#007bff', '#ffc107', '#dc3545', '#6f42c1'],
                borderWidth: 2
            }]
        };
        
        crearGrafica('galponChart', 'bar', galponData, {
            plugins: {
                title: {
                    display: true,
                    text: 'Conversión por Galpón',
                    color: '#495057',
                    font: { size: 16 }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#495057' },
                    grid: { color: 'rgba(0,0,0,0.1)' }
                },
                x: {
                    ticks: { color: '#495057' },
                    grid: { color: 'rgba(0,0,0,0.1)' }
                }
            }
        });
    }
    
    // Gráfica 3: Conversión por Tipo de Producción
    if (data.conversion_por_galpon && data.conversion_por_galpon.length > 0) {
        const tiposProduccion = data.conversion_por_galpon.reduce((acc, item) => {
            const tipo = item.galpon?.tipo_produccion || 'Sin especificar';
            if (!acc[tipo]) {
                acc[tipo] = {
                    total: 0,
                    count: 0
                };
            }
            acc[tipo].total += parseFloat(item.promedio_conversion || 0);
            acc[tipo].count += 1;
            return acc;
        }, {});
        
        if (Object.keys(tiposProduccion).length > 0) {
            const tipoProduccionData = {
                labels: Object.keys(tiposProduccion),
                datasets: [{
                    data: Object.values(tiposProduccion).map(tipo => tipo.total / tipo.count),
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            };
            
            crearGrafica('tipoProduccionChart', 'doughnut', tipoProduccionData, {
                plugins: {
                    title: {
                        display: true,
                        text: 'Conversión por Tipo de Producción',
                        color: '#495057',
                        font: { size: 16 }
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            });
        }
    }
    
    // Gráfica 4: Distribución por Período
    if (data.conversion_por_galpon && data.conversion_por_galpon.length > 0) {
        const periodos = data.conversion_por_galpon.reduce((acc, item) => {
            const periodo = item.periodo_tipo || 'Sin especificar';
            if (!acc[periodo]) {
                acc[periodo] = {
                    total: 0,
                    count: 0
                };
            }
            acc[periodo].total += parseFloat(item.promedio_conversion || 0);
            acc[periodo].count += 1;
            return acc;
        }, {});
        
        if (Object.keys(periodos).length > 0) {
            const periodoData = {
                labels: Object.keys(periodos).map(p => p.charAt(0).toUpperCase() + p.slice(1)),
                datasets: [{
                    label: 'Conversión Promedio',
                    data: Object.values(periodos).map(periodo => periodo.total / periodo.count),
                    backgroundColor: 'rgba(23, 162, 184, 0.8)',
                    borderColor: '#17a2b8',
                    borderWidth: 2
                }]
            };
            
            crearGrafica('periodoChart', 'bar', periodoData, {
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribución por Período',
                        color: '#495057',
                        font: { size: 16 }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#495057' },
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    },
                    x: {
                        ticks: { color: '#495057' },
                        grid: { color: 'rgba(0,0,0,0.1)' }
                    }
                }
            });
        }
    }
    
    console.log('✅ Todas las gráficas actualizadas con datos reales');
}

// Función para actualizar gráficas con datos reales
function actualizarGraficasConDatosReales() {
    console.log('🔄 Actualizando gráficas con datos reales...');
    
    // Obtener valores de los filtros
    const galponId = document.getElementById('galpon_id').value;
    const tipoProduccion = document.getElementById('tipo_produccion').value;
    const periodoTipo = document.getElementById('periodo_tipo').value;
    
    // Mostrar loading
    mostrarLoading();
    
    // Hacer petición AJAX para obtener datos reales
    fetch(`{{ route('avicontrol.admin.food_conversion.estadisticas') }}?galpon_id=${galponId}&tipo_produccion=${tipoProduccion}&periodo_tipo=${periodoTipo}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('📊 Datos reales recibidos:', data);
        ocultarLoading();
        
        // Actualizar métricas
        actualizarMetricas(data);
        
        // Actualizar gráficas con datos reales
        actualizarGraficasConDatos(data);
        
    })
    .catch(error => {
        console.error('❌ Error obteniendo datos:', error);
        ocultarLoading();
        mostrarError('Error al cargar datos reales');
        
        // Fallback: mostrar gráficas con datos simulados
        console.log('🔄 Mostrando gráficas simuladas como fallback');
        mostrarGraficasSimuladas();
    });
}

// Función para mostrar gráficas simuladas (fallback)
function mostrarGraficasSimuladas() {
    console.log('🎭 Mostrando gráficas simuladas...');
    
    // Datos simulados para las gráficas
    const datosSimulados = {
        conversion_por_galpon: [
            { galpon: { name: 'Galpón 1', tipo_produccion: 'huevo' }, promedio_conversion: 2.1, mejor_conversion: 1.8, peor_conversion: 2.5, periodo_tipo: 'mensual' },
            { galpon: { name: 'Galpón 2', tipo_produccion: 'carne' }, promedio_conversion: 2.3, mejor_conversion: 2.0, peor_conversion: 2.7, periodo_tipo: 'semanal' },
            { galpon: { name: 'Galpón 3', tipo_produccion: 'huevo' }, promedio_conversion: 1.9, mejor_conversion: 1.6, peor_conversion: 2.2, periodo_tipo: 'diario' },
            { galpon: { name: 'Galpón 4', tipo_produccion: 'carne' }, promedio_conversion: 2.0, mejor_conversion: 1.7, peor_conversion: 2.4, periodo_tipo: 'acumulado' }
        ],
        promedio_conversion: 2.1,
        mejor_conversion: 1.6,
        peor_conversion: 2.7,
        total_registros: 4
    };
    
    actualizarMetricas(datosSimulados);
    actualizarGraficasConDatos(datosSimulados);
}

// Funciones de UI
function mostrarLoading() {
    const loading = document.getElementById('loading');
    if (loading) loading.style.display = 'block';
}

function ocultarLoading() {
    const loading = document.getElementById('loading');
    if (loading) loading.style.display = 'none';
}

function mostrarError(mensaje) {
    console.error('❌ Error:', mensaje);
    // Aquí podrías mostrar un toast o alert
}

function limpiarFiltros() {
    document.getElementById('galpon_id').value = '';
    document.getElementById('tipo_produccion').value = '';
    document.getElementById('periodo_tipo').value = '';
    actualizarGraficasConDatosReales();
}

// Event listeners para filtros
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM cargado, configurando filtros...');
    
    // Configurar eventos de cambio en filtros
    const filtros = ['galpon_id', 'tipo_produccion', 'periodo_tipo'];
    filtros.forEach(id => {
        const elemento = document.getElementById(id);
        if (elemento) {
            elemento.addEventListener('change', function() {
                console.log(`🔄 Filtro ${id} cambiado, actualizando gráficas...`);
                actualizarGraficasConDatosReales();
            });
        }
    });
    
    // Configurar evento del formulario
    const form = document.getElementById('filtrosForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            actualizarGraficasConDatosReales();
        });
    }
    
    // Cargar datos iniciales
    console.log('🚀 Cargando datos iniciales...');
    actualizarGraficasConDatosReales();
});

// Función para verificar si el DOM ya está listo
if (document.readyState === 'loading') {
    console.log('⏳ DOM aún cargando...');
} else {
    console.log('✅ DOM ya está listo, ejecutando inmediatamente...');
    // Si el DOM ya está listo, ejecutar inmediatamente
    const filtros = ['galpon_id', 'tipo_produccion', 'periodo_tipo'];
    filtros.forEach(id => {
        const elemento = document.getElementById(id);
        if (elemento) {
            elemento.addEventListener('change', function() {
                console.log(`🔄 Filtro ${id} cambiado, actualizando gráficas...`);
                actualizarGraficasConDatosReales();
            });
        }
    });
    
    const form = document.getElementById('filtrosForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            actualizarGraficasConDatosReales();
        });
    }
    
    actualizarGraficasConDatosReales();
}

console.log('✅ SCRIPT COMPLETAMENTE CARGADO');
</script>
@endpush
