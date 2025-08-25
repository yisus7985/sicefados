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
                    <button type="button" class="btn btn-success btn-sm" id="exportar-excel">
                        <i class="fas fa-file-excel me-2"></i>Exportar Excel
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="exportar-pdf">
                        <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                    </button>
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
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('avicontrol.admin.information.galpon.detalle', ['galpon_id' => $produccion['galpon_id'] ?? 0]) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Ver Detalle
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm generar-pdf" 
                                                        data-produccion-id="{{ $produccion['id'] ?? 0 }}"
                                                        data-fecha="{{ $produccion['fecha'] ? $produccion['fecha']->format('Y-m-d') : '' }}"
                                                        data-galpon="{{ $produccion['galpon'] ?? '' }}">
                                                    <i class="fas fa-file-pdf mr-1"></i>
                                                    PDF
                                                </button>
                                            </div>
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
        </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Event listener para generar PDF individual
    $(document).on('click', '.generar-pdf', function() {
        const produccionId = $(this).data('produccion-id');
        const fecha = $(this).data('fecha');
        const galpon = $(this).data('galpon');
        
        generarPDFIndividual(produccionId, fecha, galpon);
    });
    
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
    
    function mostrarNotificacion(mensaje, tipo) {
        // Crear notificación toast
        const toast = $(`
            <div class="toast-notification toast-${tipo}">
                <div class="toast-header">
                    <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-2"></i>
                    <strong class="me-auto">${tipo === 'success' ? 'Éxito' : 'Error'}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${mensaje}
                </div>
            </div>
        `);
        
        // Agregar al DOM y mostrar
        $('body').append(toast);
        toast.fadeIn();
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            toast.fadeOut(() => toast.remove());
        }, 5000);
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
