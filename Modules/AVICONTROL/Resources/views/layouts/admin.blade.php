<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AVICONTROL - Administración')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
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
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }
        
        .navbar {
            background-color: var(--primary) !important;
            box-shadow: var(--box-shadow-sm);
        }
        
        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: var(--light) !important;
        }
        
        .navbar-brand i {
            color: var(--tertiary);
            margin-right: 10px;
        }
        
        .nav-link {
            color: var(--light) !important;
            font-weight: 600;
            margin: 0 10px;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--tertiary) !important;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: var(--box-shadow-sm);
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-success {
            background-color: var(--success);
            border-color: var(--success);
        }
        
        .btn-warning {
            background-color: var(--warning);
            border-color: var(--warning);
        }
        
        .btn-danger {
            background-color: var(--danger);
            border-color: var(--danger);
        }
        
        .btn-info {
            background-color: var(--info);
            border-color: var(--info);
        }
        
        .border-left-primary {
            border-left: 4px solid var(--primary) !important;
        }
        
        .border-left-success {
            border-left: 4px solid var(--success) !important;
        }
        
        .border-left-warning {
            border-left: 4px solid var(--warning) !important;
        }
        
        .border-left-danger {
            border-left: 4px solid var(--danger) !important;
        }
        
        .border-left-info {
            border-left: 4px solid var(--info) !important;
        }
        
        .text-primary {
            color: var(--primary) !important;
        }
        
        .text-success {
            color: var(--success) !important;
        }
        
        .text-warning {
            color: var(--warning) !important;
        }
        
        .text-danger {
            color: var(--danger) !important;
        }
        
        .text-info {
            color: var(--info) !important;
        }
        
        .bg-primary {
            background-color: var(--primary) !important;
        }
        
        .bg-success {
            background-color: var(--success) !important;
        }
        
        .bg-warning {
            background-color: var(--warning) !important;
        }
        
        .bg-danger {
            background-color: var(--danger) !important;
        }
        
        .bg-info {
            background-color: var(--info) !important;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark);
        }
        
        .badge {
            font-weight: 500;
        }
        
        .alert {
            border: none;
            border-radius: 8px;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(77, 124, 15, 0.25);
        }
        
        .progress {
            height: 8px;
            border-radius: 4px;
        }
        
        .pagination .page-link {
            color: var(--primary);
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @php
        $isInventoryOrInformation = request()->routeIs('avicontrol.admin.inventory.*') || request()->routeIs('avicontrol.admin.information.*') || request()->routeIs('avicontrol.admin.production_costs.*') || request()->routeIs('avicontrol.admin.poultry_facilities.*') || request()->routeIs('avicontrol.admin.birds.*') || request()->routeIs('avicontrol.admin.alerts.*') || request()->routeIs('avicontrol.admin.food.*') || request()->routeIs('avicontrol.admin.food_consumption.*') || request()->routeIs('avicontrol.admin.food_conversion.*') || request()->routeIs('avicontrol.admin.food_waste.*');
    @endphp
    @if($isInventoryOrInformation)
        <style>
            .sidebar {
                width: 280px;
                background-color: #4d7c0f;
                color: #fff;
                height: 100vh;
                position: fixed;
                left: 0;
                top: 0;
                overflow-y: auto;
                z-index: 1000;
                font-family: 'Open Sans', sans-serif;
                transition: all 0.3s ease;
            }
            .sidebar-header {
                padding: 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-size: 1.2rem;
                font-weight: 700;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            .sidebar-brand {
                color: #fff;
                text-decoration: none;
                display: flex;
                align-items: center;
                font-size: 1.5rem;
                font-weight: 700;
            }
            .sidebar-brand i {
                color: #f59e0b;
                margin-right: 10px;
                font-size: 1.8rem;
            }
            .sidebar-menu {
                padding: 20px 0;
            }
            .menu-header {
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                color: rgba(255,255,255,0.6);
                padding: 10px 25px 5px 25px;
                margin-top: 10px;
            }
            .menu-item {
                padding: 12px 25px;
                display: flex;
                align-items: center;
                color: rgba(255, 255, 255, 0.8);
                text-decoration: none;
                transition: all 0.3s ease;
                border-left: 3px solid transparent;
                font-size: 1rem;
                font-weight: 500;
            }
            .menu-item i {
                margin-right: 10px;
                font-size: 1.1rem;
                width: 20px;
                text-align: center;
            }
            .menu-item.active, .menu-item:hover {
                background-color: rgba(255,255,255,0.1);
                color: #fff;
                border-left: 3px solid #f59e0b;
            }
            .content-wrapper {
                margin-left: 280px;
                padding: 30px 20px 20px 20px;
                min-height: 100vh;
                background: #f1f5f9;
                transition: all 0.3s ease;
            }
            @media (max-width: 991px) {
                .sidebar { 
                    width: 280px; 
                    position: fixed; 
                    height: 100vh; 
                    transform: translateX(-100%);
                    transition: transform 0.3s ease;
                }
                .sidebar.expanded {
                    transform: translateX(0);
                }
                .content-wrapper { 
                    margin-left: 0; 
                    padding: 15px;
                }
            }
            
            /* Estilos para el dropdown de Alimentación */
            .dropdown {
                cursor: pointer;
                position: relative;
                user-select: none;
            }
            
            .dropdown:hover {
                background-color: rgba(255,255,255,0.1);
            }
            
            .dropdown-arrow {
                transition: transform 0.3s ease;
                margin-left: auto;
                font-size: 0.8rem;
            }
            
            .dropdown.active .dropdown-arrow {
                transform: rotate(180deg);
            }
            
            .submenu {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.4s ease;
                background-color: rgba(255,255,255,0.05);
                border-left: 3px solid transparent;
            }
            
            .submenu.show {
                max-height: 250px;
            }
            
            .submenu .menu-item {
                padding-left: 35px;
                font-size: 0.9rem;
                opacity: 0.9;
                transition: all 0.3s ease;
            }
            
            .submenu .menu-item:hover {
                opacity: 1;
                background-color: rgba(255,255,255,0.1);
            }
        </style>
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('avicontrol.admin.welcome') }}" class="sidebar-brand">
                    <i class="fas fa-feather-alt"></i>
                    <span>AVICONTROL</span>
                </a>
            </div>
            <div class="sidebar-menu">
                <div class="menu-header">PRINCIPAL</div>
                <a href="{{ route('avicontrol.admin.welcome') }}" class="menu-item {{ request()->routeIs('avicontrol.admin.welcome') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <div class="menu-header">MÓDULOS</div>
                <a href="{{ route('avicontrol.admin.inventory.index') }}" class="menu-item {{ request()->routeIs('avicontrol.admin.inventory.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i>
                    <span>Inventario</span>
                </a>
                <!-- Módulo de Producción - Deshabilitado temporalmente (en desarrollo por otro equipo) -->
                <a href="#" class="menu-item" style="opacity: 0.5; cursor: not-allowed;" title="En desarrollo por otro equipo">
                    <i class="fas fa-egg"></i>
                    <span>Producción</span>
                </a>
                                               <div class="menu-item dropdown {{ request()->routeIs('avicontrol.admin.food.*') ? 'active' : '' }}" id="alimentacion-dropdown" style="cursor: pointer;">
                        <i class="fas fa-utensils"></i>
                        <span>Alimentación</span>
                        <i class="fas fa-chevron-down ms-auto dropdown-arrow"></i>
                    </div>
                    <div class="submenu {{ request()->routeIs('avicontrol.admin.food.*') ? 'show' : '' }}" id="alimentacion-submenu">
                        <a href="{{ route('avicontrol.admin.food_consumption.index') }}" class="menu-item {{ request()->routeIs('avicontrol.admin.food_consumption.*') ? 'active' : '' }}" style="padding-left: 35px;">
                            <i class="fas fa-weight"></i>
                            <span>Consumo de Alimento</span>
                        </a>
                        <a href="{{ route('avicontrol.admin.food_conversion.index') }}" class="menu-item {{ request()->routeIs('avicontrol.admin.food_conversion.*') ? 'active' : '' }}" style="padding-left: 35px;">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Conversión Alimenticia</span>
                        </a>
                        <a href="{{ route('avicontrol.admin.food_waste.index') }}" class="menu-item {{ request()->routeIs('avicontrol.admin.food_waste.*') ? 'active' : '' }}" style="padding-left: 35px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Control de Mermas</span>
                        </a>
                    </div>
                <a href="{{ route('avicontrol.admin.production_costs.index') }}" class="menu-item {{ request()->routeIs('avicontrol.admin.production_costs.*') ? 'active' : '' }}">
                    <i class="fas fa-calculator"></i>
                    <span>Costos de Producción</span>
                </a>
                <a href="{{ route('avicontrol.admin.information.index') }}" class="menu-item {{ request()->routeIs('avicontrol.admin.information.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Informes</span>
                </a>
                <div class="menu-header">CONFIGURACIÓN</div>
                <a href="{{ route('avicontrol.admin.poultry_facilities.index') }}" class="menu-item {{ request()->routeIs('avicontrol.admin.poultry_facilities.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Instalaciones</span>
                </a>
                <a href="{{ route('avicontrol.admin.birds.index') }}" class="menu-item {{ request()->routeIs('avicontrol.admin.birds.*') ? 'active' : '' }}">
                    <i class="fas fa-dove"></i>
                    <span>Gestión de Aves</span>
                </a>
                <a href="{{ route('avicontrol.admin.alerts.index') }}" class="menu-item {{ request()->routeIs('avicontrol.admin.alerts.*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Alertas</span>
                </a>
                <!-- Módulo de Normativas - Deshabilitado temporalmente (en desarrollo por otro equipo) -->
                <a href="#" class="menu-item" style="opacity: 0.5; cursor: not-allowed;" title="En desarrollo por otro equipo">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Normativas</span>
                </a>
                <div class="menu-header">CUENTA</div>
                <!-- Módulo de Perfil - Deshabilitado temporalmente (en desarrollo por otro equipo) -->
                <a href="#" class="menu-item" style="opacity: 0.5; cursor: not-allowed;" title="En desarrollo por otro equipo">
                    <i class="fas fa-user-cog"></i>
                    <span>Perfil</span>
                </a>
                <a href="{{ route('avicontrol.admin.logout') }}" class="menu-item" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
                <form id="logout-form-sidebar" action="{{ route('avicontrol.admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        <div class="content-wrapper">
            <!-- Botón de toggle para móviles -->
            <div class="d-lg-none mb-3">
                <button class="btn btn-primary" id="sidebarToggle">
                    <i class="fas fa-bars"></i> Menú
                </button>
            </div>
            @yield('content')
        </div>
    @else
        <!-- Navigation superior (para el resto) -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('avicontrol.admin.welcome') }}">
                    <i class="fas fa-feather-alt"></i>
                    AVICONTROL
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('avicontrol.admin.welcome') }}">
                                <i class="fas fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('avicontrol.admin.inventory.index') }}">
                                <i class="fas fa-boxes me-1"></i> Inventario
                            </a>
                        </li>
                                                 <li class="nav-item">
                             <a class="nav-link" href="{{ route('avicontrol.admin.food.dashboard') }}">
                                 <i class="fas fa-utensils me-1"></i> Alimentación
                             </a>
                         </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('avicontrol.admin.poultry_facilities.index') }}">
                                <i class="fas fa-warehouse me-1"></i> Instalaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('avicontrol.admin.birds.index') }}">
                                <i class="fas fa-dove me-1"></i> Aves
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('avicontrol.admin.information.index') }}">
                                <i class="fas fa-chart-bar me-1"></i> Informes
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> Administrador
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-1"></i> Configuración</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('avicontrol.admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión</a></li>
                                <form id="logout-form" action="{{ route('avicontrol.admin.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @if(session('success'))
                <div class="container-fluid">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="container-fluid">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            @if(session('warning'))
                <div class="container-fluid">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            @if($errors->any())
                <div class="container-fluid">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Error:</strong> Por favor corrige los siguientes errores:
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
            @yield('content')
        </main>
    @endif

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} AVICONTROL - Sistema de Gestión Avícola SENA</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0">Desarrollado con <i class="fas fa-heart text-danger"></i> para el SENA</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (if needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- JavaScript para el dropdown de Alimentación -->
    <script>
        $(document).ready(function() {
            // Funcionalidad del dropdown de Alimentación
            $('#alimentacion-dropdown').click(function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $dropdown = $(this);
                var $submenu = $('#alimentacion-submenu');
                
                // Toggle del dropdown con animación
                if ($dropdown.hasClass('active')) {
                    // Cerrar el dropdown
                    $dropdown.removeClass('active');
                    $submenu.removeClass('show');
                } else {
                    // Abrir el dropdown
                    $dropdown.addClass('active');
                    $submenu.addClass('show');
                }
            });
            
            // Si estamos en una página de alimentación, mantener el dropdown abierto inicialmente
            if (window.location.href.includes('food_consumption') || window.location.href.includes('food_conversion') || window.location.href.includes('food_waste')) {
                $('#alimentacion-dropdown').addClass('active');
                $('#alimentacion-submenu').addClass('show');
            }
            
            // Cerrar dropdown si se hace clic fuera de él
            $(document).click(function(e) {
                if (!$(e.target).closest('#alimentacion-dropdown, #alimentacion-submenu').length) {
                    $('#alimentacion-dropdown').removeClass('active');
                    $('#alimentacion-submenu').removeClass('show');
                }
            });
            
            // Asegurar que el sidebar esté visible en dispositivos móviles
            if (window.innerWidth <= 991) {
                $('.sidebar').addClass('expanded');
            }
            
            // Funcionalidad del botón de toggle para móviles
            $('#sidebarToggle').click(function() {
                $('.sidebar').toggleClass('expanded');
            });
            
            // Cerrar sidebar al hacer clic en un enlace en móviles
            $('.sidebar .menu-item').click(function() {
                if (window.innerWidth <= 991) {
                    $('.sidebar').removeClass('expanded');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html> 