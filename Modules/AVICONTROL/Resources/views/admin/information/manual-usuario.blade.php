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
        </div>
    </div>

    <!-- Contenido Principal del Manual -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-body text-center p-5">
                    <!-- Icono del Manual -->
                    <div class="mb-4">
                        <i class="fas fa-book-open text-primary" style="font-size: 4rem;"></i>
                    </div>
                    
                    <!-- Título de Bienvenida -->
                    <h3 class="text-primary mb-4">
                        ¡Bienvenido al Manual de Usuario de AVICONTROL!
                    </h3>
                    
                    <!-- Descripción -->
                    <p class="lead text-muted mb-4">
                        Este manual contiene toda la información necesaria para utilizar el sistema AVICONTROL 
                        de manera eficiente y aprovechar todas sus funcionalidades.
                    </p>
                    
                    <!-- Información del Manual -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="text-start">
                                <h6 class="text-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Contenido del Manual:
                                </h6>
                                <ul class="list-unstyled text-muted">
                                    <li><i class="fas fa-arrow-right me-2"></i>Introducción al Sistema</li>
                                    <li><i class="fas fa-arrow-right me-2"></i>Módulo de Producción</li>
                                    <li><i class="fas fa-arrow-right me-2"></i>Gestión de Alimentos</li>
                                    <li><i class="fas fa-arrow-right me-2"></i>Seguimientos y Monitoreo</li>
                                    <li><i class="fas fa-arrow-right me-2"></i>Costos de Producción</li>
                                    <li><i class="fas fa-arrow-right me-2"></i>Generación de Reportes</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-start">
                                <h6 class="text-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Características:
                                </h6>
                                <ul class="list-unstyled text-muted">
                                    <li><i class="fas fa-star me-2"></i>Guía paso a paso</li>
                                    <li><i class="fas fa-star me-2"></i>Capturas de pantalla</li>
                                    <li><i class="fas fa-star me-2"></i>Ejemplos prácticos</li>
                                    <li><i class="fas fa-star me-2"></i>Solución de problemas</li>
                                    <li><i class="fas fa-star me-2"></i>Actualizaciones regulares</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botón de Descarga -->
                    <div class="mb-4">
                                                 <a href="{{ asset('modules/avicontrol/manual-usuario.pdf') }}" 
                            class="btn btn-success btn-lg px-5 py-3" 
                            download="Manual_Usuario_AVICONTROL.pdf"
                            target="_blank">
                            <i class="fas fa-download me-3"></i>
                            Descargar Manual de Usuario
                        </a>
                    </div>
                    
                    <!-- Información adicional -->
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Consejo:</strong> Guarda este manual en tu computadora para consultarlo 
                        cuando no tengas conexión a internet. El archivo se descargará en formato PDF.
                    </div>
                    
                    <!-- Versión del Manual -->
                    <div class="text-muted small">
                        <i class="fas fa-calendar me-1"></i>
                        Versión actualizada: {{ date('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    border-radius: 15px;
}

.card-body {
    border-radius: 15px;
}

.btn-lg {
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.fas {
    transition: all 0.3s ease;
}

.card:hover .fas {
    transform: scale(1.1);
}

.alert {
    border-radius: 10px;
    border-left: 4px solid #17a2b8;
}

.list-unstyled li {
    padding: 5px 0;
    transition: all 0.3s ease;
}

.list-unstyled li:hover {
    color: #007bff !important;
    transform: translateX(5px);
}
</style>
@endpush
