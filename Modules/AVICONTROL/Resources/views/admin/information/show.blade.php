@extends('avicontrol::layouts.admin')

@section('title', 'Detalle del Galpón: ' . $galpon->name)

@push('styles')
    <style>
        .bird-image-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .bird-image {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
            border: 2px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .bird-info {
            display: flex;
            flex-direction: column;
        }
        
        .bird-type-name {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }
        
        .bird-breed-name {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .image-placeholder {
            width: 40px;
            height: 40px;
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 0.7rem;
            text-align: center;
        }
        
        .table td {
            vertical-align: middle;
        }
    </style>
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
                                        <th>Tipo de Ave</th>
                                        <th>Raza</th>
                                        <th>Cantidad</th>
                                        <th>Edad (semanas)</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($galpon->birds as $ave)
                                        <tr>
                                            <td><strong>{{ $ave->batch_code }}</strong></td>
                                            <td>
                                                <div class="bird-image-container">
                                                    <img src="/avicontrol/img/tipoave/{{ $ave->bird_type }}.jpg" 
                                                         alt="{{ $ave->bird_type_name }}" 
                                                         class="bird-image"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div class="image-placeholder" style="display: none;">
                                                        <span>{{ substr($ave->bird_type_name, 0, 2) }}</span>
                                                    </div>
                                                    <div class="bird-info">
                                                        <span class="bird-type-name">{{ $ave->bird_type_name }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="bird-image-container">
                                                    @php
                                                        $breedImageName = '';
                                                        switch($ave->breed) {
                                                            case 'Rhode Island Red':
                                                                $breedImageName = 'rhode_island_red';
                                                                break;
                                                            case 'Leghorn':
                                                                $breedImageName = 'leghorn';
                                                                break;
                                                            case 'Plymouth Rock':
                                                                $breedImageName = 'plymouth_rock';
                                                                break;
                                                            case 'Sussex':
                                                                $breedImageName = 'sussex';
                                                                break;
                                                            case 'Orpington':
                                                                $breedImageName = 'orpington';
                                                                break;
                                                            case 'Australorp':
                                                                $breedImageName = 'australorp';
                                                                break;
                                                            case 'New Hampshire':
                                                                $breedImageName = 'new_hampshire';
                                                                break;
                                                            case 'Cornish Cross':
                                                                $breedImageName = 'cornish_cross';
                                                                break;
                                                            case 'Cobb 500':
                                                                $breedImageName = 'cobb_500';
                                                                break;
                                                            case 'Ross 308':
                                                                $breedImageName = 'ross_308';
                                                                break;
                                                            case 'Otro':
                                                                $breedImageName = 'other';
                                                                break;
                                                            default:
                                                                $breedImageName = 'other';
                                                        }
                                                    @endphp
                                                    @if($ave->breed)
                                                        <img src="/avicontrol/img/raza/{{ $breedImageName }}.jpg" 
                                                             alt="{{ $ave->breed }}" 
                                                             class="bird-image"
                                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                        <div class="image-placeholder" style="display: none;">
                                                            <span>{{ substr($ave->breed, 0, 2) }}</span>
                                                        </div>
                                                        <div class="bird-info">
                                                            <span class="bird-breed-name">{{ $ave->breed }}</span>
                                                        </div>
                                                    @else
                                                        <div class="image-placeholder">
                                                            <span>N/A</span>
                                                        </div>
                                                        <div class="bird-info">
                                                            <span class="bird-breed-name">No especificada</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="fw-bold text-success">{{ number_format($ave->quantity) }}</td>
                                            <td>{{ $ave->current_age_weeks ?? $ave->age_weeks }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ $ave->status_name }}</span>
                                            </td>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejo de errores de imágenes
    const images = document.querySelectorAll('.bird-image');
    images.forEach(function(img) {
        img.addEventListener('error', function() {
            this.style.display = 'none';
            const placeholder = this.nextElementSibling;
            if (placeholder && placeholder.classList.contains('image-placeholder')) {
                placeholder.style.display = 'flex';
            }
        });
        
        img.addEventListener('load', function() {
            this.style.display = 'block';
            const placeholder = this.nextElementSibling;
            if (placeholder && placeholder.classList.contains('image-placeholder')) {
                placeholder.style.display = 'none';
            }
        });
    });
});
</script>
@endpush