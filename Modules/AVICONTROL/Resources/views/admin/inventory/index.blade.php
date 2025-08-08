@extends('avicontrol::layouts.admin')

@section('title', 'Gestión de Inventario - AVICONTROL')

@section('content')
<div class="container-fluid">
    <!-- Mensaje de error si las tablas no existen -->
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
    
    <div class="row">
        <!-- Contenido principal (izquierda) -->
        <div class="col-lg-9">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-boxes text-primary me-2"></i>
                        Gestión de Inventario
                    </h1>
                    <p class="text-muted">Control y seguimiento de productos e insumos avícolas</p>
                </div>
                <div>
                    <a href="{{ route('avicontrol.admin.inventory.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nuevo Producto
                    </a>
                    <a href="{{ route('avicontrol.admin.inventory.movements.index') }}" class="btn btn-outline-primary ms-2">
                        <i class="fas fa-exchange-alt me-1"></i> Movimientos
                    </a>
                </div>
            </div>
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Productos
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_products'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Stock Bajo
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['low_stock_products'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Por Vencer
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['expiring_products'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Valor Total
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_value'], 2) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('avicontrol.admin.inventory.low_stock') }}" class="btn btn-warning btn-block">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Ver Stock Bajo
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('avicontrol.admin.inventory.expiring') }}" class="btn btn-danger btn-block">
                                        <i class="fas fa-clock me-2"></i>
                                        Ver Por Vencer
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('avicontrol.admin.inventory.movements.index') }}" class="btn btn-info btn-block">
                                        <i class="fas fa-exchange-alt me-2"></i>
                                        Ver Movimientos
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="{{ route('avicontrol.admin.inventory.create') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-plus me-2"></i>
                                        Agregar Producto
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Products Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Productos del Inventario</h6>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar productos...">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="productsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Stock Actual</th>
                                    <th>Stock Mínimo</th>
                                    <th>Precio Unitario</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
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
                                        <div class="d-flex align-items-center">
                                            <span class="font-weight-bold">{{ $product->current_stock }} {{ $product->unit_measure }}</span>
                                            @if($product->isLowStock())
                                                <i class="fas fa-exclamation-triangle text-warning ml-2" title="Stock bajo"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $product->minimum_stock }} {{ $product->unit_measure }}</td>
                                    <td>${{ number_format($product->unit_price, 2) }}</td>
                                    <td>
                                        @if($product->status === 'active')
                                            <span class="badge badge-success">Activo</span>
                                        @elseif($product->status === 'inactive')
                                            <span class="badge badge-secondary">Inactivo</span>
                                        @else
                                            <span class="badge badge-danger">Vencido</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('avicontrol.admin.inventory.show', $product->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('avicontrol.admin.inventory.edit', $product->id) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('avicontrol.admin.inventory.movements.create', $product->id) }}" 
                                               class="btn btn-sm btn-outline-info" title="Registrar movimiento">
                                                <i class="fas fa-exchange-alt"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteProduct({{ $product->id }})" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Panel lateral derecho (resumen y actividad reciente) -->
        <div class="col-lg-3">
            <!-- Resumen de Inventario -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="m-0 fw-bold"><i class="fas fa-chart-bar me-2"></i>Resumen de Inventario</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-boxes text-success me-2"></i> Productos: <strong>{{ $stats['total_products'] }}</strong></li>
                        <li class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Stock Bajo: <strong>{{ $stats['low_stock_products'] }}</strong></li>
                        <li class="mb-2"><i class="fas fa-clock text-danger me-2"></i> Por Vencer: <strong>{{ $stats['expiring_products'] }}</strong></li>
                        <li><i class="fas fa-dollar-sign text-info me-2"></i> Valor Total: <strong>${{ number_format($stats['total_value'], 2) }}</strong></li>
                    </ul>
                </div>
            </div>
            <!-- Actividad Reciente -->
            <div class="card shadow mb-4">
                <div class="card-header bg-light py-3">
                    <h6 class="m-0 fw-bold text-success"><i class="fas fa-history me-2"></i>Actividad Reciente</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($recent_activity as $activity)
                            <li class="mb-3">
                                <span class="badge bg-success me-2"><i class="fas fa-check"></i></span>
                                <strong>{{ $activity['title'] }}</strong>
                                <br><small class="text-muted">{{ $activity['description'] }}<br>{{ $activity['time'] }}</small>
                            </li>
                        @endforeach
                        @if(empty($recent_activity))
                            <li class="text-muted">Sin actividad reciente</li>
                        @endif
                    </ul>
                    <a href="#" class="btn btn-outline-success btn-sm mt-3 w-100">Ver Todo</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.getElementById('productsTable');
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

    // Delete product function
    function deleteProduct(productId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/avicontrol/admin/inventory/${productId}`;
        $(modal).modal('show');
    }

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush 