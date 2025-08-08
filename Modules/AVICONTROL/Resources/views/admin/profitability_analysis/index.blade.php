@extends('avicontrol::layouts.admin')

@section('title', 'Análisis de Rentabilidad')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i> Análisis de Rentabilidad
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('avicontrol.admin.profitability_analysis.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Análisis
                        </a>
                        <a href="{{ route('avicontrol.admin.profitability_analysis.report') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar"></i> Generar Reporte
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('avicontrol.admin.profitability_analysis.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="facility_id">Galpón</label>
                                    <select name="facility_id" id="facility_id" class="form-control">
                                        <option value="">Todos los galpones</option>
                                        @foreach($poultryFacilities as $facility)
                                            <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                                {{ $facility->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="analysis_period">Período de Análisis</label>
                                    <select name="analysis_period" id="analysis_period" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="daily" {{ request('analysis_period') == 'daily' ? 'selected' : '' }}>Diario</option>
                                        <option value="weekly" {{ request('analysis_period') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                        <option value="monthly" {{ request('analysis_period') == 'monthly' ? 'selected' : '' }}>Mensual</option>
                                        <option value="batch" {{ request('analysis_period') == 'batch' ? 'selected' : '' }}>Por Lote</option>
                                        <option value="custom" {{ request('analysis_period') == 'custom' ? 'selected' : '' }}>Personalizado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Estado</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archivado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="profitability_status">Estado de Rentabilidad</label>
                                    <select name="profitability_status" id="profitability_status" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="profitable" {{ request('profitability_status') == 'profitable' ? 'selected' : '' }}>Rentable</option>
                                        <option value="loss" {{ request('profitability_status') == 'loss' ? 'selected' : '' }}>Pérdida</option>
                                        <option value="break_even" {{ request('profitability_status') == 'break_even' ? 'selected' : '' }}>Punto de Equilibrio</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_from">Desde</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-info btn-block">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Tabla de análisis -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Galpón</th>
                                    <th>Período</th>
                                    <th>Tipo</th>
                                    <th>Ingresos</th>
                                    <th>Costos</th>
                                    <th>Utilidad Neta</th>
                                    <th>Margen</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($analyses as $analysis)
                                <tr>
                                    <td>{{ $analysis->id }}</td>
                                    <td>{{ $analysis->productionCost->poultryFacility->name ?? 'N/A' }}</td>
                                    <td>
                                        {{ $analysis->period_start->format('d/m/Y') }} - 
                                        {{ $analysis->period_end->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $analysis->analysis_period_name }}</span>
                                    </td>
                                    <td>${{ number_format($analysis->total_revenue, 2) }}</td>
                                    <td>${{ number_format($analysis->total_production_cost, 2) }}</td>
                                    <td>
                                        <span class="font-weight-bold {{ $analysis->net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                            ${{ number_format($analysis->net_profit, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $analysis->net_margin_percentage >= 0 ? 'success' : 'danger' }}">
                                            {{ number_format($analysis->net_margin_percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        @if($analysis->status == 'draft')
                                            <span class="badge badge-warning">Borrador</span>
                                        @elseif($analysis->status == 'confirmed')
                                            <span class="badge badge-success">Confirmado</span>
                                        @else
                                            <span class="badge badge-secondary">Archivado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('avicontrol.admin.profitability_analysis.show', $analysis->id) }}" 
                                               class="btn btn-sm btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('avicontrol.admin.profitability_analysis.edit', $analysis->id) }}" 
                                               class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($analysis->status == 'draft')
                                                <form action="{{ route('avicontrol.admin.profitability_analysis.confirm', $analysis->id) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Confirmar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('avicontrol.admin.profitability_analysis.export_pdf', $analysis->id) }}" 
                                               class="btn btn-sm btn-secondary" title="Exportar PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <form action="{{ route('avicontrol.admin.profitability_analysis.destroy', $analysis->id) }}" 
                                                  method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('¿Está seguro de eliminar este análisis?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">No se encontraron análisis de rentabilidad</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $analyses->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen estadístico -->
    <div class="row mt-3">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Análisis</span>
                    <span class="info-box-number">{{ $analyses->total() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-arrow-up"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Rentables</span>
                    <span class="info-box-number">{{ $analyses->where('net_profit', '>', 0)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="fas fa-arrow-down"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Con Pérdidas</span>
                    <span class="info-box-number">{{ $analyses->where('net_profit', '<', 0)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-minus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Punto de Equilibrio</span>
                    <span class="info-box-number">{{ $analyses->where('net_profit', '=', 0)->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush 