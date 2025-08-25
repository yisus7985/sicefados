@extends('avicontrol::layouts.admin')

@section('title', 'Información - Costos de Producción')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.information.index') }}">Información</a></li>
            <li class="breadcrumb-item active" aria-current="page">Costos de Producción</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-12">
            <div class="page-header mb-3">
                <h2 class="page-title">
                    <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                    Costos de Producción (Resumen)
                </h2>
                <p class="text-muted mb-0">Visualización de registros de costos de producción</p>
            </div>

            @if(isset($error))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Advertencia:</strong> {{ $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        Listado de Costos
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('avicontrol.admin.information.index') }}" class="btn btn-secondary btn-sm me-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver
                        </a>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success btn-sm" id="exportar-excel" data-bs-toggle="modal" data-bs-target="#modalFiltrosExportacion" data-formato="excel">
                                <i class="fas fa-file-excel me-2"></i>Exportar Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" id="exportar-pdf" data-bs-toggle="modal" data-bs-target="#modalFiltrosExportacion" data-formato="pdf">
                                <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                            </button>
                        </div>
                        <span class="badge bg-info ms-2">
                            @if(isset($productionCosts) && count($productionCosts) > 0)
                                Total: {{ $productionCosts instanceof \Illuminate\Pagination\LengthAwarePaginator ? $productionCosts->total() : count($productionCosts) }}
                            @else
                                Total: 0
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Galpón</th>
                                    <th>Lote</th>
                                    <th>Período</th>
                                    <th>Tipo</th>
                                    <th>Costo Total</th>
                                    <th>Costo/Unidad</th>
                                    <th>Estado</th>
                                    <th>Creado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productionCosts ?? [] as $cost)
                                <tr>
                                    <td>{{ $cost->id }}</td>
                                    <td>{{ $cost->facility_name ?? 'N/A' }}</td>
                                    <td>{{ $cost->batch_code ?? 'N/A' }}</td>
                                    <td>
                                        @if($cost->period_start && $cost->period_end)
                                            {{ \Carbon\Carbon::parse($cost->period_start)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($cost->period_end)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ ($cost->cost_type ?? '') === 'batch' ? 'primary' : 'info' }}" style="color: white; font-weight: bold;">
                                            {{ $cost->cost_type_name ?? strtoupper($cost->cost_type ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($cost->total_cost ?? 0, 2) }}</td>
                                    <td>
                                        @if(!is_null($cost->cost_per_unit))
                                            ${{ number_format($cost->cost_per_unit, 2) }}
                                            <small class="text-muted">/{{ $cost->unit_type_name ?? 'unidad' }}</small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @php($status = $cost->status ?? 'draft')
                                        @if($status === 'draft')
                                            <span class="badge badge-warning" style="color: white; font-weight: bold;">Borrador</span>
                                        @elseif($status === 'confirmed')
                                            <span class="badge badge-success" style="color: white; font-weight: bold;">Confirmado</span>
                                        @elseif($status === 'cancelled')
                                            <span class="badge badge-danger" style="color: white; font-weight: bold;">Cancelado</span>
                                        @else
                                            <span class="badge badge-secondary">{{ strtoupper($status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($cost->created_at))
                                            {{ \Carbon\Carbon::parse($cost->created_at)->format('d/m/Y H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Acciones">
                                            <a href="{{ route('avicontrol.admin.information.costos_produccion.pdf_show', $cost->id) }}" class="btn btn-outline-danger btn-sm" title="Descargar PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        No hay costos de producción para mostrar
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(($productionCosts ?? null) instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center">
                            {{ $productionCosts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Filtros de Exportación -->
    <div class="modal fade" id="modalFiltrosExportacion" tabindex="-1" aria-labelledby="modalFiltrosExportacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalFiltrosExportacionLabel">
                        <i class="fas fa-filter me-2"></i>Filtros de Exportación - Costos de Producción
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formFiltrosExportacion">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Selecciona el período:</strong> Elige el rango de fechas para tu reporte de costos de producción.
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
                                <label for="tipoFiltro" class="form-label">
                                    <i class="fas fa-tags me-1"></i>Tipo de Costo
                                </label>
                                <select class="form-select" id="tipoFiltro" name="cost_type">
                                    <option value="">Todos los tipos</option>
                                    <option value="batch">📦 Por lote</option>
                                    <option value="monthly">📅 Mensual</option>
                                    <option value="weekly">📊 Semanal</option>
                                    <option value="daily">🗓️ Diario</option>
                                    <option value="feed">🌾 Alimentación</option>
                                    <option value="medical">💊 Médico</option>
                                    <option value="maintenance">🔧 Mantenimiento</option>
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
                                    <option value="costo_desc">Costo Total (mayor a menor)</option>
                                    <option value="costo_asc">Costo Total (menor a mayor)</option>
                                    <option value="costo_unidad_desc">Costo/Unidad (mayor a menor)</option>
                                    <option value="costo_unidad_asc">Costo/Unidad (menor a mayor)</option>
                                    <option value="galpon_asc">Galpón (A-Z)</option>
                                    <option value="tipo_asc">Tipo de Costo (A-Z)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Filtros de rango de costo -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="costoMinimo" class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>Costo Mínimo ($)
                                </label>
                                <input type="number" class="form-control" id="costoMinimo" name="costo_minimo" placeholder="Ej: 100" step="0.01">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="costoMaximo" class="form-label">
                                    <i class="fas fa-dollar-sign me-1"></i>Costo Máximo ($)
                                </label>
                                <input type="number" class="form-control" id="costoMaximo" name="costo_maximo" placeholder="Ej: 5000" step="0.01">
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
@endsection

@push('styles')
<style>
/* Estilos para la vista de costos de producción */
.badge { 
    font-size: 0.85em; 
    padding: 0.5em 0.75em; 
    border-radius: 0.375rem; 
    font-weight: 600;
}

.badge-primary { background-color: #007bff !important; color: #fff !important; }
.badge-info { background-color: #17a2b8 !important; color: #fff !important; }
.badge-warning { background-color: #ffc107 !important; color: #212529 !important; }
.badge-success { background-color: #28a745 !important; color: #fff !important; }
.badge-danger { background-color: #dc3545 !important; color: #fff !important; }

/* Breadcrumb */
.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 20px;
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
    padding: 20px;
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

/* Card */
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
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.875rem;
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

/* Paginación */
.pagination {
    justify-content: center;
    margin-top: 20px;
}

.page-link {
    border-radius: 8px;
    margin: 0 2px;
    border: none;
    color: #007bff;
    transition: all 0.3s ease;
}

.page-link:hover {
    background-color: #007bff;
    color: white;
    transform: translateY(-1px);
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 15px;
    }
    
    .page-title {
        font-size: 1.25rem;
    }
    
    .card-header {
        padding: 15px 20px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .table th,
    .table td {
        padding: 12px 8px;
        font-size: 0.85rem;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.8rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    console.log('=== AVICONTROL COSTOS DE PRODUCCIÓN - INICIANDO ===');
    
    // Auto-ocultar alertas después de 4 segundos
    setTimeout(function(){ 
        $('.alert').fadeOut('slow'); 
    }, 4000);
    
    // Agregar tooltips a los badges de estado
    $('[data-toggle="tooltip"]').tooltip();
    
    // Mejorar la experiencia de la tabla
    $('.table tbody tr').hover(
        function() {
            $(this).addClass('table-hover');
        },
        function() {
            $(this).removeClass('table-hover');
        }
    );
    
    // Confirmar solo en botones marcados explícitamente como destructivos
    $('[data-confirm="true"]').on('click', function(e) {
        if (!confirm($(this).data('confirmMessage') || '¿Está seguro de que desea realizar esta acción?')) {
            e.preventDefault();
        }
    });
    
    // Mejorar la paginación
    $('.pagination .page-link').on('click', function() {
        $('html, body').animate({
            scrollTop: $('.card').offset().top - 100
        }, 500);
    });
    
    // Configurar event listeners para filtros
    configurarEventListeners();
    
    console.log('Vista de costos de producción cargada correctamente');
    
    // Funciones para el sistema de filtros
    function configurarEventListeners() {
        // Modal de filtros de exportación
        let formatoSeleccionado = '';
        
        // Capturar el formato cuando se abre el modal
        $('#exportar-excel, #exportar-pdf').on('click', function() {
            formatoSeleccionado = $(this).data('formato');
            $('#modalFiltrosExportacionLabel').html(`<i class="fas fa-filter me-2"></i>Filtros de Exportación - Costos - ${formatoSeleccionado.toUpperCase()}`);
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
        $('#tipoPeriodo, #tipoFiltro, #galponFiltro, #ordenamiento, #fechaInicio, #fechaFin, #costoMinimo, #costoMaximo').on('change input', function() {
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
    
    function exportarDatosConFiltros(formato, filtros) {
        mostrarNotificacion(`Iniciando exportación a ${formato.toUpperCase()} con filtros...`, 'info');
        
        const btn = $(`#exportar-${formato}`);
        const originalText = btn.html();
        btn.html(`<i class="fas fa-spinner fa-spin me-2"></i>Exportando...`);
        btn.prop('disabled', true);
        
        // Construir URL con parámetros de filtro
        const baseUrl = formato === 'excel' 
            ? '{{ route("avicontrol.admin.information.costos_produccion.excel") }}'
            : '{{ route("avicontrol.admin.information.costos_produccion.pdf") }}';
        
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
            cost_type: $('#tipoFiltro').val(),
            galpon_id: $('#galponFiltro').val(),
            ordenamiento: $('#ordenamiento').val(),
            fecha_inicio: $('#fechaInicio').val(),
            fecha_fin: $('#fechaFin').val(),
            costo_minimo: $('#costoMinimo').val(),
            costo_maximo: $('#costoMaximo').val()
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
        
        if (filtros.costo_minimo && filtros.costo_maximo) {
            if (parseFloat(filtros.costo_minimo) > parseFloat(filtros.costo_maximo)) {
                mostrarNotificacion('El costo mínimo debe ser menor que el costo máximo', 'error');
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
        
        // Tipo de costo
        if (filtros.cost_type) {
            const tipos = {
                'batch': '📦 Por lote',
                'monthly': '📅 Mensual',
                'weekly': '📊 Semanal',
                'daily': '🗓️ Diario',
                'feed': '🌾 Alimentación',
                'medical': '💊 Médico',
                'maintenance': '🔧 Mantenimiento'
            };
            resumen.push(`<strong>Tipo:</strong> ${tipos[filtros.cost_type]}`);
        } else {
            resumen.push(`<strong>Tipo:</strong> Todos los tipos de costo`);
        }
        
        // Galpón
        if (filtros.galpon_id) {
            const galponTexto = $('#galponFiltro option:selected').text();
            resumen.push(`<strong>Galpón:</strong> ${galponTexto}`);
        } else {
            resumen.push(`<strong>Galpón:</strong> Todos los galpones`);
        }
        
        // Filtros de costo
        if (filtros.costo_minimo || filtros.costo_maximo) {
            let costoFiltro = '<strong>Rango de Costo:</strong> ';
            if (filtros.costo_minimo && filtros.costo_maximo) {
                costoFiltro += `Entre $${filtros.costo_minimo} y $${filtros.costo_maximo}`;
            } else if (filtros.costo_minimo) {
                costoFiltro += `Mínimo $${filtros.costo_minimo}`;
            } else if (filtros.costo_maximo) {
                costoFiltro += `Máximo $${filtros.costo_maximo}`;
            }
            resumen.push(costoFiltro);
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
    
    console.log('=== AVICONTROL COSTOS DE PRODUCCIÓN - INICIALIZADO ===');
});
</script>
@endpush
