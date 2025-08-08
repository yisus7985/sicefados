@extends('avicontrol::layouts.admin')

@section('title', 'Alertas - AVICONTROL')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-dark">
                        <i class="fas fa-bell text-warning me-2"></i>
                        Sistema de Alertas
                    </h1>
                    <p class="text-muted mb-0">Monitoreo automático de eventos críticos y anomalías</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="refreshAlerts()">
                        <i class="fas fa-sync-alt me-1"></i>
                        Actualizar
                    </button>
                    <button class="btn btn-success btn-mark-all-read" onclick="markAllAsRead()">
                        <i class="fas fa-check-double me-1"></i>
                        Marcar todas como leídas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-exclamation-triangle text-danger fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Total Alertas</h6>
                            <h3 class="mb-0 text-danger" id="total-alerts">{{ count($allAlerts) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-egg text-warning fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Producción</h6>
                            <h3 class="mb-0 text-warning" id="production-alerts">{{ count($productionAlerts) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-boxes text-info fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Inventario</h6>
                            <h3 class="mb-0 text-info" id="inventory-alerts">{{ count($inventoryAlerts) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-skull-crossbones text-danger fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Críticas</h6>
                            <h3 class="mb-0 text-danger" id="critical-alerts">
                                {{ count(array_filter($allAlerts, function($alert) { return $alert['severity'] === 'critical'; })) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="filter-type" class="form-label">Tipo de Alerta</label>
                            <select class="form-select" id="filter-type">
                                <option value="">Todos los tipos</option>
                                <option value="production">Producción</option>
                                <option value="inventory">Inventario</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter-severity" class="form-label">Severidad</label>
                            <select class="form-select" id="filter-severity">
                                <option value="">Todas las severidades</option>
                                <option value="critical">Crítica</option>
                                <option value="high">Alta</option>
                                <option value="medium">Media</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter-category" class="form-label">Categoría</label>
                            <select class="form-select" id="filter-category">
                                <option value="">Todas las categorías</option>
                                <option value="laying_rate">Tasa de Postura</option>
                                <option value="mortality">Mortalidad</option>
                                <option value="feed_consumption">Consumo de Alimento</option>
                                <option value="low_stock">Stock Bajo</option>
                                <option value="expiring_soon">Próximo a Vencer</option>
                                <option value="expired">Vencido</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100" onclick="applyFilters()">
                                <i class="fas fa-filter me-1"></i>
                                Aplicar Filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Alertas -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Alertas Activas
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if(count($allAlerts) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">#</th>
                                        <th width="60">Tipo</th>
                                        <th width="80">Severidad</th>
                                        <th>Título</th>
                                        <th>Mensaje</th>
                                        <th width="150">Fecha</th>
                                        <th width="100">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="alerts-table-body">
                                    @foreach($allAlerts as $index => $alert)
                                        <tr class="alert-row" 
                                            data-alert-type="{{ $alert['type'] }}" 
                                            data-alert-severity="{{ $alert['severity'] }}" 
                                            data-alert-category="{{ $alert['category'] }}">
                                            <td class="align-middle">
                                                <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                            </td>
                                            <td class="align-middle">
                                                @if($alert['type'] === 'production')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-egg me-1"></i>
                                                        Producción
                                                    </span>
                                                @else
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-boxes me-1"></i>
                                                        Inventario
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if($alert['severity'] === 'critical')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Crítica
                                                    </span>
                                                @elseif($alert['severity'] === 'high')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-exclamation me-1"></i>
                                                        Alta
                                                    </span>
                                                @else
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Media
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas {{ $alert['icon'] }} text-{{ $alert['color'] }} me-2"></i>
                                                    <strong>{{ $alert['title'] }}</strong>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-muted">{{ $alert['message'] }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <small class="text-muted">
                                                    {{ $alert['created_at']->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="viewAlertDetails({{ json_encode($alert) }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success btn-mark-read" 
                                                            onclick="markAsRead({{ $index }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                            <h5 class="text-muted">No hay alertas activas</h5>
                            <p class="text-muted">El sistema está funcionando correctamente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles de alerta -->
<div class="modal fade" id="alertDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detalles de la Alerta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="alert-details-content">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="markCurrentAsRead()">
                    <i class="fas fa-check me-1"></i>
                    Marcar como leída
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentAlertIndex = null;

function refreshAlerts() {
    // Mostrar indicador de carga
    const button = document.querySelector('button[onclick="refreshAlerts()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
    button.disabled = true;
    
    // Recargar la página para obtener alertas actualizadas
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function applyFilters() {
    const typeFilter = document.getElementById('filter-type').value;
    const severityFilter = document.getElementById('filter-severity').value;
    const categoryFilter = document.getElementById('filter-category').value;
    
    const rows = document.querySelectorAll('.alert-row');
    
    rows.forEach(row => {
        let show = true;
        
        if (typeFilter && row.dataset.type !== typeFilter) {
            show = false;
        }
        
        if (severityFilter && row.dataset.severity !== severityFilter) {
            show = false;
        }
        
        if (categoryFilter && row.dataset.category !== categoryFilter) {
            show = false;
        }
        
        row.style.display = show ? '' : 'none';
    });
}

function viewAlertDetails(alert) {
    const modal = new bootstrap.Modal(document.getElementById('alertDetailsModal'));
    const content = document.getElementById('alert-details-content');
    
    let detailsHtml = `
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-muted">Información General</h6>
                <ul class="list-unstyled">
                    <li><strong>Tipo:</strong> ${alert.type === 'production' ? 'Producción' : 'Inventario'}</li>
                    <li><strong>Severidad:</strong> ${alert.severity === 'critical' ? 'Crítica' : alert.severity === 'high' ? 'Alta' : 'Media'}</li>
                    <li><strong>Categoría:</strong> ${getCategoryName(alert.category)}</li>
                    <li><strong>Fecha:</strong> ${new Date(alert.created_at).toLocaleString('es-ES')}</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="text-muted">Datos Específicos</h6>
                <ul class="list-unstyled">
    `;
    
    // Agregar datos específicos según el tipo de alerta
    if (alert.type === 'production') {
        if (alert.category === 'laying_rate') {
            detailsHtml += `
                <li><strong>Lote:</strong> ${alert.data.batch_name}</li>
                <li><strong>Tasa Actual:</strong> ${alert.data.current_rate}%</li>
                <li><strong>Tasa Esperada:</strong> ${alert.data.expected_rate}%</li>
            `;
        } else if (alert.category === 'mortality') {
            detailsHtml += `
                <li><strong>Lote:</strong> ${alert.data.batch_name}</li>
                <li><strong>Mortalidad:</strong> ${(alert.data.mortality_rate * 100).toFixed(1)}%</li>
            `;
        } else if (alert.category === 'feed_consumption') {
            detailsHtml += `
                <li><strong>Lote:</strong> ${alert.data.batch_name}</li>
                <li><strong>Consumo:</strong> ${alert.data.feed_consumption}g/ave/día</li>
            `;
        }
    } else {
        if (alert.category === 'low_stock') {
            detailsHtml += `
                <li><strong>Producto:</strong> ${alert.data.product_name}</li>
                <li><strong>Stock Actual:</strong> ${alert.data.current_quantity} unidades</li>
                <li><strong>Stock Mínimo:</strong> ${alert.data.minimum_stock} unidades</li>
            `;
        } else if (alert.category === 'expiring_soon') {
            detailsHtml += `
                <li><strong>Producto:</strong> ${alert.data.product_name}</li>
                <li><strong>Días hasta vencer:</strong> ${alert.data.days_until_expiration}</li>
                <li><strong>Fecha de vencimiento:</strong> ${new Date(alert.data.expiration_date).toLocaleDateString('es-ES')}</li>
            `;
        } else if (alert.category === 'expired') {
            detailsHtml += `
                <li><strong>Producto:</strong> ${alert.data.product_name}</li>
                <li><strong>Fecha de vencimiento:</strong> ${new Date(alert.data.expiration_date).toLocaleDateString('es-ES')}</li>
            `;
        }
    }
    
    detailsHtml += `
                </ul>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-muted">Mensaje</h6>
                <div class="alert alert-${alert.color}">
                    <i class="fas ${alert.icon} me-2"></i>
                    ${alert.message}
                </div>
            </div>
        </div>
    `;
    
    content.innerHTML = detailsHtml;
    modal.show();
}

function getCategoryName(category) {
    const categories = {
        'laying_rate': 'Tasa de Postura',
        'mortality': 'Mortalidad',
        'feed_consumption': 'Consumo de Alimento',
        'low_stock': 'Stock Bajo',
        'expiring_soon': 'Próximo a Vencer',
        'expired': 'Vencido'
    };
    return categories[category] || category;
}

function markAsRead(index) {
    const row = document.querySelectorAll('.alert-row')[index];
    if (row) {
        // Mostrar indicador de carga
        const button = row.querySelector('.btn-mark-read');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marcando...';
        button.disabled = true;
        
        // Hacer llamada AJAX al servidor
        fetch('{{ route("avicontrol.admin.alerts.mark_read") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                alert_index: index,
                alert_type: row.dataset.alertType,
                alert_category: row.dataset.alertCategory
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Aplicar cambios visuales
                row.style.opacity = '0.5';
                row.style.backgroundColor = '#f8f9fa';
                row.classList.add('read');
                
                // Actualizar contador de alertas
                updateAlertCount();
                
                showNotification('Alerta marcada como leída', 'success');
            } else {
                showNotification('Error al marcar como leída', 'danger');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al marcar como leída', 'danger');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

function markCurrentAsRead() {
    if (currentAlertIndex !== null) {
        markAsRead(currentAlertIndex);
        bootstrap.Modal.getInstance(document.getElementById('alertDetailsModal')).hide();
    }
}

function markAllAsRead() {
    const rows = document.querySelectorAll('.alert-row:not(.read)');
    if (rows.length === 0) {
        showNotification('No hay alertas nuevas para marcar como leídas', 'info');
        return;
    }
    
    // Mostrar indicador de carga
    const button = document.querySelector('.btn-mark-all-read');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marcando...';
    button.disabled = true;
    
    // Hacer llamada AJAX para marcar todas como leídas
    fetch('{{ route("avicontrol.admin.alerts.mark_read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            mark_all: true
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aplicar cambios visuales a todas las alertas
            rows.forEach(row => {
                row.style.opacity = '0.5';
                row.style.backgroundColor = '#f8f9fa';
                row.classList.add('read');
            });
            
            // Actualizar contador de alertas
            updateAlertCount();
            
            showNotification('Todas las alertas marcadas como leídas', 'success');
        } else {
            showNotification('Error al marcar alertas como leídas', 'danger');
        }
        button.innerHTML = originalText;
        button.disabled = false;
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al marcar alertas como leídas', 'danger');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function updateAlertCount() {
    const unreadAlerts = document.querySelectorAll('.alert-row:not(.read)').length;
    const countElement = document.querySelector('.alert-count');
    if (countElement) {
        countElement.textContent = unreadAlerts;
    }
}

function showNotification(message, type) {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.appendChild(toast);
    document.body.appendChild(container);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        container.remove();
    });
}

// Auto-refresh cada 5 minutos
setInterval(refreshAlerts, 300000);
</script>
@endpush 