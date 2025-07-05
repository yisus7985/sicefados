@extends('avicontrol::layouts.admin')

@section('title', 'Crear Producto - Inventario')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus text-primary me-2"></i>
                Crear Nuevo Producto
            </h1>
            <p class="text-muted">Registrar un nuevo producto en el inventario</p>
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
                    <form action="{{ route('avicontrol.admin.inventory.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nombre del Producto *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
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
                                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
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
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
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
                                        <option value="kg" {{ old('unit_measure') == 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                                        <option value="g" {{ old('unit_measure') == 'g' ? 'selected' : '' }}>Gramos (g)</option>
                                        <option value="l" {{ old('unit_measure') == 'l' ? 'selected' : '' }}>Litros (l)</option>
                                        <option value="ml" {{ old('unit_measure') == 'ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                                        <option value="unidades" {{ old('unit_measure') == 'unidades' ? 'selected' : '' }}>Unidades</option>
                                        <option value="cajas" {{ old('unit_measure') == 'cajas' ? 'selected' : '' }}>Cajas</option>
                                        <option value="cubetas" {{ old('unit_measure') == 'cubetas' ? 'selected' : '' }}>Cubetas</option>
                                        <option value="dosis" {{ old('unit_measure') == 'dosis' ? 'selected' : '' }}>Dosis</option>
                                        <option value="viales" {{ old('unit_measure') == 'viales' ? 'selected' : '' }}>Viales</option>
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
                                               id="unit_price" name="unit_price" value="{{ old('unit_price') }}" required>
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
                                           id="supplier" name="supplier" value="{{ old('supplier') }}">
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
                                           id="expiration_date" name="expiration_date" value="{{ old('expiration_date') }}">
                                    @error('expiration_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="minimum_stock" class="form-label">Stock Mínimo *</label>
                                    <input type="number" min="0" 
                                           class="form-control @error('minimum_stock') is-invalid @enderror" 
                                           id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock', 0) }}" required>
                                    @error('minimum_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="current_stock" class="form-label">Stock Inicial *</label>
                                    <input type="number" min="0" 
                                           class="form-control @error('current_stock') is-invalid @enderror" 
                                           id="current_stock" name="current_stock" value="{{ old('current_stock', 0) }}" required>
                                    @error('current_stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="has_expiration" 
                                       {{ old('expiration_date') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="has_expiration">
                                    Este producto tiene fecha de vencimiento
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-secondary me-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Crear Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información de Ayuda</h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold">Categorías de Productos:</h6>
                    <ul class="list-unstyled">
                        <li><strong>Alimentos:</strong> Concentrados, suplementos</li>
                        <li><strong>Medicamentos:</strong> Antibióticos, vitaminas</li>
                        <li><strong>Biológicos:</strong> Vacunas, sueros</li>
                        <li><strong>Desinfectantes:</strong> Limpiadores, sanitizantes</li>
                        <li><strong>Embalajes:</strong> Cajas, cubetas, contenedores</li>
                        <li><strong>Equipos:</strong> Herramientas menores</li>
                        <li><strong>Otros:</strong> Insumos diversos</li>
                    </ul>
                    
                    <hr>
                    
                    <h6 class="font-weight-bold">Stock Mínimo:</h6>
                    <p class="text-muted">Cantidad mínima que debe mantenerse en inventario. El sistema generará alertas cuando el stock baje de este nivel.</p>
                    
                    <h6 class="font-weight-bold">Stock Inicial:</h6>
                    <p class="text-muted">Cantidad con la que se inicia el producto en el inventario. Se registrará automáticamente como un movimiento de entrada.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle expiration date field
    document.getElementById('has_expiration').addEventListener('change', function() {
        const expirationField = document.getElementById('expiration_date');
        if (this.checked) {
            expirationField.removeAttribute('disabled');
            expirationField.style.display = 'block';
        } else {
            expirationField.setAttribute('disabled', 'disabled');
            expirationField.style.display = 'none';
            expirationField.value = '';
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const hasExpiration = document.getElementById('has_expiration');
        const expirationField = document.getElementById('expiration_date');
        
        if (!hasExpiration.checked) {
            expirationField.setAttribute('disabled', 'disabled');
            expirationField.style.display = 'none';
        }
    });

    // Auto-calculate total value when price or stock changes
    document.getElementById('unit_price').addEventListener('input', updateTotalValue);
    document.getElementById('current_stock').addEventListener('input', updateTotalValue);

    function updateTotalValue() {
        const price = parseFloat(document.getElementById('unit_price').value) || 0;
        const stock = parseInt(document.getElementById('current_stock').value) || 0;
        const total = price * stock;
        
        // You can display this somewhere if needed
        console.log('Valor total estimado: $' + total.toFixed(2));
    }
</script>
@endpush 