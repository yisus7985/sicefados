<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Lote: {{ $bird->batch_code }} - AVICONTROL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4d7c0f;
            --primary-dark: #3f6a0a;
            --secondary: #b45309;
            --tertiary: #f59e0b;
            --tertiary-dark: #d88e09;
            --light: #f8fafc;
            --dark: #334155;
            --accent: #f97316;
            --background: #f1f5f9;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --box-shadow-sm: 0 5px 15px rgba(0, 0, 0, 0.1);
            --box-shadow-md: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--background);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }
        
        .sidebar {
            width: 280px;
            background-color: var(--primary);
            color: var(--light);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--light);
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .sidebar-brand i {
            margin-right: 10px;
            font-size: 1.8rem;
            color: var(--tertiary);
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .menu-header {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.6);
            padding: 10px 25px;
            margin-top: 15px;
        }
        
        .menu-item {
            padding: 12px 25px;
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover, .menu-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--light);
            border-left-color: var(--tertiary);
        }
        
        .menu-item i {
            margin-right: 10px;
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }
        
        .content-wrapper {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .top-bar {
            background-color: var(--light);
            border-radius: 10px;
            padding: 15px 25px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--box-shadow-sm);
        }
        
        .page-title {
            font-size: 1.5rem;
            margin: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-name {
            margin-right: 15px;
            font-weight: 600;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--tertiary);
            color: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        
        .card {
            background-color: var(--light);
            border-radius: 10px;
            box-shadow: var(--box-shadow-sm);
            border: none;
            margin-bottom: 25px;
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-title {
            margin: 0;
            font-size: 1.2rem;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn-custom {
            background-color: var(--primary);
            color: var(--light);
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-custom:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            color: var(--light);
        }
        
        .table-borderless td {
            border: none;
            padding: 0.5rem 0;
        }
        
        .fw-bold {
            font-weight: 600 !important;
        }
        
        .text-gray-600 {
            color: #6c757d !important;
        }
        
        .border-end {
            border-right: 1px solid #dee2e6 !important;
        }
        
        .progress {
            height: 8px;
        }
        
        .btn-group .btn {
            margin-right: 2px;
        }
        
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        
        @media (max-width: 768px) {
            .content-wrapper {
                margin-left: 0;
                padding: 15px;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.expanded {
                transform: translateX(0);
                width: 280px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('avicontrol.admin.welcome') }}" class="sidebar-brand">
                <i class="fas fa-feather-alt"></i>
                <span>AVICONTROL</span>
            </a>
        </div>
        <div class="sidebar-menu">
            <div class="menu-header">Principal</div>
            <a href="{{ route('avicontrol.admin.welcome') }}" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="menu-header">Módulos</div>
            <a href="{{ route('avicontrol.admin.poultry_facilities.index') }}" class="menu-item">
                <i class="fas fa-warehouse"></i>
                <span>Galpones</span>
            </a>
            <a href="{{ route('avicontrol.admin.birds.index') }}" class="menu-item active">
                <i class="fas fa-dove"></i>
                <span>Gestión de Aves</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-egg"></i>
                <span>Producción</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-utensils"></i>
                <span>Alimentación</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-medkit"></i>
                <span>Salud</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                <span>Reportes</span>
            </a>
            
            <div class="menu-header">Cuenta</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1 class="page-title">
                <i class="fas fa-dove text-success me-2"></i>
                Detalles del Lote: {{ $bird->batch_code }}
            </h1>
            <div class="user-info">
                <span class="user-name">Administrador Yisus</span>
                <div class="user-avatar">Y</div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('avicontrol.admin.welcome') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-eye"></i> Detalles del Lote
                </li>
            </ol>
        </nav>

        <div class="row">
            <!-- Información Principal -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-1"></i> Información del Lote
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold text-gray-600">Código de Lote:</td>
                                        <td>
                                            <span class="badge bg-primary fs-6">{{ $bird->batch_code }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-gray-600">Instalación:</td>
                                        <td>
                                            <i class="fas fa-warehouse text-primary me-1"></i>
                                            <a href="{{ route('avicontrol.admin.poultry_facilities.show', $bird->poultryFacility->id) }}" 
                                               class="text-decoration-none">
                                                {{ $bird->poultryFacility->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-gray-600">Tipo de Ave:</td>
                                        <td>
                                            @switch($bird->bird_type)
                                                @case('laying_hens')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-egg me-1"></i> Gallinas Ponedoras
                                                    </span>
                                                    @break
                                                @case('broilers')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-drumstick-bite me-1"></i> Pollos de Engorde
                                                    </span>
                                                    @break
                                                @case('chicks')
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-baby me-1"></i> Pollitos
                                                    </span>
                                                    @break
                                                @case('breeders')
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-heart me-1"></i> Reproductores
                                                    </span>
                                                    @break
                                                @case('roosters')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-cocktail me-1"></i> Gallos
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-gray-600">Cantidad:</td>
                                        <td>
                                            {{ number_format($bird->quantity) }} aves
                                            @if($bird->initial_quantity != $bird->quantity)
                                                <br><small class="text-muted">Inicial: {{ number_format($bird->initial_quantity) }} aves</small>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-gray-600">Edad:</td>
                                        <td>
                                            @if($bird->age_weeks)
                                                {{ $bird->age_weeks }} semanas
                                            @else
                                                <span class="text-muted">No especificado</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-gray-600">Fecha de Ingreso:</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($bird->entry_date)->format('d/m/Y') }}
                                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($bird->entry_date)->diffForHumans() }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-gray-600">Estado:</td>
                                        <td>
                                            @switch($bird->status)
                                                @case('active')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i> Activo
                                                    </span>
                                                    @break
                                                @case('sold')
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-dollar-sign me-1"></i> Vendido
                                                    </span>
                                                    @break
                                                @case('deceased')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-skull me-1"></i> Fallecido
                                                    </span>
                                                    @break
                                                @case('transferred')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-exchange-alt me-1"></i> Transferido
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold text-gray-600">Raza:</td>
                                        <td>{{ $bird->breed ?: 'No especificada' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-gray-600">Peso Promedio:</td>
                                        <td>{{ $bird->average_weight ? number_format($bird->average_weight, 2) . ' kg' : 'No especificado' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-gray-600">Precio de Compra:</td>
                                        <td>{{ $bird->purchase_price ? '$' . number_format($bird->purchase_price, 2) : 'No especificado' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-gray-600">Proveedor:</td>
                                        <td>{{ $bird->supplier ?: 'No especificado' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-gray-600">Notas:</td>
                                        <td>{{ $bird->notes ?: 'Sin notas' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas y Acciones -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-pie me-1"></i> Estadísticas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="mb-1 fw-bold text-gray-600">Ocupación del Galpón:</p>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $bird->poultryFacility->total_active_birds / $bird->poultryFacility->capacity * 100 }}%" 
                                     aria-valuenow="{{ $bird->poultryFacility->total_active_birds }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $bird->poultryFacility->capacity }}">
                                    {{ number_format($bird->poultryFacility->total_active_birds / $bird->poultryFacility->capacity * 100, 1) }}%
                                </div>
                            </div>
                            <small class="text-muted">
                                Capacidad: {{ $bird->poultryFacility->capacity }} aves
                            </small>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1 fw-bold text-gray-600">Inversión Total:</p>
                            <h5 class="text-success">
                                ${{ number_format($bird->quantity * ($bird->purchase_price ?: 0), 2) }}
                            </h5>
                        </div>
                        <div class="mb-3">
                            <p class="mb-1 fw-bold text-gray-600">Peso Total Estimado:</p>
                            <h5 class="text-warning">
                                {{ number_format($bird->quantity * ($bird->average_weight ?: 0), 2) }} kg
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-tools me-1"></i> Acciones
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="btn-group-vertical w-100" role="group">
                            <a href="{{ route('avicontrol.admin.birds.edit', $bird->id) }}" class="btn btn-warning mb-2">
                                <i class="fas fa-edit me-1"></i> Editar Lote
                            </a>
                            <a href="{{ route('avicontrol.admin.birds.index') }}" class="btn btn-secondary mb-2">
                                <i class="fas fa-arrow-left me-1"></i> Volver a la Lista
                            </a>
                            <button type="button" class="btn btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-1"></i> Eliminar Lote
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación para eliminar -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Confirmar Eliminación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Está seguro de que desea eliminar el lote <strong>{{ $bird->batch_code }}</strong>?</p>
                        <p class="text-muted">Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('avicontrol.admin.birds.destroy', $bird->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>