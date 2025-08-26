@extends('avicontrol::layouts.admin')

@section('title', 'Editar Producto - Inventario')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit text-primary me-2"></i>
                Editar Producto: {{ $product->name }}
            </h1>
            <p class="text-muted">Modificar información del producto en el inventario</p>
        </div>
        <div>
            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver al Inventario
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Producto</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('avicontrol.admin.inventory.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nombre del Producto *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category" class="form-label">Categoría *</label>
                                    <select class="form-control @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">Seleccionar categoría</option>
                                        @foreach($categories as $key => $category)
                                            <option value="{{ $key }}" {{ old('category', $product->category) == $key ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="unit_measure" class="form-label">Unidad de Medida *</label>
                                    <select class="form-control @error('unit_measure') is-invalid @enderror" 
                                            id="unit_measure" name="unit_measure" required>
                                        <option value="">Seleccionar unidad</option>
                                        <option value="kg" {{ old('unit_measure', $product->unit_measure) == 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                                        <option value="g" {{ old('unit_measure', $product->unit_measure) == 'g' ? 'selected' : '' }}>Gramos (g)</option>
                                        <option value="l" {{ old('unit_measure', $product->unit_measure) == 'l' ? 'selected' : '' }}>Litros (l)</option>
                                        <option value="ml" {{ old('unit_measure', $product->unit_measure) == 'ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                                        <option value="unidades" {{ old('unit_measure', $product->unit_measure) == 'unidades' ? 'selected' : '' }}>Unidades</option>
                                        <option value="cajas" {{ old('unit_measure', $product->unit_measure) == 'cajas' ? 'selected' : '' }}>Cajas</option>
                                        <option value="cubetas" {{ old('unit_measure', $product->unit_measure) == 'cubetas' ? 'selected' : '' }}>Cubetas</option>
                                        <option value="dosis" {{ old('unit_measure', $product->unit_measure) == 'dosis' ? 'selected' : '' }}>Dosis</option>
                                        <option value="viales" {{ old('unit_measure', $product->unit_measure) == 'viales' ? 'selected' : '' }}>Viales</option>
                                    </select>
                                    @error('unit_measure')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="unit_price" class="form-label">Precio Unitario *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control @error('unit_price') is-invalid @enderror" 
                                               id="unit_price" name="unit_price" value="{{ old('unit_price', $product->unit_price) }}" required>
                                    </div>
                                    @error('unit_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="supplier" class="form-label">Proveedor</label>
                                    <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                                           id="supplier" name="supplier" value="{{ old('supplier', $product->supplier) }}">
                                    @error('supplier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="expiration_date" class="form-label">Fecha de Vencimiento</label>
                                    <input type="date" class="form-control @error('expiration_date') is-invalid @enderror" 
                                           id="expiration_date" name="expiration_date" 
                                           value="{{ old('expiration_date', $product->expiration_date ? $product->expiration_date->format('Y-m-d') : '') }}">
                                    @error('expiration_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Stock Actual</label>
                                    <input type="text" class="form-control" value="{{ $product->current_stock }} {{ $product->unit_measure }}" readonly>
                                    <small class="text-muted">El stock se modifica a través de movimientos</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="minimum_stock" class="form-label">Stock Mínimo *</label>
                                    <input type="number" min="0" 
                                           class="form-control @error('minimum_stock') is-invalid @enderror" 
                                           id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', $product->minimum_stock) }}" required>
                                    @error('minimum_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">Estado *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Activo</option>
                                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                        <option value="expired" {{ old('status', $product->status) == 'expired' ? 'selected' : '' }}>Vencido</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="form-label">Código del Producto</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $product->code) }}">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-secondary me-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Actualizar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Producto</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Stock Actual:</small>
                            <h5 class="mb-0">{{ $product->current_stock }} {{ $product->unit_measure }}</h5>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Valor Total:</small>
                            <h5 class="mb-0 text-success">${{ number_format($product->current_stock * $product->unit_price, 2) }}</h5>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">Stock Mínimo:</small>
                            <h6 class="mb-0">{{ $product->minimum_stock }} {{ $product->unit_measure }}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Estado:</small>
                            <h6 class="mb-0">
                                @if($product->status == 'active')
                                    <span class="badge badge-success">Activo</span>
                                @elseif($product->status == 'inactive')
                                    <span class="badge badge-secondary">Inactivo</span>
                                @else
                                    <span class="badge badge-danger">Vencido</span>
                                @endif
                            </h6>
                        </div>
                    </div>

                    @if($product->expiration_date)
                        <div class="mb-3">
                            <small class="text-muted">Fecha de Vencimiento:</small>
                            <h6 class="mb-0">
                                @if($product->isExpired())
                                    <span class="text-danger">{{ $product->expiration_date->format('d/m/Y') }} (Vencido)</span>
                                @elseif($product->isExpiringSoon())
                                    <span class="text-warning">{{ $product->expiration_date->format('d/m/Y') }} (Por vencer)</span>
                                @else
                                    <span class="text-success">{{ $product->expiration_date->format('d/m/Y') }}</span>
                                @endif
                            </h6>
                        </div>
                    @endif

                    @if($product->isLowStock())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Stock Bajo:</strong> El producto está por debajo del stock mínimo.
                        </div>
                    @endif

                    <hr>
                    
                    <h6 class="font-weight-bold">Acciones Rápidas:</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('avicontrol.admin.inventory.movements.create', $product->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Registrar Movimiento
                        </a>
                        <a href="{{ route('avicontrol.admin.inventory.show', $product->id) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye me-1"></i> Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-calculate total value when price changes
    document.getElementById('unit_price').addEventListener('input', updateTotalValue);

    function updateTotalValue() {
        const price = parseFloat(document.getElementById('unit_price').value) || 0;
        const stock = {{ $product->current_stock }};
        const total = price * stock;
        
        console.log('Valor total estimado: $' + total.toFixed(2));
    }
</script>
@endpush