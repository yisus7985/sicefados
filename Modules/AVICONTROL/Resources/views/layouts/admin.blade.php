<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        $isInventory = request()->routeIs('avicontrol.admin.inventory.*');
    @endphp
    @if($isInventory)
        <style>
            .sidebar {
                width: 220px;
                background-color: #4d7c0f;
                color: #fff;
                height: 100vh;
                position: fixed;
                left: 0;
                top: 0;
                overflow-y: auto;
                z-index: 1000;
                font-family: 'Open Sans', sans-serif;
            }
            .sidebar-header {
                padding: 20px 20px 10px 20px;
                display: flex;
                align-items: center;
                font-size: 1.2rem;
                font-weight: 700;
                border-bottom: 1px solid rgba(255,255,255,0.08);
            }
            .sidebar-brand {
                color: #fff;
                text-decoration: none;
                display: flex;
                align-items: center;
                font-size: 1.2rem;
                font-weight: 700;
            }
            .sidebar-brand i {
                color: #f59e0b;
                margin-right: 10px;
                font-size: 1.5rem;
            }
            .sidebar-menu {
                padding: 10px 0;
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
                padding: 10px 25px;
                display: flex;
                align-items: center;
                color: #fff;
                text-decoration: none;
                transition: all 0.2s;
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
                background-color: rgba(255,255,255,0.08);
                color: #fff;
                border-left: 3px solid #f59e0b;
            }
            .content-wrapper {
                margin-left: 220px;
                padding: 30px 20px 20px 20px;
                min-height: 100vh;
                background: #f1f5f9;
            }
            @media (max-width: 991px) {
                .sidebar { width: 100vw; position: relative; height: auto; }
                .content-wrapper { margin-left: 0; }
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
                <a href="{{ route('avicontrol.admin.welcome') }}" class="menu-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <div class="menu-header">MÓDULOS</div>
                <a href="{{ route('avicontrol.admin.inventory.index') }}" class="menu-item active">
                    <i class="fas fa-boxes"></i>
                    <span>Inventario</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-egg"></i>
                    <span>Producción</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-calculator"></i>
                    <span>Costos</span>
                </a>
                <a href="{{ route('avicontrol.admin.information.index') }}" class="menu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Informes</span>
                </a>
                <div class="menu-header">CONFIGURACIÓN</div>
                <a href="{{ route('avicontrol.admin.poultry_facilities.index') }}" class="menu-item">
                    <i class="fas fa-cog"></i>
                    <span>Instalaciones</span>
                </a>
                <a href="{{ route('avicontrol.admin.birds.index') }}" class="menu-item">
                    <i class="fas fa-dove"></i>
                    <span>Gestión de Aves</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-bell"></i>
                    <span>Alertas</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Normativas</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
                <div class="menu-header">CUENTA</div>
                <a href="#" class="menu-item">
                    <i class="fas fa-user-cog"></i>
                    <span>Perfil</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </div>
        <div class="content-wrapper">
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
                                <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión</a></li>
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
    
    @stack('scripts')
</body>
</html> 