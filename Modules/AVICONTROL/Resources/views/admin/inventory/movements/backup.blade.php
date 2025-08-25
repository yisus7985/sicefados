<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimientos de Inventario - AVICONTROL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .header-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .stats-card { border-left: 4px solid #007bff; }
        .stats-card.success { border-left-color: #28a745; }
        .stats-card.danger { border-left-color: #dc3545; }
        .stats-card.warning { border-left-color: #ffc107; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="card header-card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            <i class="fas fa-exchange-alt me-2"></i>
                            Movimientos de Inventario
                        </h1>
                        <p class="mb-0 opacity-75">Sistema de gestión avícola AVICONTROL</p>
                    </div>
                    <div>
                        <a href="/avicontrol/admin/inventory" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Volver al Inventario
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($error))
        <!-- Error Message -->
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Advertencia:</strong> {{ $error }}
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card success h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Movimientos
                                </div>
                                <div class="h4 mb-0 font-weight-bold">{{ $stats['total_movements'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Entradas
                                </div>
                                <div class="h4 mb-0 font-weight-bold">{{ $stats['entries'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card danger h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Salidas
                                </div>
                                <div class="h4 mb-0 font-weight-bold">{{ $stats['exits'] ?? 0 }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card warning h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Valor Total
                                </div>
                                <div class="h4 mb-0 font-weight-bold">${{ number_format($stats['total_value'] ?? 0, 2) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movements Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Lista de Movimientos
                </h5>
            </div>
            <div class="card-body">
                @if(isset($movements) && $movements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar me-1"></i>Fecha</th>
                                <th><i class="fas fa-box me-1"></i>Producto</th>
                                <th><i class="fas fa-tag me-1"></i>Tipo</th>
                                <th><i class="fas fa-sort-numeric-up me-1"></i>Cantidad</th>
                                <th><i class="fas fa-dollar-sign me-1"></i>Valor</th>
                                <th><i class="fas fa-user me-1"></i>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                            <tr>
                                <td>
                                    <strong>{{ $movement->movement_date->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ $movement->movement_date->format('H:i') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $movement->product->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $movement->product->code ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($movement->movement_type === 'entry')
                                        <span class="badge bg-success">Entrada</span>
                                    @elseif($movement->movement_type === 'exit')
                                        <span class="badge bg-danger">Salida</span>
                                    @else
                                        <span class="badge bg-info">Ajuste</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ number_format($movement->quantity) }}</strong>
                                    <small class="text-muted">{{ $movement->product->unit_measure ?? '' }}</small>
                                </td>
                                <td>
                                    <strong>${{ number_format($movement->total_value ?? 0, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $movement->user->name ?? 'Sistema' }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($movements instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="d-flex justify-content-center mt-3">
                        {{ $movements->links() }}
                    </div>
                @endif
                @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay movimientos registrados</h5>
                    <p class="text-muted">Los movimientos de inventario aparecerán aquí cuando se registren.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Success Message -->
        <div class="alert alert-success mt-4">
            <i class="fas fa-check-circle me-2"></i>
            <strong>¡Página Funcionando!</strong> El módulo de movimientos está operativo.
            <br><small>Timestamp: {{ now()->format('d/m/Y H:i:s') }}</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
