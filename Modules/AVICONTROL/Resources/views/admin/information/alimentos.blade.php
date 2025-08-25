@extends('avicontrol::layouts.admin')

@section('title', 'Informes de Alimentos - AVICONTROL')

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
                    <i class="fas fa-utensils text-primary me-3"></i>
                    Informes de Alimentos
                </h2>
                <p class="text-muted mb-0">Sistema de control y análisis de alimentos para avicultura</p>
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
            <!-- (Se removieron botones globales no requeridos) -->

    <!-- Modal de Filtros de Exportación -->
    <div class="modal fade" id="modalFiltrosExportacion" tabindex="-1" aria-labelledby="modalFiltrosExportacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalFiltrosExportacionLabel">
                        <i class="fas fa-filter me-2"></i>Filtros de Exportación - Alimentos
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formFiltrosExportacion">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Selecciona el período:</strong> Elige el rango de fechas para tu reporte de consumo de alimentos.
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
                                <label for="productoFiltro" class="form-label">
                                    <i class="fas fa-box me-1"></i>Producto Específico
                                </label>
                                <select class="form-select" id="productoFiltro" name="producto_id">
                                    <option value="">Todos los productos</option>
                                    @if(isset($productos))
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}">{{ $producto->name ?? 'Producto ' . $producto->id }}</option>
                                        @endforeach
                                    @endif
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
                                    <option value="producto_asc">Producto (A-Z)</option>
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

            <!-- Resumen de Costos y Consumo -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Estadísticas en Tiempo Real:</strong> Las estadísticas se calculan automáticamente basándose en los datos filtrados y se actualizan en tiempo real.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="total-gasto">${{ number_format($estadisticasAlimentos['total_gasto'] ?? 0, 2) }}</h3>
                            <p>Total Gastado</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="promedio-galpon">${{ number_format($estadisticasAlimentos['promedio_galpon'] ?? 0, 2) }}</h3>
                            <p>Promedio por Galpón</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="total-kilos">{{ number_format($estadisticasAlimentos['total_kilos'] ?? 0) }} kg</h3>
                            <p>Total Kilogramos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-weight-hanging"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="costo-ave">${{ number_format($estadisticasAlimentos['costo_ave'] ?? 0, 2) }}</h3>
                            <p>Costo por Ave</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-kiwi-bird"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3 id="total-consumo-kg">0 kg</h3>
                            <p>Total Consumido</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3 id="promedio-consumo-ave">0 g</h3>
                            <p>Promedio/Ave</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Adicionales -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="text-warning">Consumo del Día</h5>
                            <h3 class="text-warning" id="consumo-hoy">{{ number_format($estadisticasAlimentos['consumo_hoy'] ?? 0, 1) }} kg</h3>
                            <small class="text-muted">Kg consumidos hoy</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="text-info">Consumo del Mes</h5>
                            <h3 class="text-info" id="consumo-mes">{{ number_format($estadisticasAlimentos['consumo_mes'] ?? 0, 1) }} kg</h3>
                            <small class="text-muted">Kg consumidos este mes</small>
                        </div>
                    </div>
                </div>
            </div>

            

            <!-- Tabla de Alimentos -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-utensils mr-2"></i>
                        Registros de Alimentos por Galpón
                    </h3>
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
                                    <th>PDF</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($datosAlimentos) && count($datosAlimentos) > 0)
                                    @foreach($datosAlimentos as $alimento)
                                    <tr data-id="{{ $alimento['id'] ?? 'null' }}">
                                        <td>{{ $alimento['fecha'] ? $alimento['fecha']->format('d/m/Y') : 'N/A' }}</td>
                                        <td>{{ $alimento['galpon'] }}</td>
                                        <td>{{ $alimento['insumo'] }}</td>
                                        <td>{{ number_format($alimento['cantidad'], 1) }}</td>
                                        <td>${{ number_format($alimento['precio_unitario'], 2) }}</td>
                                        <td>${{ number_format($alimento['total'], 2) }}</td>
                                        <td>{{ $alimento['proveedor'] }}</td>
                                        <td>{{ $alimento['observaciones'] }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger btn-pdf" data-id="{{ $alimento['id'] ?? 'null' }}" title="Descargar PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                                                @else
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">
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
                <div class="col-md-4">
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
                <div class="col-md-4">
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

                <!-- Gráfica de Consumo por Galpón -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-doughnut mr-2"></i>
                                Consumo por Galpón
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="grafica-consumo-galpon" style="height: 300px;"></canvas>
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
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    console.log('=== AVICONTROL ALIMENTOS - INICIANDO ===');
    
    // Inicializar la página
    inicializarPagina();
    
    // Event Listeners
    configurarEventListeners();
    
    // Funciones principales
    function inicializarPagina() {
        // Cargar estadísticas iniciales
        actualizarEstadisticasDesdeBackend();
        
        // Generar gráficas si hay datos
        setTimeout(() => {
            const datos = obtenerDatosDeTabla();
            if (datos && datos.length > 0) {
                generarGraficas(datos);
            }
        }, 1000);
    }
    
    function configurarEventListeners() {
        // Botón PDF individual por registro
        $(document).on('click', '.btn-pdf', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            console.log('Generando PDF para ID:', id);
            
            if (!id || id === 'null' || id === 'undefined') {
                mostrarNotificacion('ID de registro no válido', 'error');
                return;
            }
            
            // Mostrar estado de carga
            const $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            // Generar y descargar PDF
            generarPDFIndividual(id, $btn);
        });
        
        // Modal de filtros de exportación
        let formatoSeleccionado = '';
        
        // Capturar el formato cuando se abre el modal
        $('#exportar-excel, #exportar-pdf').on('click', function() {
            formatoSeleccionado = $(this).data('formato');
            $('#modalFiltrosExportacionLabel').html(`<i class="fas fa-filter me-2"></i>Filtros de Exportación - Alimentos - ${formatoSeleccionado.toUpperCase()}`);
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
        $('#tipoPeriodo, #productoFiltro, #galponFiltro, #ordenamiento, #fechaInicio, #fechaFin').on('change', function() {
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
    
    function generarPDFIndividual(id, $btn) {
        try {
            // Mostrar estado de carga
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            // Construir URL para visualizar (sin download) y abrir en nueva pestaña
            const url = `{{ route('avicontrol.admin.information.alimentos.pdf_individual', ['id' => ':id']) }}`.replace(':id', id);
            console.log('Abriendo informe en nueva pestaña:', url);
            
            // Abrir en nueva pestaña/ventana para que el navegador renderice
            window.open(url, '_blank');
            
        } catch (error) {
            console.error('Error generando PDF:', error);
            mostrarNotificacion('Error generando PDF', 'error');
        } finally {
            // Restaurar botón después de un pequeño delay
            setTimeout(() => {
                $btn.prop('disabled', false).html('<i class=\"fas fa-file-pdf\"></i>');
            }, 1200);
        }
    }
    
    function generarPDFPorRango(desde, hasta) {
        try {
            const url = `{{ route('avicontrol.admin.information.alimentos.pdf') }}?rango=1&desde=${encodeURIComponent(desde)}&hasta=${encodeURIComponent(hasta)}&download=1`;
            console.log('Descargando PDF por rango desde:', url);
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalPdfRango'));
            if (modal) modal.hide();
            
            // Descargar PDF
            window.location.href = url;
            
        } catch (error) {
            console.error('Error generando PDF por rango:', error);
            mostrarNotificacion('Error generando PDF por rango', 'error');
        }
    }
    
    function ejecutarTestPDF() {
        console.log('=== TEST PDF EJECUTADO ===');
        
        // Verificar rutas
        console.log('Ruta PDF individual:', '{{ route("avicontrol.admin.information.alimentos.pdf_individual", ["id" => 1]) }}');
        console.log('Ruta PDF rango:', '{{ route("avicontrol.admin.information.alimentos.pdf") }}');
        
        // Verificar tabla
        const filas = $('#tabla-alimentos tbody tr');
        console.log('Filas en tabla:', filas.length);
        
        filas.each(function(index) {
            const id = $(this).data('id');
            const botonPdf = $(this).find('.btn-pdf');
            console.log(`Fila ${index + 1}: ID=${id}, Botón PDF existe=${botonPdf.length > 0}`);
        });
        
        mostrarNotificacion('Test completado - Revisa la consola', 'info');
    }
    
    function actualizarEstadisticasDesdeBackend() {
        // Las estadísticas ya vienen del backend en la vista
        console.log('Estadísticas cargadas desde backend');
    }
    
    function obtenerDatosDeTabla() {
        const filas = $('#tabla-alimentos tbody tr');
        if (filas.length === 0) return null;
        
        const datos = [];
        filas.each(function() {
            const celdas = $(this).find('td');
            if (celdas.length >= 6) {
                const dato = {
                    id: $(this).data('id'),
                    fecha: celdas.eq(0).text(),
                    galpon: celdas.eq(1).text(),
                    insumo: celdas.eq(2).text(),
                    cantidad: parseFloat(celdas.eq(3).text().replace(/[^\d.-]/g, '')) || 0,
                    precio_unitario: parseFloat(celdas.eq(4).text().replace(/[^\d.-]/g, '')) || 0,
                    total: parseFloat(celdas.eq(5).text().replace(/[^\d.-]/g, '')) || 0,
                    proveedor: celdas.eq(6).text(),
                    observaciones: celdas.eq(7).text()
                };
                datos.push(dato);
            }
        });
        
        return datos;
    }
    
    function generarGraficas(datos) {
        if (!datos || datos.length === 0) return;
        
        generarGraficaCostosGalpon(datos);
        generarGraficaConsumoInsumo(datos);
        generarGraficaEvolucionCostos(datos);
    }
    
    function generarGraficaCostosGalpon(datos) {
        const ctx = document.getElementById('grafica-costos-galpon');
        if (!ctx) return;
        
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
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    function generarGraficaConsumoInsumo(datos) {
        const ctx = document.getElementById('grafica-consumo-insumo');
        if (!ctx) return;
        
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
                scales: { y: { beginAtZero: true } }
            }
        });
    }
    
    function generarGraficaEvolucionCostos(datos) {
        const ctx = document.getElementById('grafica-evolucion-costos');
        if (!ctx) return;
        
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
                labels: fechas,
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
                scales: { y: { beginAtZero: true } }
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
    
    // Funciones para el sistema de filtros
    function exportarDatosConFiltros(formato, filtros) {
        mostrarNotificacion(`Iniciando exportación a ${formato.toUpperCase()} con filtros...`, 'info');
        
        const btn = $(`#exportar-${formato}`);
        const originalText = btn.html();
        btn.html(`<i class="fas fa-spinner fa-spin me-2"></i>Exportando...`);
        btn.prop('disabled', true);
        
        // Construir URL con parámetros de filtro
        const baseUrl = formato === 'excel' 
            ? '{{ route("avicontrol.admin.information.alimentos.excel") }}'
            : '{{ route("avicontrol.admin.information.alimentos.pdf") }}';
        
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
            producto_id: $('#productoFiltro').val(),
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
        
        // Producto
        if (filtros.producto_id) {
            const productoTexto = $('#productoFiltro option:selected').text();
            resumen.push(`<strong>Producto:</strong> ${productoTexto}`);
        } else {
            resumen.push(`<strong>Producto:</strong> Todos los productos`);
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
    
    console.log('=== AVICONTROL ALIMENTOS - INICIALIZADO ===');
});
</script>
@endpush

@push('styles')
<style>
/* Estilos optimizados para AVICONTROL Alimentos */

/* Page Header */
.page-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
}

.page-title {
    color: #495057;
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
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
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
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

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.btn-sm {
    margin-left: 8px;
    padding: 8px 16px;
    font-size: 0.875rem;
}

/* Botón de prueba */
#btn-test-pdf {
    transition: all 0.3s ease;
    border: 2px dashed #ffc107;
}

#btn-test-pdf:hover {
    background: #ffc107;
    color: #000;
    transform: scale(1.05);
    border-style: solid;
}

/* Botones de acciones en tabla */
.btn-pdf {
    margin: 0 2px;
    transition: all 0.3s ease;
}

.btn-pdf:hover {
    background-color: #c82333;
    transform: translateY(-2px);
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

/* Notificaciones */
.alert {
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    border: none;
    font-weight: 500;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    color: #0c5460;
    border-left: 4px solid #17a2b8;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
    border-left: 4px solid #ffc107;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .page-title {
        font-size: 1.25rem;
        text-align: center;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .small-box .inner {
        padding: 15px;
        text-align: center;
    }
    
    .table th,
    .table td {
        padding: 8px 6px;
        font-size: 0.75rem;
    }
    
    .btn-sm {
        padding: 6px 10px;
        font-size: 0.75rem;
        margin-left: 3px;
        margin-bottom: 5px;
    }
    
    canvas {
        height: 250px !important;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 12px;
    }
    
    .page-title {
        font-size: 1.1rem;
    }
    
    .card-header {
        padding: 12px 15px;
    }
    
    .card-body {
        padding: 12px;
    }
    
    .btn {
        padding: 6px 12px;
        font-size: 0.8rem;
    }
    
    .btn-sm {
        padding: 5px 8px;
        font-size: 0.7rem;
    }
    
    canvas {
        height: 200px !important;
    }
}
</style>
@endpush
@endsection
