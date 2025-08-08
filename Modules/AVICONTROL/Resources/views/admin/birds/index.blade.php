@extends('avicontrol::layouts.admin')

@section('title', 'Gestión de Aves - AVICONTROL')

@section('content')
<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Birds Management -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Gestión de Aves</h5>
        <a href="{{ route('avicontrol.admin.birds.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Nuevo Lote de Aves
            </a>
        </div>
            <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('avicontrol.admin.birds.index') }}" class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="facility_id" class="form-label">Instalación</label>
                <select name="facility_id" id="facility_id" class="form-control">
                            <option value="">Todas las instalaciones</option>
                            @foreach($poultryFacilities as $facility)
                                <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                                    {{ $facility->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
            <div class="col-md-2">
                        <label for="bird_type" class="form-label">Tipo de Ave</label>
                <select name="bird_type" id="bird_type" class="form-control">
                    <option value="">Todos</option>
                    <option value="layer" {{ request('bird_type') == 'layer' ? 'selected' : '' }}>Ponedoras</option>
                    <option value="broiler" {{ request('bird_type') == 'broiler' ? 'selected' : '' }}>Pollo de Engorde</option>
                    <option value="breeder" {{ request('bird_type') == 'breeder' ? 'selected' : '' }}>Reproductoras</option>
                        </select>
                    </div>
            <div class="col-md-2">
                        <label for="status" class="form-label">Estado</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Todos</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Vendido</option>
                        </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Desde</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Hasta</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-info btn-block">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <!-- Birds Table -->
                    <div class="table-responsive">
            <table class="table table-striped" id="birdsTable">
                            <thead>
                                <tr>
                        <th>ID</th>
                        <th>Lote</th>
                                    <th>Instalación</th>
                        <th>Tipo</th>
                                    <th>Cantidad</th>
                        <th>Edad (días)</th>
                                    <th>Estado</th>
                        <th>Fecha de Llegada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                    @forelse($birds as $bird)
                        <tr>
                            <td>{{ $bird->id }}</td>
                            <td>{{ $bird->batch_name }}</td>
                            <td>{{ $bird->poultryFacility->name ?? 'N/A' }}</td>
                            <td>
                                @if($bird->bird_type == 'layer')
                                    <span class="badge bg-info">Ponedoras</span>
                                @elseif($bird->bird_type == 'broiler')
                                    <span class="badge bg-warning">Pollo de Engorde</span>
@else
                                    <span class="badge bg-primary">Reproductoras</span>
                                            @endif
                                        </td>
                            <td>{{ number_format($bird->quantity) }}</td>
                            <td>{{ $bird->age_days ?? 'N/A' }}</td>
                            <td>
                                @if($bird->status == 'active')
                                    <span class="badge bg-success">Activo</span>
                                @elseif($bird->status == 'inactive')
                                    <span class="badge bg-danger">Inactivo</span>
                                            @else
                                    <span class="badge bg-secondary">Vendido</span>
                                            @endif
                                        </td>
                            <td>{{ $bird->arrival_date ? $bird->arrival_date->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('avicontrol.admin.birds.show', $bird->id) }}" 
                                       class="btn btn-sm btn-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('avicontrol.admin.birds.edit', $bird->id) }}" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                    <button type="button" class="btn btn-sm btn-danger" title="Eliminar" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $bird->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $bird->id }}" tabindex="-1" 
                                     aria-labelledby="deleteModalLabel{{ $bird->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $bird->id }}">Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Está seguro de que desea eliminar el lote <strong>{{ $bird->batch_name }}</strong>?
                                                <p class="text-danger mt-2">Esta acción no se puede deshacer.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('avicontrol.admin.birds.destroy', $bird->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No hay lotes de aves registrados</td>
                        </tr>
                    @endforelse
                            </tbody>
                        </table>
                    </div>

        <!-- Pagination -->
        @if($birds instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center">
            {{ $birds->appends(request()->query())->links() }}
                    </div>
                @endif
    </div>
</div>

<!-- Summary Cards -->
<div class="row mt-3">
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-dove"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total de Aves</span>
                <span class="info-box-number">{{ $birds->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-egg"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Ponedoras</span>
                <span class="info-box-number">{{ $birds->where('bird_type', 'layer')->count() }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-drumstick-bite"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pollo de Engorde</span>
                <span class="info-box-number">{{ $birds->where('bird_type', 'broiler')->count() }}</span>
                </div>
                </div>
                </div>
    <div class="col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-venus-mars"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Reproductoras</span>
                <span class="info-box-number">{{ $birds->where('bird_type', 'breeder')->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
        $('#birdsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            responsive: true,
            pageLength: 25
        });
        });
    </script>
@endpush