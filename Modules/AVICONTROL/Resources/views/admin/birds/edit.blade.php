<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Lote de Aves - AVICONTROL</title>
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
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
        }
        
        .text-danger {
            color: var(--danger) !important;
        }
        
        .alert-info {
            background-color: #e0f7fa;
            border-color: #b2ebf2;
            color: var(--info);
        }
        
        #capacityWarning {
            font-weight: 600;
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
                <i class="fas fa-edit text-warning me-2"></i>
                Editar Lote: {{ $bird->batch_code }}
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
                    <i class="fas fa-edit"></i> Editar Lote
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0">Modifique la información del lote de aves</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('avicontrol.admin.birds.show', $bird->id) }}" class="btn btn-info">
                    <i class="fas fa-eye me-1"></i> Ver Detalles
                </a>
                <a href="{{ route('avicontrol.admin.birds.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver a la Lista
                </a>
            </div>
        </div>

        <!-- Mensajes de Éxito o Error -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Formulario -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-dove me-1"></i> Información del Lote
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('avicontrol.admin.birds.update', $bird->id) }}" method="POST" id="birdForm">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Información Básica -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-1"></i> Información Básica
                            </h5>

                            <div class="mb-3">
                                <label for="poultry_facility_id" class="form-label">
                                    Instalación Avícola <span class="text-danger">*</span>
                                </label>
                                <select name="poultry_facility_id" id="poultry_facility_id" 
                                        class="form-select @error('poultry_facility_id') is-invalid @enderror" 
                                        required onchange="updateFacilityInfo()">
                                    <option value="">Seleccione una instalación</option>
                                    @foreach($poultryFacilities as $facility)
                                        <option value="{{ $facility->id }}"
                                                {{ old('poultry_facility_id', $bird->poultry_facility_id) == $facility->id ? 'selected' : '' }}
                                                data-capacity="{{ $facility->capacity }}"
                                                data-current="{{ $facility->total_active_birds ?? 0 }}"
                                                data-available="{{ $facility->available_capacity ?? $facility->capacity }}">
                                            {{ $facility->name }} (Disponible: {{ $facility->available_capacity ?? $facility->capacity }} aves)
                                        </option>
                                    @endforeach
                                </select>
                                @error('poultry_facility_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="facilityInfo" class="mt-2" style="display: none;">
                                    <div class="alert alert-info">
                                        <strong>Información de la Instalación:</strong><br>
                                        <span id="facilityCapacity"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="batch_code" class="form-label">
                                    Código de Lote <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="batch_code" id="batch_code" 
                                       class="form-control @error('batch_code') is-invalid @enderror" 
                                       value="{{ old('batch_code', $bird->batch_code) }}" 
                                       placeholder="Ej: LOTE-2025-001" 
                                       required>
                                @error('batch_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Código único para identificar este lote</div>
                            </div>

                            <div class="mb-3">
                                <label for="bird_type" class="form-label">
                                    Tipo de Ave <span class="text-danger">*</span>
                                </label>
                                <select name="bird_type" id="bird_type" 
                                        class="form-select @error('bird_type') is-invalid @enderror" 
                                        required>
                                    <option value="">Seleccione el tipo de ave</option>
                                    <option value="laying_hens" {{ old('bird_type', $bird->bird_type) == 'laying_hens' ? 'selected' : '' }}>
                                        Gallinas Ponedoras
                                    </option>
                                    <option value="broilers" {{ old('bird_type', $bird->bird_type) == 'broilers' ? 'selected' : '' }}>
                                        Pollos de Engorde
                                    </option>
                                    <option value="chicks" {{ old('bird_type', $bird->bird_type) == 'chicks' ? 'selected' : '' }}>
                                        Pollitos
                                    </option>
                                    <option value="breeders" {{ old('bird_type', $bird->bird_type) == 'breeders' ? 'selected' : '' }}>
                                        Reproductores
                                    </option>
                                    <option value="roosters" {{ old('bird_type', $bird->bird_type) == 'roosters' ? 'selected' : '' }}>
                                        Gallos
                                    </option>
                                </select>
                                @error('bird_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">
                                    Cantidad de Aves <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="quantity" id="quantity" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       value="{{ old('quantity', $bird->quantity) }}" 
                                       min="0" max="{{ $bird->initial_quantity }}"
                                       placeholder="Número de aves en el lote" 
                                       required 
                                       onchange="validateCapacity()">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Cantidad inicial: {{ number_format($bird->initial_quantity) }} aves
                                    <br>Máximo permitido: {{ number_format($bird->initial_quantity) }} aves
                                </div>
                                <div id="capacityWarning" class="form-text text-warning" style="display: none;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    La cantidad excede la capacidad disponible
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="entry_date" class="form-label">
                                    Fecha de Ingreso <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="entry_date" id="entry_date" 
                                       class="form-control @error('entry_date') is-invalid @enderror" 
                                       value="{{ old('entry_date', $bird->entry_date) }}" 
                                       required>
                                @error('entry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">
                                    Estado <span class="text-danger">*</span>
                                </label>
                                <select name="status" id="status" 
                                        class="form-select @error('status') is-invalid @enderror" 
                                        required>
                                    <option value="active" {{ old('status', $bird->status) == 'active' ? 'selected' : '' }}>
                                        Activo
                                    </option>
                                    <option value="sold" {{ old('status', $bird->status) == 'sold' ? 'selected' : '' }}>
                                        Vendido
                                    </option>
                                    <option value="deceased" {{ old('status', $bird->status) == 'deceased' ? 'selected' : '' }}>
                                        Fallecido
                                    </option>
                                    <option value="transferred" {{ old('status', $bird->status) == 'transferred' ? 'selected' : '' }}>
                                        Transferido
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Información Adicional -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-clipboard-list me-1"></i> Información Adicional
                            </h5>

                            <div class="mb-3">
                                <label for="age_weeks" class="form-label">Edad (Semanas)</label>
                                <input type="number" name="age_weeks" id="age_weeks" 
                                       class="form-control @error('age_weeks') is-invalid @enderror" 
                                       value="{{ old('age_weeks', $bird->age_weeks) }}" 
                                       min="0" max="200" 
                                       placeholder="Edad en semanas">
                                @error('age_weeks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="breed" class="form-label">Raza</label>
                                <select name="breed" id="breed" 
                                        class="form-select @error('breed') is-invalid @enderror">
                                    <option value="">Seleccione una raza</option>
                                    <option value="Rhode Island Red" {{ old('breed', $bird->breed) == 'Rhode Island Red' ? 'selected' : '' }}>Rhode Island Red</option>
                                    <option value="Leghorn" {{ old('breed', $bird->breed) == 'Leghorn' ? 'selected' : '' }}>Leghorn</option>
                                    <option value="Plymouth Rock" {{ old('breed', $bird->breed) == 'Plymouth Rock' ? 'selected' : '' }}>Plymouth Rock</option>
                                    <option value="Sussex" {{ old('breed', $bird->breed) == 'Sussex' ? 'selected' : '' }}>Sussex</option>
                                    <option value="Orpington" {{ old('breed', $bird->breed) == 'Orpington' ? 'selected' : '' }}>Orpington</option>
                                    <option value="Australorp" {{ old('breed', $bird->breed) == 'Australorp' ? 'selected' : '' }}>Australorp</option>
                                    <option value="New Hampshire" {{ old('breed', $bird->breed) == 'New Hampshire' ? 'selected' : '' }}>New Hampshire</option>
                                    <option value="Cornish Cross" {{ old('breed', $bird->breed) == 'Cornish Cross' ? 'selected' : '' }}>Cornish Cross</option>
                                    <option value="Cobb 500" {{ old('breed', $bird->breed) == 'Cobb 500' ? 'selected' : '' }}>Cobb 500</option>
                                    <option value="Ross 308" {{ old('breed', $bird->breed) == 'Ross 308' ? 'selected' : '' }}>Ross 308</option>
                                    <option value="Otro" {{ old('breed', $bird->breed) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('breed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="average_weight" class="form-label">Peso Promedio (kg)</label>
                                <input type="number" name="average_weight" id="average_weight" 
                                       class="form-control @error('average_weight') is-invalid @enderror" 
                                       value="{{ old('average_weight', $bird->average_weight) }}" 
                                       step="0.01" min="0" 
                                       placeholder="Peso promedio por ave">
                                @error('average_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="purchase_price" class="form-label">Precio de Compra (por ave)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="purchase_price" id="purchase_price" 
                                           class="form-control @error('purchase_price') is-invalid @enderror" 
                                           value="{{ old('purchase_price', $bird->purchase_price) }}" 
                                           step="0.01" min="0" 
                                           placeholder="0.00">
                                </div>
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="supplier" class="form-label">Proveedor</label>
                                <input type="text" name="supplier" id="supplier" 
                                       class="form-control @error('supplier') is-invalid @enderror" 
                                       value="{{ old('supplier', $bird->supplier) }}" 
                                       placeholder="Nombre del proveedor">
                                @error('supplier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notas</label>
                                <textarea name="notes" id="notes" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          rows="3" 
                                          placeholder="Observaciones adicionales sobre el lote">{{ old('notes', $bird->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Resumen del Lote -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-calculator me-1"></i> Resumen del Lote
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Inversión Total:</strong>
                                            <div id="totalInvestment" class="h5 text-success">$0.00</div>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Costo por Ave:</strong>
                                            <div id="costPerBird" class="h5 text-info">$0.00</div>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Peso Total Estimado:</strong>
                                            <div id="totalWeight" class="h5 text-warning">0 kg</div>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Ocupación del Galpón:</strong>
                                            <div id="occupancyPercentage" class="h5 text-primary">0%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('avicontrol.admin.birds.show', $bird->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye me-1"></i> Ver Detalles
                                </a>
                                <a href="{{ route('avicontrol.admin.birds.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-custom">
                                    <i class="fas fa-save me-1"></i> Actualizar Lote
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateFacilityInfo() {
            const select = document.getElementById('poultry_facility_id');
            const selectedOption = select.options[select.selectedIndex];
            const facilityInfo = document.getElementById('facilityInfo');
            const facilityCapacity = document.getElementById('facilityCapacity');

            if (selectedOption.value) {
                const capacity = selectedOption.dataset.capacity;
                const current = selectedOption.dataset.current;
                const available = selectedOption.dataset.available;

                facilityCapacity.innerHTML = `
                    <strong>Capacidad Total:</strong> ${capacity} aves<br>
                    <strong>Aves Actuales:</strong> ${current} aves<br>
                    <strong>Capacidad Disponible:</strong> ${available} aves
                `;
                facilityInfo.style.display = 'block';

                validateCapacity();
            } else {
                facilityInfo.style.display = 'none';
            }

            updateSummary();
        }

        function validateCapacity() {
            const select = document.getElementById('poultry_facility_id');
            const selectedOption = select.options[select.selectedIndex];
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const warning = document.getElementById('capacityWarning');
            const currentBirdQuantity = {{ $bird->quantity }};
            const currentFacilityId = {{ $bird->poultry_facility_id }};

            if (selectedOption.value && quantity > 0) {
                const available = parseInt(selectedOption.dataset.available);
                const facilityId = selectedOption.value;
                let adjustedAvailable = available;

                if (facilityId == currentFacilityId) {
                    adjustedAvailable += currentBirdQuantity;
                }

                if (quantity > adjustedAvailable) {
                    warning.style.display = 'block';
                    warning.innerHTML = `
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        La cantidad (${quantity}) excede la capacidad disponible (${adjustedAvailable} aves)
                    `;
                } else {
                    warning.style.display = 'none';
                }
            } else {
                warning.style.display = 'none';
            }

            updateSummary();
        }

        function updateSummary() {
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const price = parseFloat(document.getElementById('purchase_price').value) || 0;
            const weight = parseFloat(document.getElementById('average_weight').value) || 0;

            const select = document.getElementById('poultry_facility_id');
            const selectedOption = select.options[select.selectedIndex];

            const totalInvestment = quantity * price;
            const totalWeight = quantity * weight;

            let occupancyPercentage = 0;
            if (selectedOption.value) {
                const capacity = parseInt(selectedOption.dataset.capacity);
                const current = parseInt(selectedOption.dataset.current);
                const facilityId = selectedOption.value;
                const currentFacilityId = {{ $bird->poultry_facility_id }};
                const currentBirdQuantity = {{ $bird->quantity }};

                let adjustedCurrent = current;
                if (facilityId == currentFacilityId) {
                    adjustedCurrent = current - currentBirdQuantity + quantity;
                } else {
                    adjustedCurrent = current + quantity;
                }

                occupancyPercentage = (adjustedCurrent / capacity * 100).toFixed(1);
            }

            document.getElementById('totalInvestment').textContent = `$${totalInvestment.toFixed(2)}`;
            document.getElementById('costPerBird').textContent = `$${price.toFixed(2)}`;
            document.getElementById('totalWeight').textContent = `${totalWeight.toFixed(2)} kg`;
            document.getElementById('occupancyPercentage').textContent = `${occupancyPercentage}%`;
        }

        document.getElementById('quantity').addEventListener('input', validateCapacity);
        document.getElementById('purchase_price').addEventListener('input', updateSummary);
        document.getElementById('average_weight').addEventListener('input', updateSummary);
        document.getElementById('poultry_facility_id').addEventListener('change', updateFacilityInfo);

        document.addEventListener('DOMContentLoaded', function() {
            updateFacilityInfo();
        });
    </script>
</body>
</html>