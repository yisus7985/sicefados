@extends('avicontrol::layouts.admin')

@section('title', 'Detalle del Galpón: ' . $galpon->name)

@push('styles')
    {{-- Si hay estilos personalizados estrictamente necesarios, colócalos aquí --}}
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-warehouse text-success me-2"></i>
                        Detalle del Galpón: {{ $galpon->name }}
                    </h1>
                </div>
                <div>
                    <a href="{{ route('avicontrol.admin.information.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver a la lista
                    </a>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-success text-white">
                            <h6 class="m-0 fw-bold">📦 Información General</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Descripción:</span>
                                    <span>{{ $galpon->description ?? 'No especificada' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Capacidad:</span>
                                    <span class="text-success fw-bold">{{ number_format($galpon->capacity) }} aves</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Área:</span>
                                    <span>{{ $galpon->area }} m²</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Estado:</span>
                                    <span class="badge bg-success">{{ $galpon->status }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="m-0 fw-bold">📊 Estadísticas de Ocupación</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Aves Activas:</span>
                                    <span class="text-primary fw-bold">{{ number_format($galpon->total_active_birds) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Ocupación:</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span>{{ $galpon->occupancy_percentage }}%</span>
                                        <div class="progress" style="width: 100px; height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $galpon->occupancy_percentage }}%;"></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Capacidad Disponible:</span>
                                    <span>{{ number_format($galpon->capacity - $galpon->total_active_birds) }} aves</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Densidad:</span>
                                    <span>{{ round($galpon->total_active_birds / $galpon->area, 2) }} aves/m²</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 fw-bold">🐣 Aves en este galpón</h6>
                </div>
                <div class="card-body">
                    @if($galpon->birds->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Código Lote</th>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th>Edad (semanas)</th>
                                        <th>Estado</th>
                                        <th>Raza</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($galpon->birds as $ave)
                                        <tr>
                                            <td><strong>{{ $ave->batch_code }}</strong></td>
                                            <td>{{ $ave->bird_type_name }}</td>
                                            <td class="fw-bold text-success">{{ number_format($ave->quantity) }}</td>
                                            <td>{{ $ave->current_age_weeks ?? $ave->age_weeks }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ $ave->status_name }}</span>
                                            </td>
                                            <td>{{ $ave->breed ?? 'No especificada' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            🐣 No hay aves registradas en este galpón actualmente.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Si hay scripts personalizados estrictamente necesarios, colócalos aquí --}}
@endpush