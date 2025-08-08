<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Poultry Facility - AVICONTROL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
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
        }
        
        .btn-custom:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
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
        
        .btn-danger-custom {
            background-color: var(--danger);
            color: var(--light);
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-danger-custom:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(77, 124, 15, 0.25);
        }
        
        .form-text {
            color: var(--dark);
            opacity: 0.7;
        }
        
        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .form-section:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .form-section-title {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            color: var(--primary);
        }
        
        .required-field::after {
            content: '*';
            color: var(--danger);
            margin-left: 4px;
        }
        
        .invalid-feedback {
            display: block;
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.25rem;
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
            <div class="menu-header">Main</div>
            <a href="{{ route('avicontrol.admin.welcome') }}" class="menu-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="menu-header">Modules</div>
            <a href="#" class="menu-item">
                <i class="fas fa-warehouse"></i>
                <span>Inventory</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-egg"></i>
                <span>Production</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-calculator"></i>
                <span>Costs</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            
            <div class="menu-header">Configuration</div>
            <a href="{{ route('avicontrol.admin.poultry_facilities.index') }}" class="menu-item active">
                <i class="fas fa-cog"></i>
                <span>Poultry Facilities</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-bell"></i>
                <span>Alerts</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-clipboard-check"></i>
                <span>Regulations</span>
            </a>
            
            <div class="menu-header">Account</div>
            <a href="#" class="menu-item">
                <i class="fas fa-user-cog"></i>
                <span>Profile</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
    
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1 class="page-title">Edit Poultry Facility</h1>
            <div class="user-info">
                <span class="user-name">Administrator Yisus</span>
                <div class="user-avatar">Y</div>
            </div>
        </div>
        
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong> Please correct the following errors.
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <!-- Edit Poultry Facility Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Poultry Facility Information</h5>
                <a href="{{ route('avicontrol.admin.poultry_facilities.index') }}" class="btn btn-custom-outline">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('avicontrol.admin.poultry_facilities.update', $poultryFacility->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-section">
                        <h6 class="form-section-title">Basic Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label required-field">Poultry Facility Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $poultryFacility->name) }}" required>
                                <div class="form-text">Assign a unique name to identify the poultry facility.</div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label required-field">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $poultryFacility->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $poultryFacility->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="maintenance" {{ old('status', $poultryFacility->status) == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                </select>
                                <div class="form-text">Current status of the poultry facility.</div>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $poultryFacility->description) }}</textarea>
                            <div class="form-text">Optional description of the poultry facility.</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h6 class="form-section-title">Dimensions and Capacity</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="length" class="form-label required-field">Length (meters)</label>
                                <input type="number" step="0.01" class="form-control @error('length') is-invalid @enderror" id="length" name="length" value="{{ old('length', $poultryFacility->length) }}" required>
                                @error('length')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="width" class="form-label required-field">Width (meters)</label>
                                <input type="number" step="0.01" class="form-control @error('width') is-invalid @enderror" id="width" name="width" value="{{ old('width', $poultryFacility->width) }}" required>
                                @error('width')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="height" class="form-label required-field">Height (meters)</label>
                                <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('height', $poultryFacility->height) }}" required>
                                @error('height')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label required-field">Capacity (birds)</label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity', $poultryFacility->capacity) }}" required>
                                <div class="form-text">Maximum number of birds the poultry facility can hold.</div>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="creation_date" class="form-label required-field">Creation Date</label>
                                <input type="date" class="form-control @error('creation_date') is-invalid @enderror" id="creation_date" name="creation_date" value="{{ old('creation_date', $poultryFacility->creation_date->format('Y-m-d')) }}" required>
                                @error('creation_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Area</h6>
                                        <p class="card-text" id="area_calculated">{{ $poultryFacility->length * $poultryFacility->width }} m²</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Volume</h6>
                                        <p class="card-text" id="volume_calculated">{{ $poultryFacility->length * $poultryFacility->width * $poultryFacility->height }} m³</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Density</h6>
                                        <p class="card-text" id="density_calculated">{{ $poultryFacility->length * $poultryFacility->width > 0 ? number_format($poultryFacility->capacity / ($poultryFacility->length * $poultryFacility->width), 2) : 0 }} birds/m²</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-danger-custom" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i> Delete Facility
                        </button>
                        <div>
                            <button type="button" class="btn btn-secondary me-2" onclick="window.location.href='{{ route('avicontrol.admin.poultry_facilities.index') }}'">Cancel</button>
                            <button type="submit" class="btn btn-custom">
                                <i class="fas fa-save me-1"></i> Update Facility
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this poultry facility? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('avicontrol.admin.poultry_facilities.destroy', $poultryFacility->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger-custom">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('expanded');
        });
        
        // Calculate Area, Volume and Density
        function calculateMetrics() {
            const length = parseFloat(document.getElementById('length').value) || 0;
            const width = parseFloat(document.getElementById('width').value) || 0;
            const height = parseFloat(document.getElementById('height').value) || 0;
            const capacity = parseInt(document.getElementById('capacity').value) || 0;
            
            const area = length * width;
            const volume = area * height;
            const density = area > 0 ? capacity / area : 0;
            
            document.getElementById('area_calculated').textContent = area.toFixed(2) + ' m²';
            document.getElementById('volume_calculated').textContent = volume.toFixed(2) + ' m³';
            document.getElementById('density_calculated').textContent = density.toFixed(2) + ' birds/m²';
        }
        
        // Add event listeners to inputs
        document.getElementById('length').addEventListener('input', calculateMetrics);
        document.getElementById('width').addEventListener('input', calculateMetrics);
        document.getElementById('height').addEventListener('input', calculateMetrics);
        document.getElementById('capacity').addEventListener('input', calculateMetrics);
        
        // Calculate on page load
        document.addEventListener('DOMContentLoaded', calculateMetrics);
    </script>
</body>
</html>
