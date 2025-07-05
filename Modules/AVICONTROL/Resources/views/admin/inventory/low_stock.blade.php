@extends('avicontrol::layouts.admin')

@section('title', 'Stock Bajo - Inventario')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                Productos con Stock Bajo
            </h1>
            <p class="text-muted">Productos que requieren atención inmediata</p>
        </div>
        <div>
            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Inventario
            </a>
        </div>
    </div>

    <!-- Alert Banner -->
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Atención:</strong> Se encontraron {{ $products->total() }} productos con stock bajo. 
        Se recomienda realizar compras o ajustes de inventario.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Products Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-warning">Productos con Stock Bajo</h6>
            <div class="d-flex align-items-center">
                <span class="text-muted me-3">Total: {{ $products->total() }} productos</span>
                <button class="btn btn-outline-warning btn-sm" onclick="exportLowStock()">
                    <i class="fas fa-download me-1"></i> Exportar
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="lowStockTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                            <th>Déficit</th>
                            <th>Precio Unitario</th>
                            <th>Valor Necesario</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr class="{{ $product->current_stock == 0 ? 'table-danger' : 'table-warning' }}">
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
                                    <span class="font-weight-bold {{ $product->current_stock == 0 ? 'text-danger' : 'text-warning' }}">
                                        {{ $product->current_stock }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $product->unit_measure }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    {{ $product->minimum_stock }}
                                    <br>
                                    <small class="text-muted">{{ $product->unit_measure }}</small>
                                </div>
                            </td>
                            <td>
                                @php
                                    $deficit = $product->minimum_stock - $product->current_stock;
                                @endphp
                                <div class="text-center">
                                    <span class="badge badge-danger">{{ $deficit }}</span>
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
                                @php
                                    $valueNeeded = $deficit * $product->unit_price;
                                @endphp
                                <div class="text-end">
                                    <span class="font-weight-bold text-danger">${{ number_format($valueNeeded, 2) }}</span>
                                </div>
                            </td>
                            <td>
                                @if($product->current_stock == 0)
                                    <span class="badge badge-danger">Sin Stock</span>
                                @else
                                    <span class="badge badge-warning">Stock Bajo</span>
                                @endif
                                @if($product->isExpiringSoon())
                                    <i class="fas fa-clock text-danger ml-1" title="Por vencer"></i>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('avicontrol.admin.inventory.show', $product->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('avicontrol.admin.inventory.movements.create', $product->id) }}" 
                                       class="btn btn-sm btn-outline-success" title="Registrar entrada">
                                        <i class="fas fa-plus"></i>
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
                                    <p>¡Excelente! No hay productos con stock bajo.</p>
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
                                Sin Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('current_stock', 0)->count() }}
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
                                Stock Bajo
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->where('current_stock', '>', 0)->count() }}
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
                                Total Déficit
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $products->sum(function($p) { return $p->minimum_stock - $p->current_stock; }) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-minus-circle fa-2x text-gray-300"></i>
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
                                Valor Necesario
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($products->sum(function($p) { 
                                    return ($p->minimum_stock - $p->current_stock) * $p->unit_price; 
                                }), 2) }}
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
    // Export low stock function
    function exportLowStock() {
        const link = document.createElement('a');
        link.href = '{{ route("avicontrol.admin.inventory.low_stock") }}?export=true';
        link.download = 'stock_bajo_' + new Date().toISOString().split('T')[0] + '.csv';
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
        const table = document.getElementById('lowStockTable');
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
</script>
@endpush 