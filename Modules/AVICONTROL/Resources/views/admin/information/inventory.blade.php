@extends('avicontrol::layouts.admin')

@section('title', 'Informe de Inventario - AVICONTROL')

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
    
    <div class="card shadow-sm mb-2">
    <div class="card-body p-2 d-flex flex-wrap justify-content-between align-items-center">
        <div class="mb-1 mb-md-0">
            <h1 class="h5 mb-0 text-gray-800">
                <i class="fas fa-boxes text-primary me-1"></i>
                Informe de Inventario
            </h1>
            <p class="text-muted small mb-0">Consulta de productos e insumos avícolas</p>
        </div>
        <div>
            <a href="{{ route('avicontrol.admin.information.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Columna Principal -->
    <div class="col-lg-9">
        <!-- Fila de Estadísticas -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-primary shadow-sm h-100">
                    <div class="card-body p-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Productos</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $stats['total_products'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-lg text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-warning shadow-sm h-100">
                    <div class="card-body p-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stock Bajo</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $stats['low_stock_products'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-lg text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-danger shadow-sm h-100">
                    <div class="card-body p-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Por Vencer</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $stats['expiring_products'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-lg text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-2">
                <div class="card border-left-success shadow-sm h-100">
                    <div class="card-body p-2">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Valor Total</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_value'], 2) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-lg text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Productos -->
        <div class="card shadow-sm">
            <div class="card-header py-2">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary mb-2 mb-md-0">Listado de Productos</h6>
                    <div class="d-flex flex-wrap align-items-center">
                        <input type="text" id="searchInput" class="form-control form-control-sm me-2 mb-2 mb-md-0" placeholder="Buscar..." style="max-width: 180px;">
                        <a href="{{ route('avicontrol.admin.information.pdf.all') }}" class="btn btn-danger btn-sm me-2 mb-2 mb-md-0" data-bs-toggle="tooltip" title="Informe Completo">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <button class="btn btn-info btn-sm mb-2 mb-md-0" type="button" data-bs-toggle="collapse" data-bs-target="#dateFilterCollapse" aria-expanded="false" aria-controls="dateFilterCollapse" data-bs-toggle="tooltip" title="Filtrar por Fecha">
                            <i class="fas fa-calendar-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="collapse mt-2" id="dateFilterCollapse">
                    <form action="{{ route('avicontrol.admin.information.pdf.by_date') }}" method="GET" target="_blank">
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-2">
                                <label for="start_date" class="form-label form-label-sm">Desde:</label>
                                <input type="date" id="start_date" name="start_date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="end_date" class="form-label form-label-sm">Hasta:</label>
                                <input type="date" id="end_date" name="end_date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4 d-flex align-items-end mb-2">
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="fas fa-download me-1"></i> Descargar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="productsTable" width="100%" cellspacing="0">
                        <thead class="table-success">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Stock Mín.</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th class="text-center">PDF</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td><span class="badge bg-primary">{{ $product->code }}</span></td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->description)
                                        <br><small class="text-muted">{{ Str::limit($product->description, 40) }}</small>
                                    @endif
                                </td>
                                <td><span class="badge bg-info">{{ $product->category_name }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold">{{ $product->current_stock }} {{ $product->unit_measure }}</span>
                                        @if($product->isLowStock())
                                            <i class="fas fa-exclamation-triangle text-warning ms-2" data-bs-toggle="tooltip" title="Stock bajo"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $product->minimum_stock }} {{ $product->unit_measure }}</td>
                                <td>${{ number_format($product->unit_price, 2) }}</td>
                                <td>
                                    @if($product->status === 'active')
                                        <span class="badge bg-success">Activo</span>
                                    @elseif($product->status === 'inactive')
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @else
                                        <span class="badge bg-danger">Vencido</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('avicontrol.admin.information.pdf.product', ['id' => $product->id]) }}" class="btn btn-sm btn-danger" target="_blank" data-bs-toggle="tooltip" title="Descargar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No hay productos para mostrar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="d-flex justify-content-center mt-2">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Columna Lateral -->
    <div class="col-lg-3">
        <div class="card shadow-sm mb-2">
            <div class="card-header py-2 bg-success text-white">
                <h6 class="m-0 fw-bold"><i class="fas fa-chart-bar me-2"></i>Resumen</h6>
            </div>
            <div class="card-body p-2">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-1 d-flex justify-content-between"><span><i class="fas fa-boxes text-muted me-2"></i>Productos</span> <strong>{{ $stats['total_products'] }}</strong></li>
                    <li class="mb-1 d-flex justify-content-between"><span><i class="fas fa-exclamation-triangle text-muted me-2"></i>Stock Bajo</span> <strong>{{ $stats['low_stock_products'] }}</strong></li>
                    <li class="mb-1 d-flex justify-content-between"><span><i class="fas fa-clock text-muted me-2"></i>Por Vencer</span> <strong>{{ $stats['expiring_products'] }}</strong></li>
                    <li class="d-flex justify-content-between"><span><i class="fas fa-dollar-sign text-muted me-2"></i>Valor Total</span> <strong>${{ number_format($stats['total_value'], 2) }}</strong></li>
                </ul>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header py-2 bg-light">
                <h6 class="m-0 fw-bold text-primary"><i class="fas fa-history me-2"></i>Actividad Reciente</h6>
            </div>
            <div class="card-body p-2">
                <ul class="list-unstyled mb-0 small">
                    @forelse($recent_activity as $activity)
                        <li class="mb-2">
                            <strong class="d-block">{{ $activity['title'] }}</strong>
                            <small class="text-muted">{{ $activity['description'] }} - {{ $activity['time'] }}</small>
                        </li>
                    @empty
                        <li class="text-muted">Sin actividad reciente.</li>
                    @endforelse
                </ul>
                <a href="#" class="btn btn-outline-primary btn-sm mt-2 w-100">Ver Todo</a>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
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
        }

        // Auto-hide alerts
        const alert = document.querySelector('.alert');
        if(alert) {
            setTimeout(function() {
                alert.style.display = 'none';
            }, 5000);
        }
    });
</script>
@endsection