<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Aves - AVICONTROL</title>
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
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .table th {
            background-color: #f8f9fc;
            border-color: #e3e6f0;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            <a href="#" class="menu-item">
                <i class="fas fa-warehouse"></i>
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
            <a href="#" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                <span>Informes</span>
            </a>
            
            <div class="menu-header">Configuración</div>
            <a href="{{ route('avicontrol.admin.poultry_facilities.index') }}" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Instalaciones</span>
            </a>
            <a href="{{ route('avicontrol.admin.birds.index') }}" class="menu-item active">
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
            
            <div class="menu-header">Cuenta</div>
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
    
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1 class="page-title">
                <i class="fas fa-dove text-success me-2"></i>
                Gestión de Aves
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
                    <i class="fas fa-dove"></i> Gestión de Aves
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0">Administre los lotes de aves en las instalaciones avícolas</p>
            </div>
            <a href="{{ route('avicontrol.admin.birds.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>
                Registrar Nuevo Lote
            </a>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('avicontrol.admin.birds.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="facility_id" class="form-label">Instalación</label>
                        <select name="facility_id" id="facility_id" class="form-select">
                            <option value="">Todas las instalaciones</option>
                            @foreach($poultryFacilities as $facility)
                                <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="bird_type" class="form-label">Tipo de Ave</label>
                        <select name="bird_type" id="bird_type" class="form-select">
                            <option value="">Todos los tipos</option>
                            <option value="laying_hens" {{ request('bird_type') == 'laying_hens' ? 'selected' : '' }}>Gallinas Ponedoras</option>
                            <option value="broilers" {{ request('bird_type') == 'broilers' ? 'selected' : '' }}>Pollos de Engorde</option>
                            <option value="chicks" {{ request('bird_type') == 'chicks' ? 'selected' : '' }}>Pollitos</option>
                            <option value="breeders" {{ request('bird_type') == 'breeders' ? 'selected' : '' }}>Reproductores</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Estado</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Vendido</option>
                            <option value="deceased" {{ request('status') == 'deceased' ? 'selected' : '' }}>Fallecido</option>
                            <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferido</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                Filtrar
                            </button>
                            <a href="{{ route('avicontrol.admin.birds.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total de Aves Activas
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $birds->where('status', 'active')->sum('quantity') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dove fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Lotes Registrados
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $birds->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Gallinas Ponedoras
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $birds->where('bird_type', 'laying_hens')->where('status', 'active')->sum('quantity') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-egg fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Pollos de Engorde
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $birds->where('bird_type', 'broilers')->where('status', 'active')->sum('quantity') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-drumstick-bite fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Aves -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table me-1"></i>
                    Lista de Lotes de Aves
                </h6>
            </div>
            <div class="card-body">
                @if($birds->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Código de Lote</th>
                                    <th>Instalación</th>
                                    <th>Tipo de Ave</th>
                                    <th>Cantidad</th>
                                    <th>Edad (Semanas)</th>
                                    <th>Fecha de Ingreso</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($birds as $bird)
                                    <tr>
                                        <td>
                                            <strong>{{ $bird->batch_code }}</strong>
                                            @if($bird->breed)
                                                <br><small class="text-muted">{{ $bird->breed }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <i class="fas fa-warehouse text-primary me-1"></i>
                                            {{ $bird->poultryFacility->name }}
                                        </td>
                                        <td>
                                            @switch($bird->bird_type)
                                                @case('laying_hens')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-egg me-1"></i>
                                                        Gallinas Ponedoras
                                                    </span>
                                                    @break
                                                @case('broilers')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-drumstick-bite me-1"></i>
                                                        Pollos de Engorde
                                                    </span>
                                                    @break
                                                @case('chicks')
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-baby me-1"></i>
                                                        Pollitos
                                                    </span>
                                                    @break
                                                @case('breeders')
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-heart me-1"></i>
                                                        Reproductores
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <strong>{{ number_format($bird->quantity) }}</strong>
                                            @if($bird->initial_quantity != $bird->quantity)
                                                <br><small class="text-muted">Inicial: {{ number_format($bird->initial_quantity) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($bird->age_weeks)
                                                {{ $bird->age_weeks }} semanas
                                            @else
                                                <span class="text-muted">No especificado</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($bird->entry_date)->format('d/m/Y') }}
                                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($bird->entry_date)->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @switch($bird->status)
                                                @case('active')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        Activo
                                                    </span>
                                                    @break
                                                @case('sold')
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-dollar-sign me-1"></i>
                                                        Vendido
                                                    </span>
                                                    @break
                                                @case('deceased')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-skull me-1"></i>
                                                        Fallecido
                                                    </span>
                                                    @break
                                                @case('transferred')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-exchange-alt me-1"></i>
                                                        Transferido
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('avicontrol.admin.birds.show', $bird->id) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('avicontrol.admin.birds.edit', $bird->id) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Eliminar"
                                                        onclick="confirmDelete({{ $bird->id }}, '{{ $bird->batch_code }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-dove fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-600">No hay lotes de aves registrados</h5>
                        <p class="text-muted">Comience registrando su primer lote de aves.</p>
                        <a href="{{ route('avicontrol.admin.birds.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>
                            Registrar Primer Lote
                        </a>
                    </div>
                @endif
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
                    <p>¿Está seguro de que desea eliminar el lote <strong id="batchCodeToDelete"></strong>?</p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        function confirmDelete(birdId, batchCode) {
            document.getElementById('batchCodeToDelete').textContent = batchCode;
            document.getElementById('deleteForm').action = '{{ route("avicontrol.admin.birds.destroy", ":id") }}'.replace(':id', birdId);
            
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        // DataTable initialization
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                "pageLength": 25,
                "order": [[ 5, "desc" ]], // Ordenar por fecha de ingreso descendente
                "columnDefs": [
                    { "orderable": false, "targets": 7 } // Deshabilitar ordenamiento en columna de acciones
                ]
            });
        });
    </script>
</body>
</html>
