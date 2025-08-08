@extends('avicontrol::layouts.admin')

@section('title', 'Informes de Galpones')

@push('styles')
    {{-- Si hay estilos personalizados estrictamente necesarios, colócalos aquí --}}
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#galponesTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "pageLength": 10,
            "order": [[ 0, "asc" ]], // Ordenar por nombre de galpón ascendente
            "columnDefs": [
                { "orderable": false, "targets": 3 } // Deshabilitar ordenamiento en columna de acciones
            ]
        });
    });
</script>
@endpush

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
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="mb-0">Informes de Galpones</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-clipboard-list text-primary me-2"></i>
                    Gestión de Informes
                </h1>
                <p class="text-muted">Genere y consulte informes detallados de las instalaciones y producción</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('avicontrol.admin.information.inventory') }}" class="btn btn-success">
                    <i class="fas fa-boxes me-1"></i> Informes de Inventario
                </a>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Galpones
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ count($galpones) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                            </div>
                        </div>
                </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Capacidad Total
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($galpones->sum('capacity')) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-egg fa-2x text-gray-300"></i>
                            </div>
                        </div>
                </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Galpones Activos
                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                        @php
                            $activosCount = $galpones->filter(function($galpon) {
                                return stripos($galpon->status, 'activ') !== false || 
                                       stripos($galpon->status, 'operativo') !== false ||
                                       stripos($galpon->status, 'funcionando') !== false;
                            })->count();
                        @endphp
                        {{ $activosCount }}
                    </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Reportes Disponibles
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ count($galpones) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Reportes de Galpones</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="galponesTable">
                    <thead>
                        <tr>
                            <th>Nombre del Galpón</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                        <tbody>
                        @foreach($galpones as $galpon)
                            <tr class="galpon-row" data-name="{{ strtolower($galpon->name) }}" data-status="{{ strtolower($galpon->status) }}">
                                <td class="galpon-name">{{ $galpon->name }}</td>
                                <td class="capacity-cell">{{ number_format($galpon->capacity) }} aves</td>
                                <td>
                                        <span class="badge bg-success">{{ $galpon->status }}</span>
                                </td>
                                <td>
                                        <a href="{{ route('avicontrol.admin.information.show', $galpon->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> Ver Reporte
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-chart-bar me-2"></i>Resumen de Informes</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="fas fa-warehouse text-success me-2"></i> Galpones: <strong>{{ count($galpones) }}</strong></li>
                    <li class="mb-2"><i class="fas fa-chart-bar text-info me-2"></i> Activos: <strong>{{ $activosCount }}</strong></li>
                    <li class="mb-2"><i class="fas fa-clipboard-list text-warning me-2"></i> Reportes: <strong>{{ count($galpones) }}</strong></li>
                    <li><i class="fas fa-egg text-primary me-2"></i> Capacidad: <strong>{{ number_format($galpones->sum('capacity')) }}</strong></li>
                </ul>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-header bg-light py-3">
                <h6 class="m-0 fw-bold text-success"><i class="fas fa-history me-2"></i>Actividad Reciente</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <span class="badge bg-success me-2"><i class="fas fa-check"></i></span>
                        <strong>Informe generado</strong>
                        <br><small class="text-muted">Se generó un informe recientemente</small>
                    </li>
                    <li class="text-muted">Sin actividad reciente</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection