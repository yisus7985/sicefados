@extends('avicontrol::layouts.admin')

@section('title', 'Informes de Producción - AVICONTROL')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Informes de Producción</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.welcome') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.information.index') }}">Información</a></li>
                        <li class="breadcrumb-item active">Producción</li>
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
                    <form id="filtro-produccion">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="galpon">Galpón</label>
                                    <select class="form-control" id="galpon" name="galpon">
                                        <option value="">Todos los galpones</option>
                                        <!-- Se llenará dinámicamente -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tipo_huevo">Tipo de Huevo</label>
                                    <select class="form-control" id="tipo_huevo" name="tipo_huevo">
                                        <option value="">Todos los tipos</option>
                                        <option value="A">Tipo A</option>
                                        <option value="AA">Tipo AA</option>
                                        <option value="B">Tipo B</option>
                                        <option value="C">Tipo C</option>
                                        <option value="D">Tipo D</option>
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
                            <div class="col-md-3">
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

            <!-- Resumen de Producción -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasProduccion['total_produccion'] ?? 0) }}</h3>
                            <p>Total Producción</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-egg"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasProduccion['promedio_diario'] ?? 0) }}</h3>
                            <p>Promedio Diario</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $estadisticasProduccion['mejor_galpon'] ?? 'N/A' }}</h3>
                            <p>Mejor Galpón</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($estadisticasProduccion['total_aves_activas'] ?? 0) }}</h3>
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
                <div class="col-12">
                    <div class="card bg-gradient-success">
                        <div class="card-header">
                            <h4 class="card-title text-white">
                                <i class="fas fa-calendar-day mr-2"></i>
                                PRODUCCIÓN DEL DÍA DE HOY - {{ now()->format('d/m/Y') }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center text-white">
                                        <h2 class="mb-0">{{ number_format($estadisticasProduccion['produccion_hoy'] ?? 0) }}</h2>
                                        <p class="mb-0">Huevos Totales</p>
                                        <small>Producidos hoy</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center text-white">
                                        <h2 class="mb-0">{{ number_format($estadisticasProduccion['huevos_buenos_hoy'] ?? 0) }}</h2>
                                        <p class="mb-0">Huevos Buenos</p>
                                        <small>Sin desperfectos</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center text-white">
                                        <h2 class="mb-0">{{ number_format($estadisticasProduccion['huevos_rotos_hoy'] ?? 0) }}</h2>
                                        <p class="mb-0">Huevos Rotos</p>
                                        <small>Pérdidas del día</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center text-white">
                                        <h2 class="mb-0">${{ number_format($estadisticasProduccion['valor_total_hoy'] ?? 0, 0, ',', '.') }}</h2>
                                        <p class="mb-0">Valor Total</p>
                                        <small>Ingresos del día</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Adicionales -->
            @if(isset($estadisticasProduccion['produccion_hoy']) || isset($estadisticasProduccion['produccion_mes']))
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="text-success">Producción del Día</h5>
                            <h3 class="text-success">{{ number_format($estadisticasProduccion['produccion_hoy'] ?? 0) }}</h3>
                            <small class="text-muted">Unidades producidas hoy</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5 class="text-info">Producción del Mes</h5>
                            <h3 class="text-info">{{ number_format($estadisticasProduccion['produccion_mes'] ?? 0) }}</h3>
                            <small class="text-muted">Unidades producidas este mes</small>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tabla de Producción -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-2"></i>
                        Registros de Producción
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-warning btn-sm" id="test-produccion">
                            <i class="fas fa-bug mr-2"></i>
                            Test Datos
                        </button>
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
                        <table class="table table-bordered table-striped" id="tabla-produccion">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Galpón</th>
                                    <th>Tipo</th>
                                    <th>Total Aves</th>
                                    <th>Producción Total</th>
                                    <th>Huevos Buenos</th>
                                    <th>Huevos Rotos</th>
                                    <th>Huevos Sucios</th>
                                    <th>Porcentaje</th>
                                    <th>Peso Promedio</th>
                                    <th>Peso Total</th>
                                    <th>Valor Unidad</th>
                                    <th>Valor Total</th>
                                    <th>Destino</th>
                                    <th>Semana</th>
                                    <th>Estado</th>
                                    <th width="120">Acciones</th>
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
                                            <span class="badge bg-info">{{ $produccion['galpon'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $produccion['tipo'] == 'A' ? 'success' : ($produccion['tipo'] == 'AA' ? 'warning' : 'secondary') }}">
                                                {{ $produccion['tipo'] }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($produccion['total_aves']) }}</td>
                                        <td>
                                            <strong class="text-primary">{{ number_format($produccion['produccion']) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ number_format($produccion['huevos_buenos'] ?? 0) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">{{ number_format($produccion['huevos_rotos'] ?? 0) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ number_format($produccion['huevos_sucios'] ?? 0) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $produccion['porcentaje'] > 90 ? 'success' : ($produccion['porcentaje'] > 80 ? 'warning' : 'danger') }}">
                                                {{ $produccion['porcentaje'] }}%
                                            </span>
                                        </td>
                                        <td>{{ number_format($produccion['peso_promedio'], 1) }} g</td>
                                        <td>{{ number_format($produccion['peso_total'] ?? 0, 1) }} kg</td>
                                        <td>${{ number_format($produccion['valor_unidad'] ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            <strong class="text-success">${{ number_format($produccion['valor_total'] ?? 0, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>{{ $produccion['destino'] ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $produccion['semana'] ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $produccion['estado'] == 'activo' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($produccion['estado'] ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('avicontrol.admin.information.galpon.detalle', ['galpon_id' => $produccion['galpon_id'] ?? 0]) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye mr-1"></i>
                                                Ver Detalle
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                                                            <td colspan="17" class="text-center text-muted">
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

            <!-- Estadísticas por Tipo de Huevo -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-layer-group mr-2"></i>
                                Producción por Tipo de Huevo - Hoy
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-3">
                                    <div class="border rounded p-3">
                                        <h5 class="text-success mb-1">{{ number_format($estadisticasProduccion['tipo_a_hoy'] ?? 0) }}</h5>
                                        <small class="text-muted">Tipo A</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border rounded p-3">
                                        <h5 class="text-warning mb-1">{{ number_format($estadisticasProduccion['tipo_aa_hoy'] ?? 0) }}</h5>
                                        <small class="text-muted">Tipo AA</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border rounded p-3">
                                        <h5 class="text-info mb-1">{{ number_format($estadisticasProduccion['tipo_b_hoy'] ?? 0) }}</h5>
                                        <small class="text-muted">Tipo B</small>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="border rounded p-3">
                                        <h5 class="text-secondary mb-1">{{ number_format($estadisticasProduccion['tipo_c_hoy'] ?? 0) }}</h5>
                                        <small class="text-muted">Tipo C</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-chart-pie mr-2"></i>
                                Resumen de Calidad - Hoy
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border rounded p-3 bg-success text-white">
                                        <h5 class="mb-1">{{ number_format($estadisticasProduccion['huevos_buenos_hoy'] ?? 0) }}</h5>
                                        <small>Buenos</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-3 bg-warning text-white">
                                        <h5 class="mb-1">{{ number_format($estadisticasProduccion['huevos_sucios_hoy'] ?? 0) }}</h5>
                                        <small>Sucios</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-3 bg-danger text-white">
                                        <h5 class="mb-1">{{ number_format($estadisticasProduccion['huevos_rotos_hoy'] ?? 0) }}</h5>
                                        <small>Rotos</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfica de Producción -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Gráfica de Producción por Galpón
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="grafica-produccion" style="height: 400px;"></canvas>
                </div>
            </div>
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
    
    // Event listeners
    $('#filtro-produccion').on('submit', function(e) {
        e.preventDefault();
        cargarDatosProduccion();
    });
    
    $('#limpiar-filtros').on('click', function() {
        $('#filtro-produccion')[0].reset();
        $('#fecha-fin').val(new Date().toISOString().split('T')[0]);
        $('#tipo_huevo').val('');
        cargarDatosProduccion();
    });
    
    $('#test-produccion').on('click', function() {
        testProduccion();
    });
    
    function cargarDatosIniciales() {
        console.log('Cargando datos iniciales...');
        // Cargar datos iniciales desde el servidor
        $.ajax({
            url: '{{ route("avicontrol.admin.information.produccion") }}',
            method: 'GET',
            data: { ajax: true, action: 'datos_iniciales' },
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                if (response.success) {
                    mostrarDatos(response.datos);
                    actualizarResumen(response.datos);
                    generarGrafica(response.datos);
                } else {
                    console.log('No hay datos iniciales, cargando datos estáticos...');
                    // Si no hay datos iniciales, mostrar datos estáticos de la vista
                    mostrarDatosEstaticos();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error cargando datos iniciales:', error);
                console.log('Cargando datos estáticos como fallback...');
                mostrarDatosEstaticos();
            }
        });
    }
    
    function cargarGalpones() {
        // Cargar galpones desde el backend
        $.ajax({
            url: '{{ route("avicontrol.admin.information.produccion") }}',
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
    
    function cargarDatosProduccion() {
        mostrarCargando();
        
        // Obtener parámetros del filtro
        const filtros = {
            galpon: $('#galpon').val(),
            tipo_huevo: $('#tipo_huevo').val(),
            fecha_inicio: $('#fecha-inicio').val(),
            fecha_fin: $('#fecha-fin').val(),
            ajax: true
        };
        
        // Hacer petición AJAX para obtener datos filtrados
        $.ajax({
            url: '{{ route("avicontrol.admin.information.produccion.filtrar") }}',
            method: 'POST',
            data: filtros,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    mostrarDatos(response.datos);
                    actualizarResumen(response.datos);
                    generarGrafica(response.datos);
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
        $('#tabla-produccion tbody').html('<tr><td colspan="7" class="text-center">Cargando datos...</td></tr>');
    }
    
    function mostrarDatos(datos) {
        console.log('Mostrando datos:', datos);
        console.log('Número de registros:', datos ? datos.length : 0);
        
        if (!datos || datos.length === 0) {
            $('#tabla-produccion tbody').html(`
                <tr>
                    <td colspan="17" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay datos disponibles para los filtros seleccionados
                    </td>
                </tr>
            `);
            return;
        }
        
        let html = '';
        datos.forEach((dato, index) => {
            console.log(`Procesando dato ${index}:`, dato);
            html += `
                <tr>
                    <td>
                        <span class="badge bg-primary">${formatearFecha(dato.fecha)}</span>
                    </td>
                    <td>
                        <span class="badge bg-info">${dato.galpon}</span>
                    </td>
                    <td>
                        <span class="badge bg-${dato.tipo == 'A' ? 'success' : (dato.tipo == 'AA' ? 'warning' : 'secondary')}">${dato.tipo || 'N/A'}</span>
                    </td>
                    <td>${dato.total_aves.toLocaleString()}</td>
                    <td>
                        <strong class="text-primary">${dato.produccion.toLocaleString()}</strong>
                    </td>
                    <td>
                        <span class="badge bg-success">${(dato.huevos_buenos || 0).toLocaleString()}</span>
                    </td>
                    <td>
                        <span class="badge bg-danger">${(dato.huevos_rotos || 0).toLocaleString()}</span>
                    </td>
                    <td>
                        <span class="badge bg-warning">${(dato.huevos_sucios || 0).toLocaleString()}</span>
                    </td>
                    <td>
                        <span class="badge bg-${dato.porcentaje > 90 ? 'success' : (dato.porcentaje > 80 ? 'warning' : 'danger')}">
                            ${dato.porcentaje}%
                        </span>
                    </td>
                    <td>${dato.peso_promedio} g</td>
                    <td>${(dato.peso_total || 0).toFixed(1)} kg</td>
                    <td>$${(dato.valor_unidad || 0).toLocaleString()}</td>
                    <td>
                        <strong class="text-success">$${(dato.valor_total || 0).toLocaleString()}</strong>
                    </td>
                    <td>${dato.destino || 'N/A'}</td>
                    <td>
                        <span class="badge bg-info">${dato.semana || 'N/A'}</span>
                    </td>
                    <td>
                        <span class="badge bg-${dato.estado == 'activo' ? 'success' : 'secondary'}">${(dato.estado || 'N/A').charAt(0).toUpperCase() + (dato.estado || 'N/A').slice(1)}</span>
                    </td>
                    <td>
                        <a href="/avicontrol/admin/information/galpon/${dato.galpon_id || 0}/detalle" 
                           class="btn btn-info btn-sm">
                            <i class="fas fa-eye mr-1"></i>
                            Ver Detalle
                        </a>
                    </td>
                </tr>
            `;
        });
        console.log('HTML generado:', html);
        $('#tabla-produccion tbody').html(html);
        console.log('Tabla actualizada');
    }
    
    function mostrarDatosEstaticos() {
        console.log('Mostrando datos estáticos de la vista...');
        // Obtener los datos estáticos de la vista
        const datosEstaticos = @json($datosProduccion ?? []);
        console.log('Datos estáticos:', datosEstaticos);
        
        if (datosEstaticos && datosEstaticos.length > 0) {
            mostrarDatos(datosEstaticos);
            actualizarResumen(datosEstaticos);
            generarGrafica(datosEstaticos);
        } else {
            console.log('No hay datos estáticos disponibles');
            mostrarError('No hay datos de producción disponibles');
        }
    }
    
    function mostrarError(mensaje) {
        $('#tabla-produccion tbody').html(`
            <tr>
                <td colspan="17" class="text-center text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${mensaje}
                </td>
            </tr>
        `);
    }
    
    function actualizarResumen(datos) {
        if (!datos || datos.length === 0) {
            $('#total-produccion').text('0');
            $('#promedio-diario').text('0');
            return;
        }
        
        const totalProduccion = datos.reduce((sum, dato) => sum + dato.produccion, 0);
        const promedioDiario = datos.length > 0 ? totalProduccion / datos.length : 0;
        
        $('#total-produccion').text(totalProduccion.toLocaleString());
        $('#promedio-diario').text(promedioDiario.toFixed(0));
    }
    
    function generarGrafica(datos) {
        const ctx = document.getElementById('grafica-produccion').getContext('2d');
        
        if (window.graficaProduccion) {
            window.graficaProduccion.destroy();
        }
        
        if (!datos || datos.length === 0) {
            // Mostrar mensaje de no hay datos en el canvas
            ctx.font = '16px Arial';
            ctx.fillStyle = '#6c757d';
            ctx.textAlign = 'center';
            ctx.fillText('No hay datos disponibles para generar la gráfica', ctx.canvas.width / 2, ctx.canvas.height / 2);
            return;
        }
        
        const galpones = [...new Set(datos.map(d => d.galpon))];
        const producciones = galpones.map(galpon => {
            const galponDatos = datos.filter(d => d.galpon === galpon);
            return galponDatos.reduce((sum, d) => sum + d.produccion, 0);
        });
        
        window.graficaProduccion = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: galpones,
                datasets: [{
                    label: 'Producción Total',
                    data: producciones,
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
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Producción: ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    function testProduccion() {
        console.log('Ejecutando test de producción...');
        
        $.ajax({
            url: '{{ route("avicontrol.admin.information.test.produccion") }}',
            method: 'GET',
            success: function(response) {
                console.log('Test completado:', response);
                if (response.success) {
                    alert(`Test completado:\n- Total producciones: ${response.total_producciones}\n- Total galpones: ${response.total_galpones}\n- Mensaje: ${response.message}`);
                } else {
                    alert('Error en test: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en test:', error);
                alert('Error ejecutando test: ' + error);
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
    padding: 15px;
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

/* Filtros */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 5px;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.form-control {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 8px 12px;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
    transform: translateY(-1px);
}

/* Progress bars */
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

/* Badges */
.badge {
    font-size: 0.75rem;
    padding: 6px 10px;
    border-radius: 6px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

/* Estadísticas del día */
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    color: white;
}

.bg-gradient-success .card-header {
    background: rgba(255, 255, 255, 0.1) !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.bg-gradient-success .card-title {
    color: white !important;
}

/* Tipos de huevo */
.border.rounded {
    transition: all 0.3s ease;
    border-width: 2px !important;
}

.border.rounded:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Resumen de calidad */
.bg-success, .bg-warning, .bg-danger {
    transition: all 0.3s ease;
}

.bg-success:hover, .bg-warning:hover, .bg-danger:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
        transform: translateY(20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes slideIn {
    from { 
        opacity: 0; 
        transform: translateX(-20px); 
    }
    to { 
        opacity: 1; 
        transform: translateX(0); 
    }
}

.card {
    animation: fadeIn 0.4s ease-out;
}

.small-box {
    animation: slideIn 0.4s ease-out;
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
