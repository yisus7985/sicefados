@extends('avicontrol::layouts.admin')

@section('title', 'Módulo de Información - AVICONTROL')

@section('content')
<div class="container-fluid">
    <!-- Header de la página -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Módulo de Información
                    </h1>
                    <p class="text-muted mb-0">Acceda a informes detallados del sistema</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informes de Producción -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="fas fa-chart-line me-2"></i>
                        Informes de Producción
                    </h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text flex-grow-1">
                        Visualiza información detallada de galpones, aves y producción registrada.
                        Incluye fechas de registro y estadísticas de producción por galpón.
                    </p>
                    <a href="{{ route('avicontrol.admin.information.produccion') }}" class="btn btn-primary w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Ver Informes de Producción
                    </a>
                </div>
            </div>
        </div>

        <!-- Informes de Alimentos -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-success">
                        <i class="fas fa-utensils me-2"></i>
                        Informes de Alimentos
                    </h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text flex-grow-1">
                        Consulta información de galpones, insumos alimenticios y costos asociados.
                        Análisis detallado de consumo y gastos por galpón.
                    </p>
                    <a href="{{ route('avicontrol.admin.information.alimentos') }}" class="btn btn-success w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Ver Informes de Alimentos
                    </a>
                </div>
            </div>
        </div>

        <!-- Seguimientos de Galpón -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-info">
                        <i class="fas fa-chart-area me-2"></i>
                        Seguimientos de Galpón
                    </h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text flex-grow-1">
                        Gráficas de crecimiento de aves con fechas y pesos.
                        Seguimiento temporal del desarrollo en cada galpón.
                    </p>
                    <a href="{{ route('avicontrol.admin.information.seguimientos') }}" class="btn btn-info w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Ver Seguimientos
                    </a>
                </div>
            </div>
        </div>

        <!-- Informes de Costos de Producción -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-danger">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Informes de Costos de Producción
                    </h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="card-text flex-grow-1">
                        Consulta y analiza los costos de producción por galpón, lote y período.
                        Resumen de costos totales y por unidad.
                    </p>
                    <a href="{{ route('avicontrol.admin.information.costos_produccion') }}" class="btn btn-danger w-100">
                        <i class="fas fa-arrow-right me-2"></i>
                        Ver Informes de Costos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen Estadístico -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="fas fa-info-circle me-2"></i>
                        Resumen del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-home fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-muted">Total Galpones</h6>
                                    <h4 class="mb-0 text-primary">{{ $estadisticas['total_galpones'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-kiwi-bird fa-2x text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-muted">Total Aves</h6>
                                    <h4 class="mb-0 text-success">{{ number_format($estadisticas['total_aves'] ?? 0) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-utensils fa-2x text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-muted">Insumos Activos</h6>
                                    <h4 class="mb-0 text-warning">{{ $estadisticas['total_insumos'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center p-3 bg-info bg-opacity-10 rounded">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chart-bar fa-2x text-info"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0 text-muted">Registros Hoy</h6>
                                    <h4 class="mb-0 text-info">{{ $estadisticas['registros_hoy'] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Detallada de Galpones -->
    @if(isset($estadisticas['aves_por_galpon']) && count($estadisticas['aves_por_galpon']) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="fas fa-chart-pie me-2"></i>
                        Estado de Galpones
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Galpón</th>
                                    <th>Total Aves</th>
                                    <th>Capacidad</th>
                                    <th>Ocupación</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estadisticas['aves_por_galpon'] as $galpon)
                                <tr>
                                    <td>
                                        <strong>{{ $galpon['nombre'] }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ number_format($galpon['total_aves']) }}</span>
                                    </td>
                                    <td>{{ number_format($galpon['capacidad']) }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $galpon['porcentaje_ocupacion'] > 80 ? 'danger' : ($galpon['porcentaje_ocupacion'] > 60 ? 'warning' : 'success') }}" 
                                                 role="progressbar" 
                                                 style="width: {{ min($galpon['porcentaje_ocupacion'], 100) }}%">
                                                {{ $galpon['porcentaje_ocupacion'] }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($galpon['porcentaje_ocupacion'] > 80)
                                            <span class="badge bg-danger">Lleno</span>
                                        @elseif($galpon['porcentaje_ocupacion'] > 60)
                                            <span class="badge bg-warning">Moderado</span>
                                        @else
                                            <span class="badge bg-success">Disponible</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Resumen de Actividad del Día -->
    @if(isset($estadisticas['produccion_hoy']) || isset($estadisticas['consumo_hoy']))
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-success">
                        <i class="fas fa-egg me-2"></i>
                        Producción del Día
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-success mb-0">{{ number_format($estadisticas['produccion_hoy'] ?? 0) }}</h2>
                    <p class="text-muted mb-0">Unidades producidas hoy</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-warning">
                        <i class="fas fa-utensils me-2"></i>
                        Consumo del Día
                    </h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-warning mb-0">{{ number_format($estadisticas['consumo_hoy'] ?? 0, 1) }}</h2>
                    <p class="text-muted mb-0">Kg de alimento consumidos hoy</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Las estadísticas ya se cargan desde el servidor
    console.log('Módulo de información cargado correctamente');
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

/* Opacidad de fondo */
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
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
