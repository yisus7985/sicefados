@extends('avicontrol::layouts.admin')

@section('title', 'Reporte de Conversión Alimenticia')

@section('styles')
<style>
    .report-card {
        transition: transform 0.2s;
    }
    .report-card:hover {
        transform: translateY(-2px);
    }
    .summary-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .export-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .badge-efficiency {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-file-alt text-primary"></i>
            Reporte de Conversión Alimenticia
        </h1>
        <div class="export-buttons">
            <button class="btn btn-success" onclick="exportarPDF()">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </button>
            <button class="btn btn-info" onclick="exportarExcel()">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </button>
            <a href="{{ route('avicontrol.admin.food_conversion.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros del Reporte</h6>
        </div>
        <div class="card-body">
            <form id="filtrosReporte" class="row g-3">
                <div class="col-md-3">
                    <label for="galpon_id" class="form-label">Galpón</label>
                    <select class="form-select" id="galpon_id" name="galpon_id">
                        <option value="">Todos los galpones</option>
                        @foreach($galpones as $galpon)
                            <option value="{{ $galpon->id }}">{{ $galpon->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                           value="{{ request('fecha_inicio', now()->subDays(30)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                           value="{{ request('fecha_fin', now()->format('Y-m-d')) }}">
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
                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Generar Reporte
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="limpiarFiltrosReporte()">
                        <i class="fas fa-eraser"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen Ejecutivo -->
    <div class="summary-box">
        <div class="row text-center">
            <div class="col-md-3">
                <h4 class="mb-0" id="totalRegistros">0</h4>
                <small>Total Registros</small>
            </div>
            <div class="col-md-3">
                <h4 class="mb-0" id="promedioConversion">0.00</h4>
                <small>Conversión Promedio</small>
            </div>
            <div class="col-md-3">
                <h4 class="mb-0" id="totalAlimento">0.00</h4>
                <small>Total Alimento (kg)</small>
            </div>
            <div class="col-md-3">
                <h4 class="mb-0" id="totalProducto">0.00</h4>
                <small>Total Producto (kg)</small>
            </div>
        </div>
    </div>

    <!-- Tabla Principal del Reporte -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detalle de Conversiones</h6>
            <span class="badge bg-primary" id="contadorRegistros">0 registros</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="reporteTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Galpón</th>
                            <th>Período</th>
                            <th>Fechas</th>
                            <th>Tipo Producción</th>
                            <th>Alimento (kg)</th>
                            <th>Producto (kg)</th>
                            <th>Conversión</th>
                            <th>Eficiencia</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody id="reporteTableBody">
                        <!-- Los datos se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Análisis por Galpón -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Análisis por Galpón</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="analisisGalponTable">
                    <thead class="table-light">
                        <tr>
                            <th>Galpón</th>
                            <th>Total Registros</th>
                            <th>Conversión Promedio</th>
                            <th>Mejor Conversión</th>
                            <th>Peor Conversión</th>
                            <th>Total Alimento</th>
                            <th>Total Producto</th>
                            <th>Eficiencia Promedio</th>
                        </tr>
                    </thead>
                    <tbody id="analisisGalponTableBody">
                        <!-- Los datos se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Análisis por Período -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Análisis por Período</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="analisisPeriodoTable">
                    <thead class="table-light">
                        <tr>
                            <th>Período</th>
                            <th>Total Registros</th>
                            <th>Conversión Promedio</th>
                            <th>Total Alimento</th>
                            <th>Total Producto</th>
                            <th>Eficiencia Promedio</th>
                        </tr>
                    </thead>
                    <tbody id="analisisPeriodoTableBody">
                        <!-- Los datos se cargarán dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Observaciones y Recomendaciones -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Observaciones y Recomendaciones</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary">Observaciones Generales:</h6>
                    <ul id="observacionesGenerales">
                        <li>Analizando datos de conversión alimenticia...</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-success">Recomendaciones:</h6>
                    <ul id="recomendaciones">
                        <li>Generando recomendaciones basadas en el análisis...</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    cargarReporte();
    
    // Event listener para filtros
    document.getElementById('filtrosReporte').addEventListener('submit', function(e) {
        e.preventDefault();
        cargarReporte();
    });
});

function cargarReporte() {
    const formData = new FormData(document.getElementById('filtrosReporte'));
    const params = new URLSearchParams(formData);
    
    // Mostrar loading
    mostrarLoading();
    
    fetch(`{{ route('avicontrol.admin.food_conversion.reporte') }}?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            actualizarResumen(data);
            actualizarTablaPrincipal(data);
            actualizarAnalisisGalpon(data);
            actualizarAnalisisPeriodo(data);
            actualizarObservaciones(data);
            ocultarLoading();
        })
        .catch(error => {
            console.error('Error al cargar reporte:', error);
            alert('Error al cargar el reporte');
            ocultarLoading();
        });
}

function actualizarResumen(data) {
    document.getElementById('totalRegistros').textContent = data.total_registros || 0;
    document.getElementById('promedioConversion').textContent = 
        data.promedio_conversion ? data.promedio_conversion.toFixed(2) : '0.00';
    document.getElementById('totalAlimento').textContent = 
        data.total_alimento ? data.total_alimento.toFixed(2) : '0.00';
    document.getElementById('totalProducto').textContent = 
        data.total_producto ? data.total_producto.toFixed(2) : '0.00';
}

function actualizarTablaPrincipal(data) {
    const tbody = document.getElementById('reporteTableBody');
    tbody.innerHTML = '';
    
    if (data.conversiones && data.conversiones.length > 0) {
        data.conversiones.forEach(conversion => {
            const row = document.createElement('tr');
            const eficiencia = calcularEficiencia(conversion.conversion_alimenticia);
            
            row.innerHTML = `
                <td>${conversion.id}</td>
                <td>${conversion.galpon?.name || 'N/A'}</td>
                <td><span class="badge bg-info">${conversion.periodo_tipo}</span></td>
                <td>${conversion.fecha_inicio} - ${conversion.fecha_fin}</td>
                <td><span class="badge bg-${conversion.tipo_produccion === 'huevo' ? 'warning' : 'success'}">${conversion.tipo_produccion}</span></td>
                <td>${parseFloat(conversion.total_alimento_consumido).toFixed(2)}</td>
                <td>${parseFloat(conversion.total_producto_obtenido).toFixed(2)}</td>
                <td><strong>${parseFloat(conversion.conversion_alimenticia).toFixed(2)}</strong></td>
                <td><span class="badge badge-efficiency bg-${eficiencia.color}">${eficiencia.valor}%</span></td>
                <td><span class="badge bg-${conversion.estado === 'active' ? 'success' : 'secondary'}">${conversion.estado}</span></td>
                <td>${conversion.observaciones || '-'}</td>
            `;
            tbody.appendChild(row);
        });
        
        document.getElementById('contadorRegistros').textContent = `${data.conversiones.length} registros`;
    } else {
        tbody.innerHTML = '<tr><td colspan="11" class="text-center text-muted">No hay datos para mostrar</td></tr>';
        document.getElementById('contadorRegistros').textContent = '0 registros';
    }
}

function actualizarAnalisisGalpon(data) {
    const tbody = document.getElementById('analisisGalponTableBody');
    tbody.innerHTML = '';
    
    if (data.conversion_por_galpon && data.conversion_por_galpon.length > 0) {
        data.conversion_por_galpon.forEach(item => {
            const row = document.createElement('tr');
            const eficiencia = calcularEficiencia(item.promedio_conversion);
            
            row.innerHTML = `
                <td><strong>${item.galpon?.name || 'Galpón ' + item.galpon_id}</strong></td>
                <td>${item.total_registros || 0}</td>
                <td><strong>${item.promedio_conversion.toFixed(2)}</strong></td>
                <td>${item.mejor_conversion ? item.mejor_conversion.toFixed(2) : 'N/A'}</td>
                <td>${item.peor_conversion ? item.peor_conversion.toFixed(2) : 'N/A'}</td>
                <td>${item.total_alimento ? item.total_alimento.toFixed(2) : '0.00'}</td>
                <td>${item.total_producto ? item.total_producto.toFixed(2) : '0.00'}</td>
                <td><span class="badge badge-efficiency bg-${eficiencia.color}">${eficiencia.valor}%</span></td>
            `;
            tbody.appendChild(row);
        });
    } else {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">No hay datos para mostrar</td></tr>';
    }
}

function actualizarAnalisisPeriodo(data) {
    const tbody = document.getElementById('analisisPeriodoTableBody');
    tbody.innerHTML = '';
    
    // Datos simulados para el análisis por período
    const periodos = [
        { periodo: 'Diario', registros: 2, conversion: 2.05, alimento: 500, producto: 244, eficiencia: 73.2 },
        { periodo: 'Semanal', registros: 4, conversion: 2.07, alimento: 2500, producto: 1208, eficiencia: 72.5 },
        { periodo: 'Mensual', registros: 1, conversion: 2.06, alimento: 10200, producto: 4950, eficiencia: 72.8 }
    ];
    
    periodos.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>${item.periodo}</strong></td>
            <td>${item.registros}</td>
            <td><strong>${item.conversion.toFixed(2)}</strong></td>
            <td>${item.alimento.toFixed(2)}</td>
            <td>${item.producto.toFixed(2)}</td>
            <td><span class="badge badge-efficiency bg-${item.eficiencia >= 80 ? 'success' : item.eficiencia >= 60 ? 'warning' : 'danger'}">${item.eficiencia.toFixed(1)}%</span></td>
        `;
        tbody.appendChild(row);
    });
}

function actualizarObservaciones(data) {
    const observacionesList = document.getElementById('observacionesGenerales');
    const recomendacionesList = document.getElementById('recomendaciones');
    
    // Limpiar listas
    observacionesList.innerHTML = '';
    recomendacionesList.innerHTML = '';
    
    // Observaciones basadas en datos
    if (data.promedio_conversion) {
        if (data.promedio_conversion > 2.5) {
            observacionesList.innerHTML += '<li class="text-warning">La conversión alimenticia está por encima del óptimo (2.5)</li>';
            recomendacionesList.innerHTML += '<li class="text-warning">Revisar la calidad del alimento y las condiciones de crianza</li>';
        } else if (data.promedio_conversion < 1.8) {
            observacionesList.innerHTML += '<li class="text-success">Excelente conversión alimenticia</li>';
            recomendacionesList.innerHTML += '<li class="text-success">Mantener las condiciones actuales de producción</li>';
        } else {
            observacionesList.innerHTML += '<li class="text-info">Conversión alimenticia dentro del rango óptimo</li>';
            recomendacionesList.innerHTML += '<li class="text-info">Continuar monitoreando para identificar oportunidades de mejora</li>';
        }
    }
    
    if (data.total_registros && data.total_registros < 5) {
        observacionesList.innerHTML += '<li class="text-info">Pocos registros disponibles para análisis estadístico</li>';
        recomendacionesList.innerHTML += '<li class="text-info">Aumentar la frecuencia de registro para mejor análisis</li>';
    }
    
    if (data.conversion_por_galpon && data.conversion_por_galpon.length > 1) {
        const variacion = Math.max(...data.conversion_por_galpon.map(x => x.promedio_conversion)) - 
                         Math.min(...data.conversion_por_galpon.map(x => x.promedio_conversion));
        
        if (variacion > 0.5) {
            observacionesList.innerHTML += '<li class="text-warning">Alta variabilidad entre galpones</li>';
            recomendacionesList.innerHTML += '<li class="text-warning">Investigar causas de la variabilidad y estandarizar procesos</li>';
        }
    }
}

function calcularEficiencia(conversion) {
    if (conversion <= 0) return { valor: 0, color: 'secondary' };
    
    const eficienciaBase = 1.5;
    const eficiencia = Math.min(100, Math.max(0, (eficienciaBase / conversion) * 100));
    
    let color = 'danger';
    if (eficiencia >= 80) color = 'success';
    else if (eficiencia >= 60) color = 'warning';
    else if (eficiencia >= 40) color = 'info';
    
    return { valor: eficiencia.toFixed(1), color: color };
}

function limpiarFiltrosReporte() {
    document.getElementById('filtrosReporte').reset();
    // Establecer fechas por defecto
    document.getElementById('fecha_inicio').value = '{{ now()->subDays(30)->format("Y-m-d") }}';
    document.getElementById('fecha_fin').value = '{{ now()->format("Y-m-d") }}';
    cargarReporte();
}

function mostrarLoading() {
    // Implementar indicador de carga si es necesario
    document.body.style.cursor = 'wait';
}

function ocultarLoading() {
    document.body.style.cursor = 'default';
}

function exportarPDF() {
    alert('Función de exportación PDF en desarrollo');
}

function exportarExcel() {
    alert('Función de exportación Excel en desarrollo');
}
</script>
@endsection
