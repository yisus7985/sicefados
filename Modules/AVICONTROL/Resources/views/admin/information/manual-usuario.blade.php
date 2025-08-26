@extends('avicontrol::layouts.admin')

@section('title', 'Manual de Usuario - AVICONTROL')

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
                    <i class="fas fa-book text-primary me-3"></i>
                    Manual de Usuario
                </h2>
                <p class="text-muted mb-0">Guía completa para el uso del sistema AVICONTROL</p>
            </div>
            <div class="col-auto">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-success btn-sm" id="exportar-manual-excel">
                        <i class="fas fa-file-excel me-2"></i>Exportar Excel
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="exportar-manual-pdf">
                        <i class="fas fa-file-pdf me-2"></i>Exportar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegación del Manual -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Índice del Manual
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="list-group" id="manual-tabs" role="tablist">
                                <a class="list-group-item list-group-item-action active" id="introduccion-tab" data-bs-toggle="list" href="#introduccion" role="tab">
                                    <i class="fas fa-play-circle me-2"></i>Introducción
                                </a>
                                <a class="list-group-item list-group-item-action" id="produccion-tab" data-bs-toggle="list" href="#produccion" role="tab">
                                    <i class="fas fa-egg me-2"></i>Módulo de Producción
                                </a>
                                <a class="list-group-item list-group-item-action" id="alimentos-tab" data-bs-toggle="list" href="#alimentos" role="tab">
                                    <i class="fas fa-utensils me-2"></i>Módulo de Alimentos
                                </a>
                                <a class="list-group-item list-group-item-action" id="seguimientos-tab" data-bs-toggle="list" href="#seguimientos" role="tab">
                                    <i class="fas fa-chart-area me-2"></i>Seguimientos
                                </a>
                                <a class="list-group-item list-group-item-action" id="costos-tab" data-bs-toggle="list" href="#costos" role="tab">
                                    <i class="fas fa-dollar-sign me-2"></i>Costos de Producción
                                </a>
                                <a class="list-group-item list-group-item-action" id="reportes-tab" data-bs-toggle="list" href="#reportes" role="tab">
                                    <i class="fas fa-file-alt me-2"></i>Generación de Reportes
                                </a>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content" id="manual-tabContent">
                                <!-- Introducción -->
                                <div class="tab-pane fade show active" id="introduccion" role="tabpanel">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <h4 class="text-primary mb-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Bienvenido al Sistema AVICONTROL
                                            </h4>
                                            <p class="lead">
                                                AVICONTROL es un sistema integral de gestión avícola diseñado para optimizar 
                                                el control de producción, seguimiento de galpones y análisis de costos.
                                            </p>
                                            
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-lightbulb me-2"></i>Características Principales:</h6>
                                                <ul class="mb-0">
                                                    <li>Gestión completa de galpones y lotes de aves</li>
                                                    <li>Seguimiento en tiempo real de la producción</li>
                                                    <li>Control de consumo de alimentos y costos</li>
                                                    <li>Generación de reportes detallados</li>
                                                    <li>Análisis estadístico avanzado</li>
                                                </ul>
                                            </div>

                                            <h5 class="text-success mt-4 mb-3">Requisitos del Sistema</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <ul class="list-unstyled">
                                                        <li><i class="fas fa-check text-success me-2"></i>Navegador web moderno</li>
                                                        <li><i class="fas fa-check text-success me-2"></i>Conexión a internet estable</li>
                                                        <li><i class="fas fa-check text-success me-2"></i>Acceso autorizado al sistema</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul class="list-unstyled">
                                                        <li><i class="fas fa-check text-success me-2"></i>Permisos de administrador</li>
                                                        <li><i class="fas fa-check text-success me-2"></i>Datos de galpones configurados</li>
                                                        <li><i class="fas fa-check text-success me-2"></i>Configuración de lotes activos</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Módulo de Producción -->
                                <div class="tab-pane fade" id="produccion" role="tabpanel">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <h4 class="text-primary mb-3">
                                                <i class="fas fa-egg me-2"></i>
                                                Módulo de Producción
                                            </h4>
                                            
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h5 class="text-success">Funcionalidades Principales</h5>
                                                    <ul>
                                                        <li>Registro diario de producción por galpón</li>
                                                        <li>Seguimiento de lotes de aves</li>
                                                        <li>Estadísticas en tiempo real</li>
                                                        <li>Análisis de rendimiento por período</li>
                                                        <li>Identificación del mejor galpón</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5 class="text-info">Métricas Clave</h5>
                                                    <ul>
                                                        <li>Total de producción acumulada</li>
                                                        <li>Producción diaria y mensual</li>
                                                        <li>Promedio diario de producción</li>
                                                        <li>Rendimiento por galpón</li>
                                                        <li>Tendencias de producción</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="alert alert-warning">
                                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Importante:</h6>
                                                <p class="mb-0">
                                                    La producción se registra automáticamente cada día a las 00:00 horas. 
                                                    Asegúrese de que todos los galpones estén correctamente configurados 
                                                    para obtener estadísticas precisas.
                                                </p>
                                            </div>

                                            <h5 class="text-primary mt-4 mb-3">Proceso de Registro</h5>
                                            <ol>
                                                <li>Acceder al módulo de producción</li>
                                                <li>Seleccionar el galpón deseado</li>
                                                <li>Ingresar la cantidad de huevos producidos</li>
                                                <li>Verificar la fecha de registro</li>
                                                <li>Guardar la información</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <!-- Módulo de Alimentos -->
                                <div class="tab-pane fade" id="alimentos" role="tabpanel">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <h4 class="text-primary mb-3">
                                                <i class="fas fa-utensils me-2"></i>
                                                Módulo de Alimentos
                                            </h4>
                                            
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h5 class="text-success">Control de Insumos</h5>
                                                    <ul>
                                                        <li>Gestión de inventario de alimentos</li>
                                                        <li>Registro de consumo por galpón</li>
                                                        <li>Seguimiento de costos por lote</li>
                                                        <li>Análisis de eficiencia alimenticia</li>
                                                        <li>Alertas de stock bajo</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5 class="text-info">Tipos de Alimentos</h5>
                                                    <ul>
                                                        <li>Alimento de inicio (0-7 días)</li>
                                                        <li>Alimento de crecimiento (8-21 días)</li>
                                                        <li>Alimento de acabado (22+ días)</li>
                                                        <li>Suplementos vitamínicos</li>
                                                        <li>Premezclas especializadas</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-calculator me-2"></i>Cálculo de Consumo:</h6>
                                                <p class="mb-0">
                                                    El sistema calcula automáticamente el consumo diario por ave basándose 
                                                    en la edad del lote y las recomendaciones nutricionales estándar.
                                                </p>
                                            </div>

                                            <h5 class="text-primary mt-4 mb-3">Proceso de Gestión</h5>
                                            <ol>
                                                <li>Registrar entrada de alimentos al inventario</li>
                                                <li>Asignar consumo diario por galpón</li>
                                                <li>Monitorear niveles de stock</li>
                                                <li>Generar reportes de consumo</li>
                                                <li>Analizar costos por período</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <!-- Seguimientos -->
                                <div class="tab-pane fade" id="seguimientos" role="tabpanel">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <h4 class="text-primary mb-3">
                                                <i class="fas fa-chart-area me-2"></i>
                                                Seguimientos de Galpón
                                            </h4>
                                            
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h5 class="text-success">Indicadores de Seguimiento</h5>
                                                    <ul>
                                                        <li>Peso promedio por ave</li>
                                                        <li>Ganancia de peso diaria</li>
                                                        <li>Conversión alimenticia</li>
                                                        <li>Mortalidad por período</li>
                                                        <li>Eficiencia de producción</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5 class="text-info">Gráficas Disponibles</h5>
                                                    <ul>
                                                        <li>Curva de crecimiento</li>
                                                        <li>Consumo de alimento</li>
                                                        <li>Evolución de peso</li>
                                                        <li>Comparación entre galpones</li>
                                                        <li>Tendencias temporales</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="alert alert-success">
                                                <h6><i class="fas fa-chart-line me-2"></i>Análisis de Tendencias:</h6>
                                                <p class="mb-0">
                                                    El sistema genera gráficas automáticas que permiten identificar 
                                                    patrones de crecimiento y detectar anomalías en el desarrollo de las aves.
                                                </p>
                                            </div>

                                            <h5 class="text-primary mt-4 mb-3">Frecuencia de Medición</h5>
                                            <ul>
                                                <li><strong>Día 1-7:</strong> Medición diaria de peso</li>
                                                <li><strong>Día 8-21:</strong> Medición cada 3 días</li>
                                                <li><strong>Día 22+:</strong> Medición semanal</li>
                                                <li><strong>Consumo:</strong> Registro diario</li>
                                                <li><strong>Mortalidad:</strong> Registro inmediato</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Costos de Producción -->
                                <div class="tab-pane fade" id="costos" role="tabpanel">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <h4 class="text-primary mb-3">
                                                <i class="fas fa-dollar-sign me-2"></i>
                                                Costos de Producción
                                            </h4>
                                            
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h5 class="text-success">Componentes de Costo</h5>
                                                    <ul>
                                                        <li>Alimentación (60-70% del total)</li>
                                                        <li>Mano de obra (15-20%)</li>
                                                        <li>Medicamentos y vacunas (5-10%)</li>
                                                        <li>Energía y mantenimiento (5-8%)</li>
                                                        <li>Otros gastos (2-5%)</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5 class="text-info">Análisis de Rentabilidad</h5>
                                                    <ul>
                                                        <li>Costo por ave producida</li>
                                                        <li>Costo por kilo de carne</li>
                                                        <li>Margen de utilidad</li>
                                                        <li>Comparación entre lotes</li>
                                                        <li>Proyecciones financieras</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="alert alert-warning">
                                                <h6><i class="fas fa-chart-pie me-2"></i>Distribución de Costos:</h6>
                                                <p class="mb-0">
                                                    La alimentación representa el mayor porcentaje de los costos totales. 
                                                    Un control eficiente del consumo puede mejorar significativamente 
                                                    la rentabilidad del negocio.
                                                </p>
                                            </div>

                                            <h5 class="text-primary mt-4 mb-3">Reportes de Costos</h5>
                                            <ul>
                                                <li><strong>Reporte Diario:</strong> Costos acumulados del día</li>
                                                <li><strong>Reporte Semanal:</strong> Resumen de costos por período</li>
                                                <li><strong>Reporte Mensual:</strong> Análisis completo de costos</li>
                                                <li><strong>Reporte por Lote:</strong> Costos específicos por galpón</li>
                                                <li><strong>Reporte Comparativo:</strong> Análisis entre períodos</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Generación de Reportes -->
                                <div class="tab-pane fade" id="reportes" role="tabpanel">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <h4 class="text-primary mb-3">
                                                <i class="fas fa-file-alt me-2"></i>
                                                Generación de Reportes
                                            </h4>
                                            
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h5 class="text-success">Formatos Disponibles</h5>
                                                    <ul>
                                                        <li><strong>Excel (.xlsx):</strong> Para análisis detallado</li>
                                                        <li><strong>PDF (.pdf):</strong> Para presentaciones</li>
                                                        <li><strong>CSV (.csv):</strong> Para importación a otros sistemas</li>
                                                        <li><strong>HTML:</strong> Para visualización web</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5 class="text-info">Tipos de Reportes</h5>
                                                    <ul>
                                                        <li>Reportes de producción diaria</li>
                                                        <li>Reportes de consumo de alimentos</li>
                                                        <li>Reportes de seguimiento de galpones</li>
                                                        <li>Reportes de costos de producción</li>
                                                        <li>Reportes consolidados mensuales</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-cog me-2"></i>Configuración de Reportes:</h6>
                                                <p class="mb-0">
                                                    Antes de generar un reporte, configure los filtros deseados como 
                                                    fechas, galpones específicos o rangos de datos para obtener 
                                                    información más precisa y relevante.
                                                </p>
                                            </div>

                                            <h5 class="text-primary mt-4 mb-3">Proceso de Generación</h5>
                                            <ol>
                                                <li>Seleccionar el tipo de reporte deseado</li>
                                                <li>Configurar filtros y parámetros</li>
                                                <li>Elegir el formato de exportación</li>
                                                <li>Generar y descargar el reporte</li>
                                                <li>Revisar y validar la información</li>
                                            </ol>

                                            <h5 class="text-success mt-4 mb-3">Filtros Disponibles</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <ul>
                                                        <li><strong>Por Fecha:</strong> Rango de fechas específico</li>
                                                        <li><strong>Por Galpón:</strong> Galpones individuales o grupos</li>
                                                        <li><strong>Por Lote:</strong> Lotes específicos de aves</li>
                                                        <li><strong>Por Tipo:</strong> Tipo de producción o alimento</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul>
                                                        <li><strong>Por Estado:</strong> Activo, inactivo, completado</li>
                                                        <li><strong>Por Categoría:</strong> Clasificación de datos</li>
                                                        <li><strong>Por Usuario:</strong> Usuario que generó el registro</li>
                                                        <li><strong>Por Prioridad:</strong> Nivel de importancia</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Contacto y Soporte -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-headset me-2"></i>
                        ¿Necesitas Ayuda?
                    </h5>
                    <p class="mb-3">
                        Si tienes alguna pregunta o necesitas asistencia técnica, 
                        nuestro equipo de soporte está disponible para ayudarte.
                    </p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <span>soporte@avicontrol.com</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <span>+1 (555) 123-4567</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <span>Lun-Vie: 8:00 - 18:00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para funcionalidad del manual -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para exportar a Excel
    document.getElementById('exportar-manual-excel').addEventListener('click', function() {
        // Implementar lógica de exportación a Excel
        alert('Función de exportación a Excel en desarrollo');
    });

    // Función para exportar a PDF
    document.getElementById('exportar-manual-pdf').addEventListener('click', function() {
        // Implementar lógica de exportación a PDF
        alert('Función de exportación a PDF en desarrollo');
    });

    // Navegación por tabs
    const manualTabs = document.querySelectorAll('#manual-tabs a');
    manualTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('href');
            
            // Remover clase active de todos los tabs
            manualTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Mostrar contenido del tab seleccionado
            const tabContents = document.querySelectorAll('.tab-pane');
            tabContents.forEach(content => {
                content.classList.remove('show', 'active');
            });
            
            const targetContent = document.querySelector(target);
            if (targetContent) {
                targetContent.classList.add('show', 'active');
            }
        });
    });
});
</script>
@endpush

<!-- Estilos adicionales para el manual -->
@push('styles')
<style>
.list-group-item {
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

.list-group-item:hover {
    border-left-color: #007bff;
    background-color: #f8f9fa;
}

.list-group-item.active {
    border-left-color: #007bff;
    background-color: #007bff;
    border-color: #007bff;
}

.tab-pane {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.alert {
    border-left: 4px solid;
}

.alert-info {
    border-left-color: #17a2b8;
}

.alert-success {
    border-left-color: #28a745;
}

.alert-warning {
    border-left-color: #ffc107;
}

.alert-danger {
    border-left-color: #dc3545;
}
</style>
@endpush
@endsection
