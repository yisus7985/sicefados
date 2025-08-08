<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Instalación Avícola - AVICONTROL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4d7c0f;
            --secondary: #b45309;
            --tertiary: #f59e0b;
            --light: #f8fafc;
            --dark: #334155;
            --accent: #f97316;
            --background: #f1f5f9;
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #222;
            color: #fff;
            min-height: 100vh;
        }
        
        .sidebar {
            background-color: #333;
            min-height: 100vh;
            color: #fff;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0.5rem;
            transition: all 0.3s;
            border-radius: 5px;
            padding: 0.75rem 1rem;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: var(--primary);
            color: #fff;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .content-wrapper {
            background-color: #2a2a2a;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            margin: 20px;
            padding: 30px;
        }
        
        .detail-card {
            background-color: #333;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-custom {
            background-color: var(--primary);
            color: #fff;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-custom:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }
        
        .header {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0;
        }
        
        .header-subtitle {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .page-title {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .page-title i {
            color: var(--tertiary);
        }
        
        .info-label {
            font-size: 0.9rem;
            color: #aaa;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 20px;
        }
        
        .status-badge {
            font-size: 0.9rem;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
        }
        
        .status-active {
            background-color: #198754;
            color: white;
        }
        
        .status-maintenance {
            background-color: #ffc107;
            color: #212529;
        }
        
        .status-inactive {
            background-color: #dc3545;
            color: white;
        }
        
        .stats-card {
            background-color: #444;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
        }
        
        .stats-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            color: var(--tertiary);
        }
        
        .stats-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-size: 0.9rem;
            color: #ddd;
        }
        
        .activity-item {
            padding: 15px;
            border-bottom: 1px solid #444;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-date {
            font-size: 0.8rem;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 px-0 sidebar">
                <div class="d-flex flex-column align-items-center py-4">
                    <i class="fas fa-feather-alt fa-3x text-warning mb-2"></i>
                    <h2 class="mb-0">AVICONTROL</h2>
                    <p class="text-muted small">Panel de Administración</p>
                </div>
                <hr class="bg-secondary">
                <ul class="nav flex-column px-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('avicontrol.admin.welcome') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('avicontrol.admin.poultry_houses.index') }}">
                            <i class="fas fa-warehouse"></i> Instalaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-egg"></i> Producción
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-utensils"></i> Alimentación
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-clipboard-list"></i> Inventario
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-line"></i> Estadísticas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog"></i> Configuración
                        </a>
                    </li>
                </ul>
                <hr class="bg-secondary">
                <div class="px-3">
                    <a href="{{ route('cefa.avicontrol.index') }}" class="btn btn-outline-light btn-sm w-100">
                        <i class="fas fa-sign-out-alt"></i> Volver al Inicio
                    </a>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-10 col-md-9 p-0">
                <div class="content-wrapper">
                    <!-- Header -->
                    <div class="header d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="logo-text"><i class="fas fa-feather-alt me-2 text-warning"></i>AVICONTROL</h1>
                            <p class="header-subtitle mb-0">Sistema de Gestión Avícola</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('avicontrol.admin.poultry_houses.index') }}" class="btn btn-outline-light me-2">
                                <i class="fas fa-arrow-left me-1"></i> Volver
                            </a>
                            <a href="{{ route('avicontrol.admin.poultry_houses.edit', $poultry_house->id) }}" class="btn btn-custom">
                                <i class="fas fa-edit me-1"></i> Editar
                            </a>
                        </div>
                    </div>
                    
                    <!-- Detail Content -->
                    <div class="row">
                        <div class="col-12">
                            <h2 class="page-title">
                                <i class="fas fa-warehouse me-2"></i> 
                                {{ $poultry_house->name }}
                                
                                @if($poultry_house->status == 'active')
                                <span class="status-badge status-active"><i class="fas fa-check-circle me-1"></i> Activo</span>
                                @elseif($poultry_house->status == 'maintenance')
                                <span class="status-badge status-maintenance"><i class="fas fa-tools me-1"></i> En Mantenimiento</span>
                                @else
                                <span class="status-badge status-inactive"><i class="fas fa-times-circle me-1"></i> Inactivo</span>
                                @endif
                            </h2>
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="detail-card">
                                        <h3 class="mb-4">Información General</h3>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="info-label">Código</p>
                                                <p class="info-value">{{ $poultry_house->code }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="info-label">Capacidad</p>
                                                <p class="info-value">{{ number_format($poultry_house->capacity) }} aves</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="info-label">Dimensiones</p>
                                                <p class="info-value">{{ $poultry_house->length }}m × {{ $poultry_house->width }}m</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="info-label">Área Total</p>
                                                <p class="info-value">{{ $poultry_house->length * $poultry_house->width }}m²</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="info-label">Ubicación</p>
                                                <p class="info-value">{{ $poultry_house->location }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="info-label">Fecha de Registro</p>
                                                <p class="info-value">{{ $poultry_house->created_at->format('d/m/Y') }}</p>
                                            </div>
                                            <div class="col-12">
                                                <p class="info-label">Descripción</p>
                                                <p class="info-value">{{ $poultry_house->description ?: 'No hay descripción disponible.' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-card">
                                        <h3 class="mb-4">Actividad Reciente</h3>
                                        
                                        @if(count($recent_activities) > 0)
                                            @foreach($recent_activities as $activity)
                                            <div class="activity-item">
                                                <h5>{{ $activity->title }}</h5>
                                                <p>{{ $activity->description }}</p>
                                                <p class="activity-date">{{ $activity->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            @endforeach
                                        @else
                                            <p>No hay actividades recientes registradas.</p>
                                        @endif
                                        
                                        <div class="mt-3">
                                            <a href="#" class="btn btn-sm btn-outline-light">Ver todas las actividades</a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4">
                                    <div class="detail-card">
                                        <h3 class="mb-4">Estadísticas</h3>
                                        
                                        <div class="stats-card text-center">
                                            <i class="fas fa-egg stats-icon"></i>
                                            <p class="stats-value">{{ number_format($current_production) }}</p>
                                            <p class="stats-label">Producción Actual (Huevos/Día)</p>
                                        </div>
                                        
                                        <div class="stats-card text-center">
                                            <i class="fas fa-percentage stats-icon"></i>
                                            <p class="stats-value">{{ number_format($production_rate, 1) }}%</p>
                                            <p class="stats-label">Tasa de Postura</p>
                                        </div>
                                        
                                        <div class="stats-card text-center">
                                            <i class="fas fa-drumstick-bite stats-icon"></i>
                                            <p class="stats-value">{{ number_format($current_birds) }}</p>
                                            <p class="stats-label">Aves Actuales</p>
                                        </div>
                                        
                                        <div class="stats-card text-center">
                                            <i class="fas fa-weight stats-icon"></i>
                                            <p class="stats-value">{{ number_format($avg_weight, 2) }}g</p>
                                            <p class="stats-label">Peso Promedio del Huevo</p>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-card">
                                        <h3 class="mb-4">Acciones Rápidas</h3>
                                        
                                        <div class="d-grid gap-2">
                                            <a href="#" class="btn btn-custom mb-2">
                                                <i class="fas fa-clipboard-list me-2"></i> Registrar Producción
                                            </a>
                                            <a href="#" class="btn btn-outline-light mb-2">
                                                <i class="fas fa-print me-2"></i> Generar Reporte
                                            </a>
                                            <a href="{{ route('avicontrol.admin.poultry_houses.edit', $poultry_house->id) }}" class="btn btn-outline-warning mb-2">
                                                <i class="fas fa-edit me-2"></i> Editar Instalación
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <i class="fas fa-trash me-2"></i> Eliminar Instalación
                                            </button>
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
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar la instalación <strong>{{ $poultry_house->name }}</strong>?</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i> Esta acción no se puede deshacer y eliminará todos los datos asociados.</p>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('avicontrol.admin.poultry_houses.destroy', $poultry_house->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
