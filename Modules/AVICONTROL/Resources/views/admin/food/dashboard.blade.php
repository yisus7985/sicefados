@extends('avicontrol::layouts.admin')

@section('title', 'Panel de Control - Módulo de Alimentación')

@section('content')
<div class="container-fluid">
    <!-- Header del Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-utensils text-secondary me-2"></i>
                        Panel de Control - Alimentación
                    </h1>
                    <p class="text-muted">Resumen general del módulo de alimentación</p>
                </div>
                <div>
                    <a href="{{ route('avicontrol.admin.food_consumption.create') }}" class="btn btn-custom me-2">
                        <i class="fas fa-plus me-1"></i> Nuevo Consumo
                    </a>
                    <a href="{{ route('avicontrol.admin.food_waste.create') }}" class="btn btn-custom-outline me-2">
                        <i class="fas fa-exclamation-triangle me-1"></i> Registrar Merma
                    </a>
                    <a href="{{ route('avicontrol.admin.food_conversion.create') }}" class="btn btn-custom-outline">
                        <i class="fas fa-calculator me-1"></i> Nueva Conversión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if(count($alertas) > 0)
    <div class="row mb-4">
        <div class="col-12">
            @foreach($alertas as $alerta)
            <div class="alert alert-{{ $alerta['tipo'] }} alert-dismissible fade show" role="alert">
                <i class="{{ $alerta['icono'] }} me-2"></i>
                {{ $alerta['mensaje'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tarjetas de Estadísticas Principales -->
    <div class="row mb-4">
        <!-- Consumo Total -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="module-icon primary mb-3">
                        <i class="fas fa-weight"></i>
                    </div>
                    <h3 class="module-title">{{ number_format($totalConsumo, 0) }}</h3>
                    <p class="module-description">Consumo Total (kg)</p>
                </div>
            </div>
        </div>

        <!-- Promedio Diario -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="module-icon success mb-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="module-title">{{ number_format($promedioConsumoDiario, 1) }}</h3>
                    <p class="module-description">Promedio Diario (kg)</p>
                </div>
            </div>
        </div>

        <!-- Conversión Promedio -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="module-icon info mb-3">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h3 class="module-title">{{ number_format($promedioConversion, 2) }}</h3>
                    <p class="module-description">Conversión Promedio</p>
                </div>
            </div>
        </div>

        <!-- Mermas Totales -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="module-icon warning mb-3">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="module-title">{{ number_format($totalMermas, 0) }}</h3>
                    <p class="module-description">Mermas Totales (kg)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Estadísticas Detalladas -->
    <div class="row mb-4">
        <!-- Mermas por Causa -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Mermas por Causa</h6>
                </div>
                <div class="card-body">
                    @if($mermasPorCausa->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Causa</th>
                                    <th>Cantidad (kg)</th>
                                    <th>Registros</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mermasPorCausa as $merma)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $merma->causa_merma)) }}</td>
                                    <td>{{ number_format($merma->cantidad_total, 1) }}</td>
                                    <td>{{ $merma->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center">No hay datos de mermas disponibles</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Consumo por Galpón -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Consumo por Galpón</h6>
                </div>
                <div class="card-body">
                    @if($consumoPorGalpon->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Galpón</th>
                                    <th>Consumo Total (kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consumoPorGalpon as $consumo)
                                <tr>
                                    <td>{{ $consumo->galpon->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($consumo->total_consumo, 1) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center">No hay datos de consumo disponibles</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos Registros -->
    <div class="row mb-4">
        <!-- Últimos Consumos -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Últimos Consumos</h6>
                    <a href="{{ route('avicontrol.admin.food_consumption.index') }}" class="btn btn-custom btn-sm">
                        Ver Todos
                    </a>
                </div>
                <div class="card-body">
                    @if($ultimosConsumos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Galpón</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimosConsumos as $consumo)
                                <tr>
                                    <td>{{ $consumo->fecha_registro->format('d/m/Y') }}</td>
                                    <td>{{ $consumo->galpon->name ?? 'N/A' }}</td>
                                    <td>{{ $consumo->producto->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($consumo->getTotalConsumoAttribute(), 1) }} kg</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center">No hay registros de consumo recientes</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Últimas Mermas -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Últimas Mermas</h6>
                    <a href="{{ route('avicontrol.admin.food_waste.index') }}" class="btn btn-custom btn-sm">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    @if($ultimasMermas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Galpón</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Causa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasMermas as $merma)
                                <tr>
                                    <td>{{ $merma->fecha_registro->format('d/m/Y') }}</td>
                                    <td>{{ $merma->galpon->name ?? 'N/A' }}</td>
                                    <td>{{ $merma->producto->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($merma->cantidad_perdida, 1) }} kg</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $merma->causa_merma)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center">No hay registros de mermas recientes</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Adicionales -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Estadísticas Adicionales</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ number_format($consumoEsteMes, 0) }}</h4>
                                <p class="text-muted">Consumo Este Mes (kg)</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ number_format($costoTotalMermas, 2) }}</h4>
                                <p class="text-muted">Costo Total Mermas ($)</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ number_format($porcentajeMermas, 2) }}%</h4>
                                <p class="text-muted">Porcentaje de Mermas</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $conversionesEsteMes }}</h4>
                                <p class="text-muted">Conversiones Este Mes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlaces Rápidos -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('avicontrol.admin.food_consumption.index') }}" class="btn btn-custom w-100">
                                <i class="fas fa-list me-2"></i>
                                Ver Consumos
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('avicontrol.admin.food_conversion.index') }}" class="btn btn-custom w-100">
                                <i class="fas fa-calculator me-2"></i>
                                Ver Conversiones
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('avicontrol.admin.food_waste.index') }}" class="btn btn-custom w-100">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Ver Mermas
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('avicontrol.admin.food_consumption.estadisticas') }}" class="btn btn-custom w-100">
                                <i class="fas fa-chart-bar me-2"></i>
                                Estadísticas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .module-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 2rem;
    }
    
    .module-icon.primary {
        background-color: rgba(77, 124, 15, 0.1);
        color: var(--primary);
    }
    
    .module-icon.success {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }
    
    .module-icon.warning {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }
    
    .module-icon.info {
        background-color: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }
    
    .module-title {
        font-size: 1.5rem;
        margin: 0 0 10px;
        font-weight: 700;
    }
    
    .module-description {
        font-size: 0.9rem;
        color: var(--dark);
        opacity: 0.7;
        margin-bottom: 15px;
    }
    
    .btn-custom {
        background-color: var(--primary);
        color: var(--light);
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-custom:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        color: var(--light);
    }
    
    .btn-custom-outline {
        background-color: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-custom-outline:hover {
        background-color: var(--primary);
        color: var(--light);
        transform: translateY(-2px);
    }
    
    :root {
        --primary: #4d7c0f;
        --primary-dark: #3f6a0a;
        --secondary: #b45309;
        --tertiary: #f59e0b;
        --tertiary-dark: #d88e09;
        --light: #f8fafc;
        --dark: #334155;
        --accent: #f97316;
        --background: #f1f5f9;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #3b82f6;
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh de alertas cada 5 minutos
    setInterval(function() {
        location.reload();
    }, 300000);
});
</script>
@endsection
