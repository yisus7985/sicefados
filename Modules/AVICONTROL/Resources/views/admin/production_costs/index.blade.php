@extends('avicontrol::layouts.admin')

@section('title', 'Costos de Producción')

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
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calculator"></i> Costos de Producción
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('avicontrol.admin.production_costs.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Costo
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('avicontrol.admin.production_costs.index') }}" class="mb-3">
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
                                    <label for="cost_type">Tipo de Costo</label>
                                    <select name="cost_type" id="cost_type" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="batch" {{ request('cost_type') == 'batch' ? 'selected' : '' }}>Por Lote</option>
                                        <option value="period" {{ request('cost_type') == 'period' ? 'selected' : '' }}>Por Período</option>
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
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_from">Desde</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_to">Hasta</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
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

                    <!-- Tabla de costos -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Galpón</th>
                                    <th>Lote</th>
                                    <th>Período</th>
                                    <th>Tipo</th>
                                    <th>Costo Total</th>
                                    <th>Costo/Unidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productionCosts as $cost)
                                <tr>
                                    <td>{{ $cost->id }}</td>
                                    <td>{{ $cost->poultryFacility->name ?? 'N/A' }}</td>
                                    <td>{{ $cost->batch_code ?? 'N/A' }}</td>
                                    <td>
                                        {{ $cost->period_start->format('d/m/Y') }} - 
                                        {{ $cost->period_end->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $cost->cost_type == 'batch' ? 'primary' : 'info' }}">
                                            {{ $cost->cost_type_name }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($cost->total_cost, 2) }}</td>
                                    <td>
                                        @if($cost->cost_per_unit)
                                            ${{ number_format($cost->cost_per_unit, 2) }}
                                            <small class="text-muted">/{{ $cost->unit_type_name }}</small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($cost->status == 'draft')
                                            <span class="badge badge-warning">Borrador</span>
                                        @elseif($cost->status == 'confirmed')
                                            <span class="badge badge-success">Confirmado</span>
                                        @else
                                            <span class="badge badge-danger">Cancelado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('avicontrol.admin.production_costs.show', $cost->id) }}" 
                                               class="btn btn-sm btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('avicontrol.admin.production_costs.edit', $cost->id) }}" 
                                               class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($cost->status == 'draft')
                                                <form action="{{ route('avicontrol.admin.production_costs.confirm', $cost->id) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Confirmar">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('avicontrol.admin.production_costs.destroy', $cost->id) }}" 
                                                  method="POST" style="display: inline;" 
                                                  onsubmit="return confirm('¿Está seguro de eliminar este costo?')">
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
                                    <td colspan="9" class="text-center">No se encontraron costos de producción</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($productionCosts instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center">
                            {{ $productionCosts->appends(request()->query())->links() }}
                        </div>
                    @endif
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