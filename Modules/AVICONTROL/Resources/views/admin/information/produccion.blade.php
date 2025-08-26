@extends('avicontrol::layouts.admin')

@section('title', 'Informes de Producción - AVICONTROL')

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
                    <i class="fas fa-egg text-primary me-3"></i>
                    Informes de Producción
                </h2>
                <p class="text-muted mb-0">Sistema de control y análisis de producción avícola</p>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success btn-sm" id="exportar-excel" data-bs-toggle="modal" data-bs-target="#modalFiltrosExportacion" data-formato="excel">
                        <i class="fas fa-file-excel me-2"></i>Exportar Excel
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="exportar-pdf" data-bs-toggle="modal" data-bs-target="#modalFiltrosExportacion" data-formato="pdf">
                        <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Estadísticas de Producción -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Estadísticas en Tiempo Real:</strong> Las estadísticas se calculan automáticamente basándose en los datos de producción y se actualizan en tiempo real.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="total-produccion">{{ number_format($estadisticasProduccion['total_produccion'] ?? 0) }}</h3>
                    <p>Total Producción</p>
                </div>
                <div class="icon">
                    <i class="fas fa-egg"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="produccion-hoy">{{ number_format($estadisticasProduccion['produccion_hoy'] ?? 0) }}</h3>
                    <p>Producción Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="produccion-mes">{{ number_format($estadisticasProduccion['produccion_mes'] ?? 0) }}</h3>
                    <p>Producción del Mes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3 id="promedio-diario">{{ number_format($estadisticasProduccion['promedio_diario'] ?? 0) }}</h3>
                    <p>Promedio Diario</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 id="mejor-galpon">{{ $estadisticasProduccion['mejor_galpon'] ?? 'N/A' }}</h3>
                    <p>Mejor Galpón</p>
                </div>
                <div class="icon">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3 id="aves-activas">{{ number_format($estadisticasProduccion['total_aves_activas'] ?? 0) }}</h3>
                    <p>Aves Activas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-kiwi-bird"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Detalladas del Día -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-success">Huevos Buenos Hoy</h5>
                    <h3 class="text-success" id="huevos-buenos-hoy">{{ number_format($estadisticasProduccion['huevos_buenos_hoy'] ?? 0) }}</h3>
                    <small class="text-muted">Unidades de calidad</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-danger">Huevos Rotos Hoy</h5>
                    <h3 class="text-danger" id="huevos-rotos-hoy">{{ number_format($estadisticasProduccion['huevos_rotos_hoy'] ?? 0) }}</h3>
                    <small class="text-muted">Pérdidas del día</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-warning">Huevos Sucios Hoy</h5>
                    <h3 class="text-warning" id="huevos-sucios-hoy">{{ number_format($estadisticasProduccion['huevos_sucios_hoy'] ?? 0) }}</h3>
                    <small class="text-muted">Requieren limpieza</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-info">Valor Total Hoy</h5>
                    <h3 class="text-info" id="valor-total-hoy">${{ number_format($estadisticasProduccion['valor_total_hoy'] ?? 0, 0, ',', '.') }}</h3>
                    <small class="text-muted">Ingresos estimados</small>
                </div>
            </div>
        </div>
    </div>

            <!-- Tabla de Producción -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-2"></i>
                        Registros de Producción
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="tabla-produccion">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo Producción</th>
                                    <th>Galpón</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Mortalidad</th>
                                    <th>Huevos Buenos</th>
                                    <th>Huevos Rotos</th>
                                    <th>Huevos Sucios</th>
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
                                @if(isset($datosProduccion) && count($datosProduccion) > 0)
                                    @foreach($datosProduccion as $produccion)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $produccion['fecha'] ? $produccion['fecha']->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ ($produccion['tipo_produccion'] ?? 'huevos') === 'huevos' ? 'warning text-dark' : 'danger' }}">
                                                {{ ($produccion['tipo_produccion'] ?? 'huevos') === 'huevos' ? 'Huevos' : 'Carne' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $produccion['galpon'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $produccion['tipo'] == 'A' ? 'success' : ($produccion['tipo'] == 'AA' ? 'warning' : 'secondary') }}">
                                                {{ $produccion['tipo'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ number_format($produccion['cantidad'] ?? $produccion['produccion'] ?? 0) }}</strong>
                                        </td>
                                        <td>
                                            @if(($produccion['mortalidad_aves'] ?? 0) > 0)
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-heart-broken me-1"></i>
                                                    {{ number_format($produccion['mortalidad_aves']) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($produccion['tipo_produccion'] ?? 'huevos') === 'huevos')
                                                <span class="badge bg-success">{{ number_format($produccion['huevos_buenos'] ?? 0) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($produccion['tipo_produccion'] ?? 'huevos') === 'huevos')
                                                <span class="badge bg-danger">{{ number_format($produccion['huevos_rotos'] ?? 0) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($produccion['tipo_produccion'] ?? 'huevos') === 'huevos')
                                                <span class="badge bg-warning">{{ number_format($produccion['huevos_sucios'] ?? 0) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($produccion['tipo_produccion'] ?? 'huevos') === 'carne')
                                                {{ number_format($produccion['peso_promedio'] ?? 0, 2) }} kg
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($produccion['tipo_produccion'] ?? 'huevos') === 'carne')
                                                {{ number_format($produccion['peso_total'] ?? 0, 2) }} kg
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>${{ number_format($produccion['valor_unidad'] ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            <strong class="text-success">${{ number_format($produccion['valor_total'] ?? 0, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>{{ $produccion['destino'] ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $produccion['semana_produccion'] ?? $produccion['semana'] ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $produccion['estado'] == 'activo' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($produccion['estado'] ?? 'N/A') }}
                                            </span>
                                        </td>

                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="16" class="text-center text-muted">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No hay datos de producción disponibles
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <!-- Gráficas de Análisis -->
    <div class="row mb-4">
        <!-- Gráfica de Producción por Galpón -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Producción por Galpón
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="grafica-produccion-galpon" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfica de Evolución Diaria -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-2"></i>
                        Evolución Diaria
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="grafica-evolucion-diaria" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfica de Calidad de Huevos -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-doughnut mr-2"></i>
                        Calidad de Huevos
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="grafica-calidad-huevos" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Análisis de Tendencias -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-area mr-2"></i>
                Evolución de Producción por Período
            </h3>
        </div>
        <div class="card-body">
            <canvas id="grafica-evolucion-produccion" style="height: 400px;"></canvas>
        </div>
    </div>

    <!-- Modal de Filtros de Exportación -->
    <div class="modal fade" id="modalFiltrosExportacion" tabindex="-1" aria-labelledby="modalFiltrosExportacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalFiltrosExportacionLabel">
                        <i class="fas fa-filter me-2"></i>Filtros de Exportación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formFiltrosExportacion">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Selecciona el período:</strong> Elige el rango de fechas para tu reporte de producción.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipoPeriodo" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>Tipo de Período
                                </label>
                                <select class="form-select" id="tipoPeriodo" name="tipo_periodo" required>
                                    <option value="">Seleccionar período...</option>
                                    <option value="todo">📊 Todos los datos</option>
                                    <option value="hoy">📅 Solo hoy</option>
                                    <option value="semanal">📅 Esta semana</option>
                                    <option value="mensual">📅 Este mes</option>
                                    <option value="anual">📅 Este año</option>
                                    <option value="personalizado">🎯 Rango personalizado</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tipoProduccion" class="form-label">
                                    <i class="fas fa-egg me-1"></i>Tipo de Producción
                                </label>
                                <select class="form-select" id="tipoProduccion" name="tipo_produccion">
                                    <option value="">Todos los tipos</option>
                                    <option value="huevos">🥚 Solo huevos</option>
                                    <option value="carne">🍗 Solo carne</option>
                                </select>
                            </div>
                        </div>

                        <!-- Campos de fecha personalizada (ocultos por defecto) -->
                        <div class="row" id="camposFechaPersonalizada" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="fechaInicio" class="form-label">
                                    <i class="fas fa-calendar-plus me-1"></i>Fecha Inicio
                                </label>
                                <input type="date" class="form-control" id="fechaInicio" name="fecha_inicio">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fechaFin" class="form-label">
                                    <i class="fas fa-calendar-minus me-1"></i>Fecha Fin
                                </label>
                                <input type="date" class="form-control" id="fechaFin" name="fecha_fin">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="galponFiltro" class="form-label">
                                    <i class="fas fa-home me-1"></i>Galpón Específico
                                </label>
                                <select class="form-select" id="galponFiltro" name="galpon_id">
                                    <option value="">Todos los galpones</option>
                                    @if(isset($galpones))
                                        @foreach($galpones as $galpon)
                                            <option value="{{ $galpon->id }}">{{ $galpon->name ?? 'Galpón ' . $galpon->id }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ordenamiento" class="form-label">
                                    <i class="fas fa-sort me-1"></i>Ordenar por
                                </label>
                                <select class="form-select" id="ordenamiento" name="ordenamiento">
                                    <option value="fecha_desc">Fecha (más reciente primero)</option>
                                    <option value="fecha_asc">Fecha (más antiguo primero)</option>
                                    <option value="cantidad_desc">Cantidad (mayor a menor)</option>
                                    <option value="cantidad_asc">Cantidad (menor a mayor)</option>
                                    <option value="galpon_asc">Galpón (A-Z)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Resumen de filtros -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-eye me-1"></i>Vista previa de filtros:
                                        </h6>
                                        <div id="resumenFiltros" class="text-muted">
                                            Selecciona un período para ver el resumen...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" id="aplicarFiltrosYExportar">
                        <i class="fas fa-download me-1"></i>Exportar con Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    console.log('=== AVICONTROL PRODUCCIÓN - INICIANDO ===');
    
    // Inicializar la página
    inicializarPagina();
    
    // Event Listeners
    configurarEventListeners();
    
    // Funciones principales
    function inicializarPagina() {
        // Generar gráficas si hay datos
        setTimeout(() => {
            const datos = obtenerDatosDeTabla();
            if (datos && datos.length > 0) {
                generarGraficas(datos);
            }
        }, 1000);
    }
    
    function configurarEventListeners() {
        // Event listener para generar PDF individual
        $(document).on('click', '.generar-pdf', function() {
            const produccionId = $(this).data('produccion-id');
            const fecha = $(this).data('fecha');
            const galpon = $(this).data('galpon');
            
            generarPDFIndividual(produccionId, fecha, galpon);
        });
        
        // Modal de filtros de exportación
        let formatoSeleccionado = '';
        
        // Capturar el formato cuando se abre el modal
        $('#exportar-excel, #exportar-pdf').on('click', function() {
            formatoSeleccionado = $(this).data('formato');
            $('#modalFiltrosExportacionLabel').html(`<i class="fas fa-filter me-2"></i>Filtros de Exportación - ${formatoSeleccionado.toUpperCase()}`);
        });
        
        // Mostrar/ocultar campos de fecha personalizada
        $('#tipoPeriodo').on('change', function() {
            const valor = $(this).val();
            if (valor === 'personalizado') {
                $('#camposFechaPersonalizada').show();
                $('#fechaInicio, #fechaFin').prop('required', true);
            } else {
                $('#camposFechaPersonalizada').hide();
                $('#fechaInicio, #fechaFin').prop('required', false);
            }
            actualizarResumenFiltros();
        });
        
        // Actualizar resumen cuando cambien los filtros
        $('#tipoPeriodo, #tipoProduccion, #galponFiltro, #ordenamiento, #fechaInicio, #fechaFin').on('change', function() {
            actualizarResumenFiltros();
        });
        
        // Aplicar filtros y exportar
        $('#aplicarFiltrosYExportar').on('click', function() {
            const filtros = obtenerFiltrosSeleccionados();
            if (validarFiltros(filtros)) {
                $('#modalFiltrosExportacion').modal('hide');
                exportarDatosConFiltros(formatoSeleccionado, filtros);
            }
        });
    }
    
    function generarPDFIndividual(produccionId, fecha, galpon) {
        // Mostrar indicador de carga
        const btn = $(`.generar-pdf[data-produccion-id="${produccionId}"]`);
        const originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Generando...');
        btn.prop('disabled', true);
        
        // Hacer petición AJAX para generar PDF
        $.ajax({
            url: '{{ route("avicontrol.admin.information.produccion.pdf") }}',
            method: 'POST',
            data: {
                produccion_id: produccionId,
                fecha: fecha,
                galpon: galpon,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Descargar el PDF
                    const link = document.createElement('a');
                    link.href = response.pdf_url;
                    link.download = `produccion_${galpon}_${fecha}.pdf`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Mostrar mensaje de éxito
                    mostrarNotificacion('PDF generado exitosamente', 'success');
                } else {
                    mostrarNotificacion('Error al generar PDF: ' + response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error generando PDF:', error);
                mostrarNotificacion('Error al generar PDF. Intente nuevamente.', 'error');
            },
            complete: function() {
                // Restaurar botón
                btn.html(originalText);
                btn.prop('disabled', false);
            }
        });
    }
    
    function exportarDatos(formato) {
        mostrarNotificacion(`Iniciando exportación a ${formato.toUpperCase()}...`, 'info');
        
        const btn = $(`#exportar-${formato}`);
        const originalText = btn.html();
        btn.html(`<i class="fas fa-spinner fa-spin me-2"></i>Exportando...`);
        btn.prop('disabled', true);
        
        const url = formato === 'excel' 
            ? '{{ route("avicontrol.admin.information.produccion.excel") }}'
            : '{{ route("avicontrol.admin.information.produccion.pdf") }}';
        
        // Para ambos formatos, usar descarga directa mediante window.location
        setTimeout(() => {
            window.location.href = url;
            mostrarNotificacion(`${formato.toUpperCase()} descargado exitosamente`, 'success');
            
            // Restaurar botón después de un momento
            setTimeout(() => {
                btn.html(originalText);
                btn.prop('disabled', false);
            }, 2000);
        }, 500);
    }
    
    function exportarDatosConFiltros(formato, filtros) {
        mostrarNotificacion(`Iniciando exportación a ${formato.toUpperCase()} con filtros...`, 'info');
        
        const btn = $(`#exportar-${formato}`);
        const originalText = btn.html();
        btn.html(`<i class="fas fa-spinner fa-spin me-2"></i>Exportando...`);
        btn.prop('disabled', true);
        
        // Construir URL con parámetros de filtro
        const baseUrl = formato === 'excel' 
            ? '{{ route("avicontrol.admin.information.produccion.excel") }}'
            : '{{ route("avicontrol.admin.information.produccion.pdf") }}';
        
        const params = new URLSearchParams();
        Object.keys(filtros).forEach(key => {
            if (filtros[key] && filtros[key] !== '') {
                params.append(key, filtros[key]);
            }
        });
        
        const urlConFiltros = `${baseUrl}?${params.toString()}`;
        
        setTimeout(() => {
            window.location.href = urlConFiltros;
            mostrarNotificacion(`${formato.toUpperCase()} con filtros descargado exitosamente`, 'success');
            
            setTimeout(() => {
                btn.html(originalText);
                btn.prop('disabled', false);
            }, 2000);
        }, 500);
    }
    
    function obtenerFiltrosSeleccionados() {
        return {
            tipo_periodo: $('#tipoPeriodo').val(),
            tipo_produccion: $('#tipoProduccion').val(),
            galpon_id: $('#galponFiltro').val(),
            ordenamiento: $('#ordenamiento').val(),
            fecha_inicio: $('#fechaInicio').val(),
            fecha_fin: $('#fechaFin').val()
        };
    }
    
    function validarFiltros(filtros) {
        if (!filtros.tipo_periodo) {
            mostrarNotificacion('Por favor selecciona un tipo de período', 'error');
            return false;
        }
        
        if (filtros.tipo_periodo === 'personalizado') {
            if (!filtros.fecha_inicio || !filtros.fecha_fin) {
                mostrarNotificacion('Para rango personalizado debes especificar fecha de inicio y fin', 'error');
                return false;
            }
            
            if (new Date(filtros.fecha_inicio) > new Date(filtros.fecha_fin)) {
                mostrarNotificacion('La fecha de inicio debe ser anterior a la fecha de fin', 'error');
                return false;
            }
        }
        
        return true;
    }
    
    function actualizarResumenFiltros() {
        const filtros = obtenerFiltrosSeleccionados();
        let resumen = [];
        
        // Período
        if (filtros.tipo_periodo) {
            const periodos = {
                'todo': '📊 Todos los datos disponibles',
                'hoy': '📅 Solo registros de hoy',
                'semanal': '📅 Registros de esta semana',
                'mensual': '📅 Registros de este mes',
                'anual': '📅 Registros de este año',
                'personalizado': `🎯 Del ${filtros.fecha_inicio || '...'} al ${filtros.fecha_fin || '...'}`
            };
            resumen.push(`<strong>Período:</strong> ${periodos[filtros.tipo_periodo]}`);
        }
        
        // Tipo de producción
        if (filtros.tipo_produccion) {
            const tipos = {
                'huevos': '🥚 Solo producción de huevos',
                'carne': '🍗 Solo producción de carne'
            };
            resumen.push(`<strong>Tipo:</strong> ${tipos[filtros.tipo_produccion]}`);
        } else {
            resumen.push(`<strong>Tipo:</strong> Todos los tipos de producción`);
        }
        
        // Galpón
        if (filtros.galpon_id) {
            const galponTexto = $('#galponFiltro option:selected').text();
            resumen.push(`<strong>Galpón:</strong> ${galponTexto}`);
        } else {
            resumen.push(`<strong>Galpón:</strong> Todos los galpones`);
        }
        
        // Ordenamiento
        if (filtros.ordenamiento) {
            const ordenTexto = $('#ordenamiento option:selected').text();
            resumen.push(`<strong>Orden:</strong> ${ordenTexto}`);
        }
        
        const resumenHtml = resumen.length > 0 
            ? resumen.join('<br>') 
            : 'Selecciona un período para ver el resumen...';
        
        $('#resumenFiltros').html(resumenHtml);
    }
    
    function obtenerDatosDeTabla() {
        const filas = $('#tabla-produccion tbody tr');
        if (filas.length === 0) return null;
        
        const datos = [];
        filas.each(function() {
            const celdas = $(this).find('td');
            if (celdas.length >= 16) {
                const dato = {
                    fecha: celdas.eq(0).text().trim(),
                    tipo_produccion: celdas.eq(1).text().trim(),
                    galpon: celdas.eq(2).text().trim(),
                    tipo: celdas.eq(3).text().trim(),
                    cantidad: parseInt(celdas.eq(4).text().replace(/[^\d]/g, '')) || 0,
                    mortalidad: parseInt(celdas.eq(5).text().replace(/[^\d]/g, '')) || 0,
                    huevos_buenos: parseInt(celdas.eq(6).text().replace(/[^\d]/g, '')) || 0,
                    huevos_rotos: parseInt(celdas.eq(7).text().replace(/[^\d]/g, '')) || 0,
                    huevos_sucios: parseInt(celdas.eq(8).text().replace(/[^\d]/g, '')) || 0,
                    valor_total: parseFloat(celdas.eq(12).text().replace(/[^\d.-]/g, '')) || 0
                };
                datos.push(dato);
            }
        });
        
        return datos;
    }
    
    function generarGraficas(datos) {
        if (!datos || datos.length === 0) return;
        
        generarGraficaProduccionGalpon(datos);
        generarGraficaEvolucionDiaria(datos);
        generarGraficaCalidadHuevos(datos);
        generarGraficaEvolucionProduccion(datos);
    }
    
    function generarGraficaProduccionGalpon(datos) {
        const ctx = document.getElementById('grafica-produccion-galpon');
        if (!ctx) return;
        
        if (window.graficaProduccionGalpon) {
            window.graficaProduccionGalpon.destroy();
        }
        
        const galpones = [...new Set(datos.map(d => d.galpon))];
        const produccion = galpones.map(galpon => {
            const galponDatos = datos.filter(d => d.galpon === galpon);
            return galponDatos.reduce((sum, d) => sum + d.cantidad, 0);
        });
        
        window.graficaProduccionGalpon = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: galpones,
                datasets: [{
                    data: produccion,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
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
    }
    
    function generarGraficaEvolucionDiaria(datos) {
        const ctx = document.getElementById('grafica-evolucion-diaria');
        if (!ctx) return;
        
        if (window.graficaEvolucionDiaria) {
            window.graficaEvolucionDiaria.destroy();
        }
        
        const fechas = [...new Set(datos.map(d => d.fecha))].sort();
        const produccionDiaria = fechas.map(fecha => {
            const fechaDatos = datos.filter(d => d.fecha === fecha);
            return fechaDatos.reduce((sum, d) => sum + d.cantidad, 0);
        });
        
        window.graficaEvolucionDiaria = new Chart(ctx, {
            type: 'line',
            data: {
                labels: fechas,
                datasets: [{
                    label: 'Producción Diaria',
                    data: produccionDiaria,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
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
    
    function generarGraficaCalidadHuevos(datos) {
        const ctx = document.getElementById('grafica-calidad-huevos');
        if (!ctx) return;
        
        if (window.graficaCalidadHuevos) {
            window.graficaCalidadHuevos.destroy();
        }
        
        const huevosHoy = datos.filter(d => d.tipo_produccion === 'Huevos');
        const totalBuenos = huevosHoy.reduce((sum, d) => sum + d.huevos_buenos, 0);
        const totalRotos = huevosHoy.reduce((sum, d) => sum + d.huevos_rotos, 0);
        const totalSucios = huevosHoy.reduce((sum, d) => sum + d.huevos_sucios, 0);
        
        window.graficaCalidadHuevos = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Buenos', 'Rotos', 'Sucios'],
                datasets: [{
                    data: [totalBuenos, totalRotos, totalSucios],
                    backgroundColor: ['#28a745', '#dc3545', '#ffc107']
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
    }
    
    function generarGraficaEvolucionProduccion(datos) {
        const ctx = document.getElementById('grafica-evolucion-produccion');
        if (!ctx) return;
        
        if (window.graficaEvolucionProduccion) {
            window.graficaEvolucionProduccion.destroy();
        }
        
        const fechas = [...new Set(datos.map(d => d.fecha))].sort();
        const produccionHuevos = fechas.map(fecha => {
            const fechaDatos = datos.filter(d => d.fecha === fecha && d.tipo_produccion === 'Huevos');
            return fechaDatos.reduce((sum, d) => sum + d.cantidad, 0);
        });
        
        const produccionCarne = fechas.map(fecha => {
            const fechaDatos = datos.filter(d => d.fecha === fecha && d.tipo_produccion === 'Carne');
            return fechaDatos.reduce((sum, d) => sum + d.cantidad, 0);
        });
        
        window.graficaEvolucionProduccion = new Chart(ctx, {
            type: 'line',
            data: {
                labels: fechas,
                datasets: [
                    {
                        label: 'Producción de Huevos',
                        data: produccionHuevos,
                        borderColor: 'rgba(255, 206, 86, 1)',
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        tension: 0.1
                    },
                    {
                        label: 'Producción de Carne',
                        data: produccionCarne,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1
                    }
                ]
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
    
    function mostrarNotificacion(mensaje, tipo = 'info') {
        const notificacion = $(`
            <div class="alert alert-${tipo} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('body').append(notificacion);
        
        setTimeout(() => {
            notificacion.fadeOut(() => notificacion.remove());
        }, 5000);
    }
    
    console.log('=== AVICONTROL PRODUCCIÓN - INICIALIZADO ===');
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
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0,0,0,0.15);
}

.btn-sm {
    margin-left: 5px;
    padding: 6px 12px;
    font-size: 0.8rem;
}

/* Badges */
.badge {
    font-size: 0.75rem;
    padding: 6px 10px;
    border-radius: 6px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

/* Botones de acción */
.btn-group {
    display: flex;
    gap: 5px;
}

.btn-group .btn {
    flex: 1;
    min-width: 80px;
}

/* Notificaciones toast */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border: none;
    display: none;
}

.toast-success {
    border-left: 4px solid #28a745;
}

.toast-error {
    border-left: 4px solid #dc3545;
}

.toast-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 12px 15px;
    border-radius: 8px 8px 0 0;
    display: flex;
    align-items: center;
}

.toast-header .btn-close {
    margin-left: auto;
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    color: #6c757d;
}

.toast-header .btn-close:hover {
    color: #495057;
}

.toast-body {
    padding: 15px;
    color: #495057;
}

.toast-success .toast-header i {
    color: #28a745;
}

.toast-error .toast-header i {
    color: #dc3545;
}

/* Indicador de carga */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush
@endsection
