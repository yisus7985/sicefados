@extends('avicontrol::layouts.admin')

@section('title', 'Información - Costos de Producción')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('avicontrol.admin.information.index') }}">Información</a></li>
            <li class="breadcrumb-item active" aria-current="page">Costos de Producción</li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-12">
            <div class="page-header mb-3">
                <h2 class="page-title">
                    <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                    Costos de Producción (Resumen)
                </h2>
                <p class="text-muted mb-0">Visualización de registros de costos de producción</p>
            </div>

            @if(isset($error))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Advertencia:</strong> {{ $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        Listado de Costos
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('avicontrol.admin.information.index') }}" class="btn btn-secondary btn-sm me-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            Volver
                        </a>
                        <a href="{{ route('avicontrol.admin.information.costos_produccion.pdf') }}" class="btn btn-danger btn-sm me-2">
                            <i class="fas fa-file-pdf me-1"></i>
                            Descargar PDF
                        </a>
                        <span class="badge bg-info">
                            @if(isset($productionCosts) && count($productionCosts) > 0)
                                Total: {{ $productionCosts instanceof \Illuminate\Pagination\LengthAwarePaginator ? $productionCosts->total() : count($productionCosts) }}
                            @else
                                Total: 0
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
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
                                    <th>Creado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productionCosts ?? [] as $cost)
                                <tr>
                                    <td>{{ $cost->id }}</td>
                                    <td>{{ $cost->facility_name ?? 'N/A' }}</td>
                                    <td>{{ $cost->batch_code ?? 'N/A' }}</td>
                                    <td>
                                        @if($cost->period_start && $cost->period_end)
                                            {{ \Carbon\Carbon::parse($cost->period_start)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($cost->period_end)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ ($cost->cost_type ?? '') === 'batch' ? 'primary' : 'info' }}" style="color: white; font-weight: bold;">
                                            {{ $cost->cost_type_name ?? strtoupper($cost->cost_type ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($cost->total_cost ?? 0, 2) }}</td>
                                    <td>
                                        @if(!is_null($cost->cost_per_unit))
                                            ${{ number_format($cost->cost_per_unit, 2) }}
                                            <small class="text-muted">/{{ $cost->unit_type_name ?? 'unidad' }}</small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @php($status = $cost->status ?? 'draft')
                                        @if($status === 'draft')
                                            <span class="badge badge-warning" style="color: white; font-weight: bold;">Borrador</span>
                                        @elseif($status === 'confirmed')
                                            <span class="badge badge-success" style="color: white; font-weight: bold;">Confirmado</span>
                                        @elseif($status === 'cancelled')
                                            <span class="badge badge-danger" style="color: white; font-weight: bold;">Cancelado</span>
                                        @else
                                            <span class="badge badge-secondary">{{ strtoupper($status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($cost->created_at))
                                            {{ \Carbon\Carbon::parse($cost->created_at)->format('d/m/Y H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Acciones">
                                            <a href="{{ route('avicontrol.admin.information.costos_produccion.pdf_show', $cost->id) }}" class="btn btn-outline-danger btn-sm" title="Descargar PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        No hay costos de producción para mostrar
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(($productionCosts ?? null) instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center">
                            {{ $productionCosts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Estilos para la vista de costos de producción */
.badge { 
    font-size: 0.85em; 
    padding: 0.5em 0.75em; 
    border-radius: 0.375rem; 
    font-weight: 600;
}

.badge-primary { background-color: #007bff !important; color: #fff !important; }
.badge-info { background-color: #17a2b8 !important; color: #fff !important; }
.badge-warning { background-color: #ffc107 !important; color: #212529 !important; }
.badge-success { background-color: #28a745 !important; color: #fff !important; }
.badge-danger { background-color: #dc3545 !important; color: #fff !important; }

/* Breadcrumb */
.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 20px;
    font-size: 0.9rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
    font-weight: bold;
}

.breadcrumb-item a {
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: #0056b3;
}

/* Page header */
.page-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    margin-bottom: 25px;
}

.page-title {
    color: #495057;
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
}

.page-subtitle {
    color: #6c757d;
    margin: 10px 0 0 0;
    font-size: 1rem;
}

/* Card */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    margin-bottom: 25px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}

.card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
    padding: 20px 25px;
}

.card-title {
    margin: 0;
    color: #495057;
    font-weight: 600;
    font-size: 1.1rem;
}

.card-body {
    padding: 25px;
}

/* Botones */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 10px 20px;
    transition: all 0.3s ease;
    border: none;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.875rem;
}

/* Tabla */
.table-responsive {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
    background: white;
}

.table th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
    color: #495057;
    padding: 18px 15px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    padding: 15px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: scale(1.01);
    transition: all 0.2s ease;
}

/* Paginación */
.pagination {
    justify-content: center;
    margin-top: 20px;
}

.page-link {
    border-radius: 8px;
    margin: 0 2px;
    border: none;
    color: #007bff;
    transition: all 0.3s ease;
}

.page-link:hover {
    background-color: #007bff;
    color: white;
    transform: translateY(-1px);
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 15px;
    }
    
    .page-title {
        font-size: 1.25rem;
    }
    
    .card-header {
        padding: 15px 20px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .table th,
    .table td {
        padding: 12px 8px;
        font-size: 0.85rem;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.8rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-ocultar alertas después de 4 segundos
    setTimeout(function(){ 
        $('.alert').fadeOut('slow'); 
    }, 4000);
    
    // Agregar tooltips a los badges de estado
    $('[data-toggle="tooltip"]').tooltip();
    
    // Mejorar la experiencia de la tabla
    $('.table tbody tr').hover(
        function() {
            $(this).addClass('table-hover');
        },
        function() {
            $(this).removeClass('table-hover');
        }
    );
    
    // Confirmar solo en botones marcados explícitamente como destructivos
    $('[data-confirm="true"]').on('click', function(e) {
        if (!confirm($(this).data('confirmMessage') || '¿Está seguro de que desea realizar esta acción?')) {
            e.preventDefault();
        }
    });
    
    // Mejorar la paginación
    $('.pagination .page-link').on('click', function() {
        $('html, body').animate({
            scrollTop: $('.card').offset().top - 100
        }, 500);
    });
    
    console.log('Vista de costos de producción cargada correctamente');
});
</script>
@endpush
