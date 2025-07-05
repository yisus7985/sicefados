@extends('avicontrol::layouts.admin')

@section('title', 'Detalles del Producto - ' . $product->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-box text-primary me-2"></i>
                {{ $product->name }}
            </h1>
            <p class="text-muted">Detalles del producto en inventario</p>
        </div>
        <div>
            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Volver al Inventario
            </a>
            <a href="{{ route('avicontrol.admin.inventory.edit', $product->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <a href="{{ route('avicontrol.admin.inventory.movements.create', $product->id) }}" class="btn btn-primary">
                <i class="fas fa-exchange-alt me-1"></i> Registrar Movimiento
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Product Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Producto</h6>
                    <span class="badge badge-primary">{{ $product->code }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nombre:</strong></td>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Categoría:</strong></td>
                                    <td><span class="badge badge-info">{{ $product->category_name }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Descripción:</strong></td>
                                    <td>{{ $product->description ?: 'Sin descripción' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Proveedor:</strong></td>
                                    <td>{{ $product->supplier ?: 'No especificado' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Unidad de Medida:</strong></td>
                                    <td>{{ $product->unit_measure }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Precio Unitario:</strong></td>
                                    <td>${{ number_format($product->unit_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Stock Actual:</strong></td>
                                    <td>
                                        <span class="font-weight-bold">{{ $product->current_stock }} {{ $product->unit_measure }}</span>
                                        @if($product->isLowStock())
                                            <i class="fas fa-exclamation-triangle text-warning ml-2" title="Stock bajo"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Stock Mínimo:</strong></td>
                                    <td>{{ $product->minimum_stock }} {{ $product->unit_measure }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Valor Total:</strong></td>
                                    <td><span class="font-weight-bold text-success">${{ number_format($product->total_value, 2) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td>
                                        @if($product->status === 'active')
                                            <span class="badge badge-success">Activo</span>
                                        @elseif($product->status === 'inactive')
                                            <span class="badge badge-secondary">Inactivo</span>
                                        @else
                                            <span class="badge badge-danger">Vencido</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($product->expiration_date)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert {{ $product->isExpired() ? 'alert-danger' : ($product->isExpiringSoon() ? 'alert-warning' : 'alert-info') }}">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Fecha de Vencimiento:</strong> {{ $product->expiration_date->format('d/m/Y') }}
                                @if($product->isExpired())
                                    <span class="badge badge-danger ml-2">VENCIDO</span>
                                @elseif($product->isExpiringSoon())
                                    <span class="badge badge-warning ml-2">Por Vencer</span>
                                @else
                                    <span class="badge badge-info ml-2">Vigente</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stock Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Historial de Stock</h6>
                </div>
                <div class="card-body">
                    <canvas id="stockChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Stock Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Estado del Stock</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Stock Actual</span>
                            <span>{{ $product->current_stock }} / {{ $product->minimum_stock }}</span>
                        </div>
                        <div class="progress">
                            @php
                                $percentage = $product->minimum_stock > 0 ? min(100, ($product->current_stock / $product->minimum_stock) * 100) : 100;
                                $color = $percentage < 50 ? 'danger' : ($percentage < 100 ? 'warning' : 'success');
                            @endphp
                            <div class="progress-bar bg-{{ $color }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    @if($product->isLowStock())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Stock Bajo:</strong> El nivel de stock está por debajo del mínimo recomendado.
                        </div>
                    @endif

                    @if($product->isExpiringSoon())
                        <div class="alert alert-danger">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Por Vencer:</strong> Este producto vence pronto.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('avicontrol.admin.inventory.movements.create', $product->id) }}" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Entrada de Stock
                        </a>
                        <a href="{{ route('avicontrol.admin.inventory.movements.create', $product->id) }}?type=exit" 
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-minus me-1"></i> Salida de Stock
                        </a>
                        <a href="{{ route('avicontrol.admin.inventory.edit', $product->id) }}" 
                           class="btn btn-info btn-sm">
                            <i class="fas fa-edit me-1"></i> Editar Producto
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Movements -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Movimientos Recientes</h6>
                </div>
                <div class="card-body">
                    @forelse($product->movements->take(5) as $movement)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="text-muted">{{ $movement->movement_date->format('d/m/Y H:i') }}</small>
                                <br>
                                <span class="badge badge-{{ $movement->isEntry() ? 'success' : ($movement->isExit() ? 'danger' : 'info') }}">
                                    {{ $movement->movement_type_name }}
                                </span>
                                <small>{{ $movement->quantity }} {{ $product->unit_measure }}</small>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">${{ number_format($movement->total_value, 2) }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No hay movimientos registrados</p>
                    @endforelse
                    
                    @if($product->movements->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('avicontrol.admin.inventory.movements.index') }}?product_id={{ $product->id }}" 
                               class="btn btn-outline-primary btn-sm">
                                Ver Todos los Movimientos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Historial de Movimientos</h6>
            <a href="{{ route('avicontrol.admin.inventory.movements.create', $product->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nuevo Movimiento
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fecha</th>
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
                            <td>{{ $movement->movement_date->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge badge-{{ $movement->isEntry() ? 'success' : ($movement->isExit() ? 'danger' : 'info') }}">
                                    {{ $movement->movement_type_name }}
                                </span>
                            </td>
                            <td>{{ $movement->reference_name }}</td>
                            <td>{{ $movement->quantity }} {{ $product->unit_measure }}</td>
                            <td>${{ number_format($movement->unit_price, 2) }}</td>
                            <td>${{ number_format($movement->total_value, 2) }}</td>
                            <td>{{ $movement->user->name ?? 'N/A' }}</td>
                            <td>{{ $movement->notes ?: '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay movimientos registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Stock Chart
    const ctx = document.getElementById('stockChart').getContext('2d');
    const stockChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($product->movements->pluck('movement_date')->map(function($date) { return $date->format('d/m'); })->reverse()) !!},
            datasets: [{
                label: 'Stock',
                data: {!! json_encode($product->movements->pluck('quantity')->reverse()) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush 