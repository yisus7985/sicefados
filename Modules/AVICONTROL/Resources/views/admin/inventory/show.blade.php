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
            <a href="{{ route('avicontrol.admin.inventory.edit', $product->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <!-- Botón de movimiento removido temporalmente -->
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
                        <!-- Botones de movimientos removidos temporalmente -->
                        <a href="{{ route('avicontrol.admin.inventory.edit', $product->id) }}" 
                           class="btn btn-info btn-sm">
                            <i class="fas fa-edit me-1"></i> Editar Producto
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sección de movimientos removida temporalmente -->
        </div>
    </div>

    <!-- Tabla de movimientos removida temporalmente -->
</div>
@endsection

{{-- Scripts de gráficos removidos temporalmente --}} 