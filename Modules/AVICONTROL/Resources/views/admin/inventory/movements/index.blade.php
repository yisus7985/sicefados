@extends('avicontrol::layouts.admin')

@section('title', 'Movimientos de Inventario - AVICONTROL')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-exchange-alt text-primary me-2"></i>
                Movimientos de Inventario
            </h1>
            <p class="text-muted">Historial de entradas, salidas y ajustes de stock</p>
        </div>
        <div>
            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Inventario
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('avicontrol.admin.inventory.movements.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product_id">Producto</label>
                            <select class="form-control" id="product_id" name="product_id">
                                <option value="">Todos los productos</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->code }})
                                    </option>
                                @endforeach
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i> Filtrar
                                </button>
                                <a href="{{ route('avicontrol.admin.inventory.movements.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Movimientos de Inventario</h6>
            <div class="d-flex align-items-center">
                <span class="text-muted me-3">Total: {{ $movements->total() }} movimientos</span>
                <button class="btn btn-outline-primary btn-sm" onclick="exportMovements()">
                    <i class="fas fa-download me-1"></i> Exportar
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="movementsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Referencia</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Valor Total</th>
                            <th>Usuario</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
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
                                    <small class="text-muted">{{ $movement->product->code }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{ $movement->isEntry() ? 'success' : ($movement->isExit() ? 'danger' : 'info') }}">
                                    {{ $movement->movement_type_name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $movement->reference_name }}</span>
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="font-weight-bold">{{ $movement->quantity }}</span>
                                    <br>
                                    <small class="text-muted">{{ $movement->product->unit_measure }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-end">
                                    ${{ number_format($movement->unit_price, 2) }}
                                </div>
                            </td>
                            <td>
                                <div class="text-end">
                                    <span class="font-weight-bold">${{ number_format($movement->total_value, 2) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="badge badge-light">{{ $movement->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>
                                @if($movement->notes)
                                    <span title="{{ $movement->notes }}">{{ Str::limit($movement->notes, 30) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No se encontraron movimientos</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $movements->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    @if($movements->count() > 0)
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Entradas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $movements->where('movement_type', 'entry')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Salidas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $movements->where('movement_type', 'exit')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
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
                                Ajustes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $movements->where('movement_type', 'adjustment')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
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
                                Valor Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($movements->sum('total_value'), 2) }}
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
</div>
@endsection

@push('scripts')
<script>
    // Export movements function
    function exportMovements() {
        // Get current filters
        const params = new URLSearchParams(window.location.search);
        params.append('export', 'true');
        
        // Create download link
        const link = document.createElement('a');
        link.href = '{{ route("avicontrol.admin.inventory.movements.index") }}?' + params.toString();
        link.download = 'movimientos_inventario_' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Auto-submit form when filters change
    document.getElementById('product_id').addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('movement_type').addEventListener('change', function() {
        this.form.submit();
    });

    // Date range validation
    document.getElementById('date_to').addEventListener('change', function() {
        const dateFrom = document.getElementById('date_from').value;
        const dateTo = this.value;
        
        if (dateFrom && dateTo && dateFrom > dateTo) {
            alert('La fecha "Desde" no puede ser mayor que la fecha "Hasta"');
            this.value = '';
        }
    });

    // Search functionality
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.className = 'form-control form-control-sm';
    searchInput.placeholder = 'Buscar movimientos...';
    searchInput.style.width = '200px';
    
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.getElementById('movementsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let row of rows) {
            const cells = row.getElementsByTagName('td');
            let found = false;
            
            for (let cell of cells) {
                if (cell.textContent.toLowerCase().includes(searchTerm)) {
                    found = true;
                    break;
                }
            }
            
            row.style.display = found ? '' : 'none';
        }
    });

    // Add search input to header
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.card-header');
        header.appendChild(searchInput);
    });
</script>
@endpush 