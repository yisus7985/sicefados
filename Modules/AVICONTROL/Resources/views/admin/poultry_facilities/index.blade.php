@extends('avicontrol::layouts.admin')

@section('title', 'Gestión de Instalaciones Avícolas - AVICONTROL')

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
        
        <!-- Poultry Facilities List -->
        <div class="card">
            <div class="card-header">
        <h5 class="card-title">Lista de Instalaciones Avícolas</h5>
        <a href="{{ route('avicontrol.admin.poultry_facilities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Nueva Instalación
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
            <table class="table table-striped" id="poultryFacilitiesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                        <th>Nombre</th>
                        <th>Dimensiones (m)</th>
                        <th>Capacidad</th>
                        <th>Estado</th>
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($poultryFacilities as $facility)
                                <tr>
                                    <td>{{ $facility->id }}</td>
                                    <td>{{ $facility->name }}</td>
                                    <td>{{ $facility->length }} x {{ $facility->width }} x {{ $facility->height }}</td>
                            <td>{{ $facility->capacity }} aves</td>
                                    <td>
                                        @if($facility->status == 'active')
                                    <span class="badge bg-success">Activo</span>
                                        @elseif($facility->status == 'inactive')
                                    <span class="badge bg-danger">Inactivo</span>
                                        @else
                                    <span class="badge bg-warning">Mantenimiento</span>
                                        @endif
                                    </td>
                                    <td>{{ $facility->creation_date->format('d/m/Y') }}</td>
                                    <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('avicontrol.admin.poultry_facilities.show', $facility->id) }}" 
                                       class="btn btn-sm btn-info" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                    <a href="{{ route('avicontrol.admin.poultry_facilities.edit', $facility->id) }}" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                    <button type="button" class="btn btn-sm btn-danger" title="Eliminar" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $facility->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $facility->id }}" tabindex="-1" 
                                     aria-labelledby="deleteModalLabel{{ $facility->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $facility->id }}">Confirmar Eliminación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                ¿Está seguro de que desea eliminar la instalación <strong>{{ $facility->name }}</strong>?
                                                <p class="text-danger mt-2">Esta acción no se puede deshacer.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <form action="{{ route('avicontrol.admin.poultry_facilities.destroy', $facility->id) }}" method="POST">
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
                            <td colspan="7" class="text-center">No hay instalaciones registradas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
    
@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#poultryFacilitiesTable').DataTable({
                language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                },
                responsive: true
            });
        });
    </script>
@endpush
