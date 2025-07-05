@extends('avicontrol::layouts.admin')

@section('title', 'Registrar Movimiento - ' . $product->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-exchange-alt text-primary me-2"></i>
                Registrar Movimiento
            </h1>
            <p class="text-muted">Registrar entrada o salida de stock para: {{ $product->name }}</p>
        </div>
        <div>
            <a href="{{ route('avicontrol.admin.inventory.show', $product->id) }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Volver al Producto
            </a>
            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-1"></i> Ver Inventario
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Nuevo Movimiento</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('avicontrol.admin.inventory.movements.store', $product->id) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="movement_type" class="form-label">Tipo de Movimiento *</label>
                                    <select class="form-control @error('movement_type') is-invalid @enderror" 
                                            id="movement_type" name="movement_type" required>
                                        <option value="">Seleccionar tipo</option>
                                        @foreach($movementTypes as $key => $type)
                                            <option value="{{ $key }}" {{ old('movement_type') == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('movement_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference" class="form-label">Referencia *</label>
                                    <select class="form-control @error('reference') is-invalid @enderror" 
                                            id="reference" name="reference" required>
                                        <option value="">Seleccionar referencia</option>
                                        @foreach($references as $key => $reference)
                                            <option value="{{ $key }}" {{ old('reference') == $key ? 'selected' : '' }}>
                                                {{ $reference }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="quantity" class="form-label">Cantidad *</label>
                                    <div class="input-group">
                                        <input type="number" min="1" 
                                               class="form-control @error('quantity') is-invalid @enderror" 
                                               id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                                        <span class="input-group-text">{{ $product->unit_measure }}</span>
                                    </div>
                                    @error('quantity')
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
                                               id="unit_price" name="unit_price" 
                                               value="{{ old('unit_price', $product->unit_price) }}" required>
                                    </div>
                                    @error('unit_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="movement_date" class="form-label">Fecha del Movimiento *</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('movement_date') is-invalid @enderror" 
                                           id="movement_date" name="movement_date" 
                                           value="{{ old('movement_date', now()->format('Y-m-d\TH:i')) }}" required>
                                    @error('movement_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes" class="form-label">Notas</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Descripción adicional del movimiento...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('avicontrol.admin.inventory.show', $product->id) }}" class="btn btn-secondary me-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Registrar Movimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Product Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información del Producto</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Nombre:</strong></td>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Código:</strong></td>
                            <td><span class="badge badge-primary">{{ $product->code }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Categoría:</strong></td>
                            <td><span class="badge badge-info">{{ $product->category_name }}</span></td>
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
                            <td><strong>Precio Actual:</strong></td>
                            <td>${{ number_format($product->unit_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Valor Total:</strong></td>
                            <td><span class="font-weight-bold text-success">${{ number_format($product->total_value, 2) }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>

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
                </div>
            </div>

            <!-- Movement Preview -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Vista Previa</h6>
                </div>
                <div class="card-body">
                    <div id="movementPreview">
                        <p class="text-muted">Complete el formulario para ver la vista previa del movimiento.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update movement preview
    function updatePreview() {
        const movementType = document.getElementById('movement_type').value;
        const reference = document.getElementById('reference').value;
        const quantity = document.getElementById('quantity').value;
        const unitPrice = document.getElementById('unit_price').value;
        const totalValue = quantity * unitPrice;
        
        let previewHtml = '';
        
        if (movementType && reference && quantity && unitPrice) {
            const typeText = movementType === 'entry' ? 'Entrada' : (movementType === 'exit' ? 'Salida' : 'Ajuste');
            const typeClass = movementType === 'entry' ? 'success' : (movementType === 'exit' ? 'danger' : 'info');
            
            previewHtml = `
                <div class="alert alert-${typeClass}">
                    <h6><i class="fas fa-exchange-alt me-2"></i>${typeText}</h6>
                    <p><strong>Referencia:</strong> ${reference}</p>
                    <p><strong>Cantidad:</strong> ${quantity} {{ $product->unit_measure }}</p>
                    <p><strong>Precio Unitario:</strong> $${parseFloat(unitPrice).toFixed(2)}</p>
                    <p><strong>Valor Total:</strong> $${totalValue.toFixed(2)}</p>
                </div>
            `;
        } else {
            previewHtml = '<p class="text-muted">Complete el formulario para ver la vista previa del movimiento.</p>';
        }
        
        document.getElementById('movementPreview').innerHTML = previewHtml;
    }

    // Add event listeners
    document.getElementById('movement_type').addEventListener('change', updatePreview);
    document.getElementById('reference').addEventListener('change', updatePreview);
    document.getElementById('quantity').addEventListener('input', updatePreview);
    document.getElementById('unit_price').addEventListener('input', updatePreview);

    // Validate exit movements
    document.getElementById('quantity').addEventListener('input', function() {
        const quantity = parseInt(this.value) || 0;
        const currentStock = {{ $product->current_stock }};
        const movementType = document.getElementById('movement_type').value;
        
        if (movementType === 'exit' && quantity > currentStock) {
            this.setCustomValidity(`No hay suficiente stock disponible. Stock actual: ${currentStock} {{ $product->unit_measure }}`);
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });

    // Auto-fill reference based on movement type
    document.getElementById('movement_type').addEventListener('change', function() {
        const movementType = this.value;
        const referenceSelect = document.getElementById('reference');
        
        // Reset reference
        referenceSelect.value = '';
        
        // Auto-select common references based on movement type
        if (movementType === 'entry') {
            referenceSelect.value = 'purchase';
        } else if (movementType === 'exit') {
            referenceSelect.value = 'consumption';
        }
        
        updatePreview();
    });

    // Initialize preview on page load
    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
    });
</script>
@endpush 