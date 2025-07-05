@extends('avicontrol::layouts.admin')

@section('title', 'Productos por Vencer - Inventario')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clock text-danger me-2"></i>
                Productos por Vencer
            </h1>
            <p class="text-muted">Productos que vencen en los próximos 30 días</p>
        </div>
        <div>
            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Inventario
            </a>
        </div>
    </div>

    <!-- Alert Banner -->
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-clock me-2"></i>
        <strong>Urgente:</strong> Se encontraron {{ $products->total() }} productos por vencer. 
        Se recomienda revisar y tomar acciones inmediatas.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Products Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-danger">Productos por Vencer</h6>
            <div class="d-flex align-items-center">
                <span class="text-muted me-3">Total: {{ $products->total() }} productos</span>
                <button class="btn btn-outline-danger btn-sm" onclick="exportExpiring()">
                    <i class="fas fa-download me-1"></i> Exportar
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="expiringTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Fecha de Vencimiento</th>
                            <th>Días Restantes</th>
                            <th>Stock Actual</th>
                            <th>Precio Unitario</th>
                            <th>Valor Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr class="{{ $product->isExpired() ? 'table-danger' : ($product->expiration_date->diffInDays(now()) <= 7 ? 'table-warning' : 'table-info') }}">
                            <td>
                                <span class="badge badge-primary">{{ $product->code }}</span>
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if($product->description)
                                    <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $product->category_name }}</span>
                            </td>
                            <td>
                                <div class="text-center">
                                    <strong>{{ $product->expiration_date->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $product->expiration_date->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                @php
                                    $daysLeft = $product->expiration_date->diffInDays(now(), false);
                                @endphp
                                <div class="text-center">
                                    @if($daysLeft < 0)
                                        <span class="badge badge-danger">VENCIDO</span>
                                        <br>
                                        <small class="text-danger">{{ abs($daysLeft) }} días atrás</small>
                                    @elseif($daysLeft <= 7)
                                        <span class="badge badge-warning">{{ $daysLeft }} días</span>
                                        <br>
                                        <small class="text-warning">¡Urgente!</small>
                                    @else
                                        <span class="badge badge-info">{{ $daysLeft }} días</span>
                                        <br>
                                        <small class="text-info">Por vencer</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <span class="font-weight-bold">{{ $product->current_stock }}</span>
                                    <br>
                                    <small class="text-muted">{{ $product->unit_measure }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-end">
                                    ${{ number_format($product->unit_price, 2) }}
                                </div>
                            </td>
                            <td>
                                <div class="text-end">
                                    <span class="font-weight-bold">${{ number_format($product->total_value, 2) }}</span>
                                </div>
                            </td>
                            <td>
                                @if($product->isExpired())
                                    <span class="badge badge-danger">Vencido</span>
                                @elseif($daysLeft <= 7)
                                    <span class="badge badge-warning">Urgente</span>
                                @else
                                    <span class="badge badge-info">Por Vencer</span>
                                @endif
                                @if($product->isLowStock())
                                    <i class="fas fa-exclamation-triangle text-warning ml-1" title="Stock bajo"></i>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('avicontrol.admin.inventory.show', $product->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('avicontrol.admin.inventory.movements.create', $product->id) }}?type=exit&reference=discard" 
                                       class="btn btn-sm btn-outline-danger" title="Registrar descarte">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <a href="{{ route('avicontrol.admin.inventory.edit', $product->id) }}" 
                                       class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="text-success">
                                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                                    <p>¡Excelente! No hay productos por vencer.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    @if($products->count() > 0)
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Vencidos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->filter(function($p) { return $p->isExpired(); })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
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
                                Urgentes (≤7 días)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->filter(function($p) { 
                                    return !$p->isExpired() && $p->expiration_date->diffInDays(now()) <= 7; 
                                })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                Por Vencer (>7 días)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->filter(function($p) { 
                                    return !$p->isExpired() && $p->expiration_date->diffInDays(now()) > 7; 
                                })->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Valor en Riesgo
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($products->sum('total_value'), 2) }}
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
    // Export expiring products function
    function exportExpiring() {
        const link = document.createElement('a');
        link.href = '{{ route("avicontrol.admin.inventory.expiring") }}?export=true';
        link.download = 'productos_por_vencer_' + new Date().toISOString().split('T')[0] + '.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Search functionality
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.className = 'form-control form-control-sm';
    searchInput.placeholder = 'Buscar productos...';
    searchInput.style.width = '200px';
    
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.getElementById('expiringTable');
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

    // Auto-refresh every 5 minutes
    setTimeout(function() {
        location.reload();
    }, 300000);

    // Sort by days remaining
    function sortByDaysRemaining() {
        const table = document.getElementById('expiringTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = Array.from(tbody.getElementsByTagName('tr'));
        
        rows.sort((a, b) => {
            const daysA = parseInt(a.cells[4].textContent.match(/\d+/)[0]) || 999;
            const daysB = parseInt(b.cells[4].textContent.match(/\d+/)[0]) || 999;
            return daysA - daysB;
        });
        
        rows.forEach(row => tbody.appendChild(row));
    }

    // Add sort button
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.card-header');
        const sortBtn = document.createElement('button');
        sortBtn.className = 'btn btn-outline-secondary btn-sm ms-2';
        sortBtn.innerHTML = '<i class="fas fa-sort me-1"></i> Ordenar por Urgencia';
        sortBtn.onclick = sortByDaysRemaining;
        header.appendChild(sortBtn);
    });
</script>
@endpush 