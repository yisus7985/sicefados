@extends('avicontrol::layouts.admin')

@section('title', 'Movimientos de Inventario - AVICONTROL')

@section('content')
<div class="container-fluid">
    <!-- Error Message -->
    @if(isset($error))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>⚠️ Advertencia:</strong> {{ $error }}
            <br><br>
            <strong>Para solucionar esto:</strong>
            <ol>
                <li>Ejecute el comando: <code>php artisan migrate --path=Modules/AVICONTROL/Database/Migrations</code></li>
                <li>O ejecute: <code>php artisan avicontrol:install-costos</code></li>
            </ol>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-exchange-alt text-primary me-2"></i>
                Movimientos de Inventario
            </h1>
            <p class="text-muted">Historial detallado de entradas, salidas y ajustes de stock</p>
        </div>
        <div>
            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Inventario
            </a>
            <button class="btn btn-success" onclick="exportMovements()">
                <i class="fas fa-download me-1"></i> Exportar CSV
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if(isset($stats))
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Movimientos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-movements">
                                {{ $stats['total_movements'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Entradas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-entries">
                                {{ $stats['entries'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Salidas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-exits">
                                {{ $stats['exits'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Valor Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-value">
                                ${{ number_format($stats['total_value'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Advanced Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filtros Avanzados
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('avicontrol.admin.inventory.movements.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product_id">Producto</label>
                            <select class="form-control" id="product_id" name="product_id">
                                <option value="">Todos los productos</option>
                                @if(isset($products))
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} ({{ $product->code }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="movement_type">Tipo</label>
                            <select class="form-control" id="movement_type" name="movement_type">
                                <option value="">Todos</option>
                                <option value="entry" {{ request('movement_type') == 'entry' ? 'selected' : '' }}>Entrada</option>
                                <option value="exit" {{ request('movement_type') == 'exit' ? 'selected' : '' }}>Salida</option>
                                <option value="adjustment" {{ request('movement_type') == 'adjustment' ? 'selected' : '' }}>Ajuste</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="reference">Referencia</label>
                            <select class="form-control" id="reference" name="reference">
                                <option value="">Todas</option>
                                @if(isset($references))
                                    @foreach($references as $key => $value)
                                        <option value="{{ $key }}" {{ request('reference') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_from">Desde</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_to">Hasta</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex flex-column">
                                <button type="submit" class="btn btn-primary btn-sm mb-1">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('avicontrol.admin.inventory.movements.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="search">Búsqueda</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Buscar por producto, código o notas..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table me-2"></i>Historial de Movimientos
            </h6>
            <div class="d-flex align-items-center">
                @if(isset($movements))
                    <span class="text-muted me-3">Total: {{ $movements->total() }} movimientos</span>
                @endif
                <div class="btn-group">
                    <button class="btn btn-outline-primary btn-sm" onclick="refreshData()">
                        <i class="fas fa-sync-alt me-1"></i> Actualizar
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="exportMovements()">
                        <i class="fas fa-download me-1"></i> Exportar
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="movementsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th><i class="fas fa-calendar me-1"></i>Fecha</th>
                            <th><i class="fas fa-box me-1"></i>Producto</th>
                            <th><i class="fas fa-tag me-1"></i>Tipo</th>
                            <th><i class="fas fa-bookmark me-1"></i>Referencia</th>
                            <th><i class="fas fa-sort-numeric-up me-1"></i>Cantidad</th>
                            <th><i class="fas fa-dollar-sign me-1"></i>P. Unitario</th>
                            <th><i class="fas fa-calculator me-1"></i>Valor Total</th>
                            <th><i class="fas fa-user me-1"></i>Usuario</th>
                            <th><i class="fas fa-sticky-note me-1"></i>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($movements) && $movements->count() > 0)
                            @foreach($movements as $movement)
                            <tr>
                                <td>
                                    <div class="text-nowrap">
                                        <strong>{{ $movement->movement_date->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $movement->movement_date->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $movement->product->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <span class="badge badge-light">{{ $movement->product->code }}</span>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    @if($movement->isEntry())
                                        <span class="badge badge-success">
                                            <i class="fas fa-arrow-down me-1"></i>{{ $movement->movement_type_name }}
                                        </span>
                                    @elseif($movement->isExit())
                                        <span class="badge badge-danger">
                                            <i class="fas fa-arrow-up me-1"></i>{{ $movement->movement_type_name }}
                                        </span>
                                    @else
                                        <span class="badge badge-info">
                                            <i class="fas fa-balance-scale me-1"></i>{{ $movement->movement_type_name }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $movement->reference_name }}</span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <span class="font-weight-bold text-primary">{{ number_format($movement->quantity) }}</span>
                                        <br>
                                        <small class="text-muted">{{ $movement->product->unit_measure }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end">
                                        <span class="font-weight-bold">${{ number_format($movement->unit_price, 2) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end">
                                        <span class="font-weight-bold text-success">${{ number_format($movement->total_value, 2) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        @if($movement->user)
                                            <span class="badge badge-primary">{{ $movement->user->name }}</span>
                                        @else
                                            <span class="badge badge-light">Sistema</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($movement->notes)
                                        <span title="{{ $movement->notes }}" data-toggle="tooltip">
                                            {{ Str::limit($movement->notes, 30) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p class="mb-0">No se encontraron movimientos</p>
                                    @if(!request()->hasAny(['product_id', 'movement_type', 'date_from', 'date_to', 'search']))
                                        <small>Agregue algunos productos al inventario para ver movimientos aquí.</small>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(isset($movements) && $movements instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center mt-3">
                    {{ $movements->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Auto-submit form when filters change
        $('#product_id, #movement_type, #reference').on('change', function() {
            $('#filterForm').submit();
        });

        // Date range validation
        $('#date_to').on('change', function() {
            const dateFrom = $('#date_from').val();
            const dateTo = $(this).val();
            
            if (dateFrom && dateTo && dateFrom > dateTo) {
                alert('La fecha "Desde" no puede ser mayor que la fecha "Hasta"');
                $(this).val('');
            }
        });

        // Search with debounce
        let searchTimeout;
        $('#search').on('keyup', function() {
            clearTimeout(searchTimeout);
            const searchTerm = $(this).val();
            
            searchTimeout = setTimeout(function() {
                if (searchTerm.length >= 3 || searchTerm.length === 0) {
                    $('#filterForm').submit();
                }
            }, 500);
        });

        // Update statistics when filters change
        updateStatistics();
    });

    // Export movements function
    function exportMovements() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', 'csv');
        
        const url = '{{ route("avicontrol.admin.inventory.movements.index") }}?' + params.toString();
        window.open(url, '_blank');
    }

    // Refresh data
    function refreshData() {
        location.reload();
    }

    // Update statistics via AJAX
    function updateStatistics() {
        const formData = $('#filterForm').serialize();
        
        $.get('{{ route("avicontrol.admin.inventory.movements.stats") }}', formData)
            .done(function(data) {
                $('#total-movements').text(data.total_movements || 0);
                $('#total-entries').text(data.entries || 0);
                $('#total-exits').text(data.exits || 0);
                $('#total-value').text('$' + parseFloat(data.total_value || 0).toLocaleString('es-CO', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            })
            .fail(function() {
                console.log('Error al actualizar estadísticas');
            });
    }

    // Table search functionality
    function searchTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('movementsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < cells.length; j++) {
                if (cells[j].textContent.toLowerCase().includes(filter)) {
                    found = true;
                    break;
                }
            }
            
            rows[i].style.display = found ? '' : 'none';
        }
    }

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 10000);
</script>
@endpush

@push('styles')
<style>
    .table th {
        background-color: #f8f9fc;
        border-top: none;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .card-header h6 {
        color: white !important;
    }
    
    .btn-group .btn {
        border-radius: 0.35rem;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .table-responsive {
        border-radius: 0.35rem;
    }
    
    .pagination {
        margin-bottom: 0;
    }
    
    .text-nowrap {
        white-space: nowrap;
    }
    
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.85rem;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group .btn {
            margin-bottom: 0.25rem;
        }
    }
</style>
@endpush