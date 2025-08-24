<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - AVICONTROL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background-color: var(--light);
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--box-shadow-sm);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-md);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.8rem;
        }
        
        .stat-icon.primary {
            background-color: rgba(77, 124, 15, 0.1);
            color: var(--primary);
        }
        
        .stat-icon.success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .stat-icon.warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }
        
        .stat-icon.danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }
        
        .stat-info h3 {
            font-size: 1.8rem;
            margin: 0;
            font-weight: 700;
        }
        
        .stat-info p {
            margin: 0;
            color: var(--dark);
            opacity: 0.7;
            font-size: 0.9rem;
        }
        
        .dashboard-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
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
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        .activity-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .activity-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        .activity-icon.primary {
            background-color: rgba(77, 124, 15, 0.1);
            color: var(--primary);
        }
        
        .activity-icon.success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .activity-icon.warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }
        
        .activity-icon.danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }
        
        .activity-info h5 {
            margin: 0 0 5px;
            font-size: 1rem;
        }
        
        .activity-info p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--dark);
            opacity: 0.7;
        }
        
        .activity-time {
            font-size: 0.8rem;
            color: var(--dark);
            opacity: 0.5;
            margin-top: 5px;
        }
        
        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .module-card {
            background-color: var(--light);
            border-radius: 10px;
            box-shadow: var(--box-shadow-sm);
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-md);
        }
        
        .module-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2rem;
        }
        
        .module-icon.primary {
            background-color: rgba(77, 124, 15, 0.1);
            color: var(--primary);
        }
        
        .module-icon.success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }
        
        .module-icon.warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }
        
        .module-icon.danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }
        
        .module-icon.info {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }
        
        .module-icon.secondary {
            background-color: rgba(180, 83, 9, 0.1);
            color: var(--secondary);
        }
        
        .module-title {
            font-size: 1.2rem;
            margin: 0 0 10px;
        }
        
        .module-description {
            font-size: 0.9rem;
            color: var(--dark);
            opacity: 0.7;
            margin-bottom: 15px;
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
        
        .btn-custom-outline {
            background-color: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-custom-outline:hover {
            background-color: var(--primary);
            color: var(--light);
            transform: translateY(-2px);
        }
        
        .alert-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            background-color: rgba(245, 158, 11, 0.1);
            border-left: 4px solid var(--warning);
        }
        
        .alert-icon {
            margin-right: 15px;
            font-size: 1.2rem;
            color: var(--warning);
        }
        
        .alert-info {
            flex: 1;
        }
        
        .alert-title {
            margin: 0 0 5px;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .alert-description {
            margin: 0;
            font-size: 0.9rem;
            color: var(--dark);
            opacity: 0.8;
        }
        
        .alert-action {
            margin-left: 15px;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                transform: translateX(0);
            }
            
            .sidebar.expanded {
                width: 280px;
            }
            
            .sidebar-brand span, .menu-item span, .menu-header {
                display: none;
            }
            
            .sidebar.expanded .sidebar-brand span, 
            .sidebar.expanded .menu-item span, 
            .sidebar.expanded .menu-header {
                display: inline;
            }
            
            .content-wrapper {
                margin-left: 80px;
            }
            
            .sidebar.expanded + .content-wrapper {
                margin-left: 280px;
            }
            
            .dashboard-content {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-stats {
                grid-template-columns: 1fr;
            }
            
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
            
            .sidebar.expanded + .content-wrapper {
                margin-left: 0;
            }
            
            .top-bar {
                padding: 10px 15px;
            }
            
            .page-title {
                font-size: 1.2rem;
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
            <button class="btn btn-link text-light d-lg-none" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="sidebar-menu">
            <div class="menu-header">Principal</div>
            <a href="{{ route('avicontrol.admin.welcome') }}" class="menu-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="menu-header">Módulos</div>
            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="menu-item">
                <i class="fas fa-warehouse"></i>
                <span>Inventario</span>
            </a>
            <a href="{{ route('avicontrol.admin.production.index') }}" class="menu-item">
                <i class="fas fa-egg"></i>
                <span>Producción</span>
            </a>
                                       <a href="{{ route('avicontrol.admin.food_consumption.index') }}" class="menu-item">
            <i class="fas fa-utensils"></i>
            <span>Alimentación</span>
             </a>
            <a href="{{ route('avicontrol.admin.production_costs.index') }}" class="menu-item">
                <i class="fas fa-calculator"></i>
                <span>Costos de Producción</span>
            </a>
            <a href="{{ route('avicontrol.admin.information.index') }}" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                <span>Informes</span>
            </a>
            
            <div class="menu-header">Configuración</div>
            <a href="{{ route('avicontrol.admin.poultry_facilities.index') }}" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Instalaciones</span>
            </a>
            <a href="{{ route('avicontrol.admin.birds.index') }}" class="menu-item">
                <i class="fas fa-dove"></i>
                <span>Gestión de Aves</span>
            </a>
            <a href="{{ route('avicontrol.admin.alerts.index') }}" class="menu-item">
                <i class="fas fa-bell"></i>
                <span>Alertas</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-clipboard-check"></i>
                <span>Normativas</span>
            </a>
            
            <div class="menu-header">Cuenta</div>
            <a href="#" class="menu-item">
                <i class="fas fa-user-cog"></i>
                <span>Perfil</span>
            </a>
            <a href="{{ route('avicontrol.admin.logout') }}" class="menu-item" onclick="event.preventDefault(); document.getElementById('logout-form-welcome').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
            <form id="logout-form-welcome" action="{{ route('avicontrol.admin.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
    
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1 class="page-title">Panel de Administración</h1>
            <div class="user-info">
                <span class="user-name">Administrador Yisus</span>
                <div class="user-avatar">Y</div>
            </div>
        </div>
        
        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-info">
                    <h3>12</h3>
                    <p>Instalaciones Activas</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-egg"></i>
                </div>
                <div class="stat-info">
                    <h3>2,500</h3>
                    <p>Producción Diaria (Huevos)</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-info">
                    <h3>85%</h3>
                    <p>Tasa de Postura</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ count($dashboardAlerts) }}</h3>
                    <p>Alertas Pendientes</p>
                </div>
            </div>
        </div>

        <!-- Gestión de Instalaciones Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><i class="fas fa-warehouse me-2"></i>Gestión de Instalaciones Avícolas</h4>
                            <p class="mb-0">Cree, edite y administre las instalaciones avícolas del sistema</p>
                        </div>
                        <a href="{{ route('avicontrol.admin.poultry_facilities.index') }}" class="btn btn-light">
                            <i class="fas fa-plus-circle me-1"></i> Gestionar Instalaciones
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Inventario Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-success text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><i class="fas fa-boxes me-2"></i>Gestión de Inventario</h4>
                            <p class="mb-0">Administre y controle los productos e insumos del inventario</p>
                        </div>
                        <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-light">
                            <i class="fas fa-boxes me-1"></i> Gestionar Inventario
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Aves Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-success text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><i class="fas fa-dove me-2"></i>Gestión de Aves</h4>
                            <p class="mb-0">Registre y administre los lotes de aves en cada instalación</p>
                        </div>
                        <a href="{{ route('avicontrol.admin.birds.index') }}" class="btn btn-light">
                            <i class="fas fa-plus-circle me-1"></i> Gestionar Aves
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gestión de Informes Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-info text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Gestión de Informes</h4>
                            <p class="mb-0">Genere y consulte informes detallados de las instalaciones y producción</p>
                        </div>
                        <a href="{{ route('avicontrol.admin.information.index') }}" class="btn btn-light">
                            <i class="fas fa-chart-bar me-1"></i> Gestionar Informes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Production Chart -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Resumen de Producción</h5>
                    <div>
                        <button class="btn btn-custom-outline btn-sm">
                            <i class="fas fa-download"></i> Exportar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="productionChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Actividad Reciente</h5>
                    <div>
                        <button class="btn btn-custom-outline btn-sm">
                            Ver Todo
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="activity-item">
                        <div class="activity-icon primary">
                            <i class="fas fa-egg"></i>
                        </div>
                        <div class="activity-info">
                            <h5>Registro de Producción</h5>
                            <p>Se registró la producción de la Instalación #3</p>
                            <div class="activity-time">Hace 30 minutos</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="activity-info">
                            <h5>Actualización de Inventario</h5>
                            <p>Se actualizó el inventario de alimentos</p>
                            <div class="activity-time">Hace 2 horas</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="activity-info">
                            <h5>Alerta de Stock</h5>
                            <p>Stock bajo de medicamentos</p>
                            <div class="activity-time">Hace 5 horas</div>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon danger">
                            <i class="fas fa-skull"></i>
                        </div>
                        <div class="activity-info">
                            <h5>Registro de Mortalidad</h5>
                            <p>Se registró mortalidad en la Instalación #5</p>
                            <div class="activity-time">Ayer</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Alerts Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Alertas del Sistema</h5>
                <div>
                    <a href="{{ route('avicontrol.admin.alerts.index') }}" class="btn btn-custom-outline btn-sm">
                        Gestionar Alertas
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(count($dashboardAlerts) > 0)
                    @foreach($dashboardAlerts as $alert)
                        <div class="alert-item">
                            <div class="alert-icon">
                                <i class="fas {{ $alert['icon'] }} text-{{ $alert['color'] }}"></i>
                            </div>
                            <div class="alert-info">
                                <h5 class="alert-title">{{ $alert['title'] }}</h5>
                                <p class="alert-description">{{ $alert['message'] }}</p>
                            </div>
                            <div class="alert-action">
                                <a href="{{ route('avicontrol.admin.alerts.index') }}" class="btn btn-custom btn-sm">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-success fa-2x mb-3"></i>
                        <h6 class="text-muted">No hay alertas activas</h6>
                        <p class="text-muted mb-0">El sistema está funcionando correctamente</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Modules Section -->
        <h2 class="mt-4 mb-3">Módulos del Sistema</h2>
        <div class="module-grid">
            <div class="module-card">
                <div class="module-icon primary">
                    <i class="fas fa-warehouse"></i>
                </div>
                <h3 class="module-title">Inventario</h3>
                <p class="module-description">Gestión de productos, suministros y niveles de stock.</p>
                <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-custom">Acceder</a>
            </div>
            
            <div class="module-card">
                <div class="module-icon success">
                    <i class="fas fa-egg"></i>
                </div>
                <h3 class="module-title">Producción</h3>
                <p class="module-description">Registro y seguimiento de parámetros de producción diaria.</p>
                <a href="{{ route('avicontrol.admin.production.index') }}" class="btn btn-custom">Acceder</a>
            </div>
            
            <div class="module-card">
                <div class="module-icon secondary">
                    <i class="fas fa-utensils"></i>
                </div>
                                 <h3 class="module-title">Alimentación</h3>
                 <p class="module-description">Control de consumo de alimento para producción avícola.</p>
                                            <a href="{{ route('avicontrol.admin.food_consumption.index') }}" class="btn btn-custom">Acceder</a>
            </div>
            
            <div class="module-card">
                <div class="module-icon warning">
                    <i class="fas fa-calculator"></i>
                </div>
                <h3 class="module-title">Costos</h3>
                <p class="module-description">Cálculo y monitoreo de costos de producción.</p>
                <button class="btn btn-custom">Acceder</button>
            </div>
            
          <div class="module-card">
    <div class="module-icon info">
        <i class="fas fa-chart-bar"></i>
    </div>
    <h3 class="module-title">Informes</h3>
    <p class="module-description">Generación de informes y estadísticas detalladas.</p>
    <a href="{{ route('avicontrol.admin.information.index') }}" class="btn btn-custom">Acceder</a>
</div>

            
            <div class="module-card">
                <div class="module-icon success">
                    <i class="fas fa-dove"></i>
                </div>
                <h3 class="module-title">Gestión de Aves</h3>
                <p class="module-description">Registre y controle los lotes de aves por instalación.</p>
                <a href="{{ route('avicontrol.admin.birds.index') }}" class="btn btn-custom">Acceder</a>
            </div>
            
            <div class="module-card">
                <div class="module-icon danger">
                    <i class="fas fa-cog"></i>
                </div>
                <h3 class="module-title">Configuración</h3>
                <p class="module-description">Gestión de instalaciones, alertas y normativas.</p>
                <a href="{{ route('avicontrol.admin.poultry_facilities.index') }}" class="btn btn-custom">Acceder</a>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Toggle Sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('expanded');
        });
        
        // Production Chart
        const productionCtx = document.getElementById('productionChart').getContext('2d');
        const productionChart = new Chart(productionCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Producción Diaria (Huevos)',
                    data: [2300, 2450, 2380, 2500, 2600, 2400, 2350],
                    backgroundColor: 'rgba(77, 124, 15, 0.1)',
                    borderColor: 'rgba(77, 124, 15, 1)',
                    pointBackgroundColor: 'rgba(77, 124, 15, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(77, 124, 15, 1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 2000,
                        ticks: {
                            maxTicksLimit: 5
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Initialize AOS
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
        
        // Set current year in footer
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate loading data
            setTimeout(() => {
                document.querySelectorAll('.stat-card').forEach(card => {
                    card.classList.add('aos-animate');
                });
            }, 300);
        });
        
        // Función para actualizar el contador de alertas
        function updateAlertCount() {
            fetch('{{ route("avicontrol.admin.alerts.stats") }}')
                .then(response => response.json())
                .then(data => {
                    const alertCountElement = document.querySelector('.stat-card .stat-info h3');
                    if (alertCountElement) {
                        alertCountElement.textContent = data.total_alerts;
                    }
                })
                .catch(error => {
                    console.error('Error al actualizar contador de alertas:', error);
                });
        }
        
        // Actualizar contador cada 30 segundos
        setInterval(updateAlertCount, 30000);
    </script>
</body>
</html>
