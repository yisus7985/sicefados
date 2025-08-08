@extends('avicontrol::layouts.admin')

@section('title', 'Detalles del Costo de Producción')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calculator"></i> Detalles del Costo de Producción #{{ $productionCost->id }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('avicontrol.admin.production_costs.edit', $productionCost->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('avicontrol.admin.production_costs.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información básica -->
                        <div class="col-md-6">
                            <h5>Información Básica</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Galpón:</strong></td>
                                    <td>{{ $productionCost->poultryFacility->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Lote de Aves:</strong></td>
                                    <td>
                                        @if($productionCost->bird)
                                            {{ $productionCost->bird->batch_code }} - {{ $productionCost->bird->bird_type_name }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Código de Lote:</strong></td>
                                    <td>{{ $productionCost->batch_code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo de Costo:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $productionCost->cost_type == 'batch' ? 'primary' : 'info' }}">
                                            {{ $productionCost->cost_type_name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td>
                                        @if($productionCost->status == 'draft')
                                            <span class="badge badge-warning">Borrador</span>
                                        @elseif($productionCost->status == 'confirmed')
                                            <span class="badge badge-success">Confirmado</span>
                                        @else
                                            <span class="badge badge-danger">Cancelado</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Período y métricas -->
                        <div class="col-md-6">
                            <h5>Período y Métricas</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Período:</strong></td>
                                    <td>
                                        {{ $productionCost->period_start->format('d/m/Y') }} - 
                                        {{ $productionCost->period_end->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">({{ $productionCost->period_duration }} días)</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tipo de Unidad:</strong></td>
                                    <td>{{ $productionCost->unit_type_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Costo Total:</strong></td>
                                    <td><span class="text-primary font-weight-bold">${{ number_format($productionCost->total_cost, 2) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Costo por Unidad:</strong></td>
                                    <td>
                                        @if($productionCost->cost_per_unit)
                                            <span class="text-info font-weight-bold">
                                                ${{ number_format($productionCost->cost_per_unit, 2) }}
                                            </span>
                                            <small class="text-muted">/{{ $productionCost->unit_type_name }}</small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de Creación:</strong></td>
                                    <td>{{ $productionCost->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Desglose de costos -->
                    <h5>Desglose de Costos</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Componente</th>
                                            <th class="text-right">Costo</th>
                                            <th class="text-right">% del Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalCost = $productionCost->total_cost;
                                        @endphp
                                        <tr>
                                            <td>Concentrado</td>
                                            <td class="text-right">${{ number_format($productionCost->concentrate_cost, 2) }}</td>
                                            <td class="text-right">{{ $totalCost > 0 ? number_format(($productionCost->concentrate_cost / $totalCost) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Agua</td>
                                            <td class="text-right">${{ number_format($productionCost->water_cost, 2) }}</td>
                                            <td class="text-right">{{ $totalCost > 0 ? number_format(($productionCost->water_cost / $totalCost) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Energía</td>
                                            <td class="text-right">${{ number_format($productionCost->energy_cost, 2) }}</td>
                                            <td class="text-right">{{ $totalCost > 0 ? number_format(($productionCost->energy_cost / $totalCost) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Medicamentos</td>
                                            <td class="text-right">${{ number_format($productionCost->medication_cost, 2) }}</td>
                                            <td class="text-right">{{ $totalCost > 0 ? number_format(($productionCost->medication_cost / $totalCost) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Biológicos</td>
                                            <td class="text-right">${{ number_format($productionCost->biological_cost, 2) }}</td>
                                            <td class="text-right">{{ $totalCost > 0 ? number_format(($productionCost->biological_cost / $totalCost) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Embalajes</td>
                                            <td class="text-right">${{ number_format($productionCost->packaging_cost, 2) }}</td>
                                            <td class="text-right">{{ $totalCost > 0 ? number_format(($productionCost->packaging_cost / $totalCost) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Mano de Obra</td>
                                            <td class="text-right">${{ number_format($productionCost->labor_cost, 2) }}</td>
                                            <td class="text-right">{{ $totalCost > 0 ? number_format(($productionCost->labor_cost / $totalCost) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Mantenimiento</td>
                                            <td class="text-right">${{ number_format($productionCost->maintenance_cost, 2) }}</td>
                                            <td class="text-right">{{ $totalCost > 0 ? number_format(($productionCost->maintenance_cost / $totalCost) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr>
                                            <td>Otros Costos</td>
                                            <td class="text-right">${{ number_format($productionCost->other_costs, 2) }}</td>
                                            <td class="text-right">{{ $totalCost > 0 ? number_format(($productionCost->other_costs / $totalCost) * 100, 1) : 0 }}%</td>
                                        </tr>
                                        <tr class="table-primary font-weight-bold">
                                            <td><strong>TOTAL</strong></td>
                                            <td class="text-right"><strong>${{ number_format($productionCost->total_cost, 2) }}</strong></td>
                                            <td class="text-right"><strong>100%</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Gráfico de costos -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Distribución de Costos</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="costChart" width="400" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Componentes detallados -->
                    @if($productionCost->costComponents->count() > 0)
                    <hr>
                    <h5>Componentes Detallados</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Costo Total</th>
                                    <th>Fecha Aplicada</th>
                                    <th>Proveedor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productionCost->costComponents as $component)
                                <tr>
                                    <td>
                                        <span class="badge badge-secondary">{{ $component->component_type_name }}</span>
                                    </td>
                                    <td>{{ $component->component_name }}</td>
                                    <td>{{ number_format($component->quantity, 2) }} {{ $component->unit_measure_name }}</td>
                                    <td>${{ number_format($component->unit_price, 2) }}</td>
                                    <td>${{ number_format($component->total_cost, 2) }}</td>
                                    <td>{{ $component->date_applied->format('d/m/Y') }}</td>
                                    <td>{{ $component->supplier ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Notas -->
                    @if($productionCost->notes)
                    <hr>
                    <h5>Notas</h5>
                    <div class="alert alert-info">
                        {{ $productionCost->notes }}
                    </div>
                    @endif

                    <!-- Análisis de rentabilidad -->
                    @if($productionCost->profitabilityAnalysis)
                    <hr>
                    <h5>Análisis de Rentabilidad Asociado</h5>
                    <div class="alert alert-success">
                        <strong>Este costo de producción tiene un análisis de rentabilidad asociado.</strong>
                        <br>
                        <a href="{{ route('avicontrol.admin.profitability_analysis.show', $productionCost->profitabilityAnalysis->id) }}" 
                           class="btn btn-sm btn-success mt-2">
                            <i class="fas fa-chart-line"></i> Ver Análisis de Rentabilidad
                        </a>
                    </div>
                    @else
                    <hr>
                    <h5>Análisis de Rentabilidad</h5>
                    <div class="alert alert-warning">
                        <strong>Este costo de producción no tiene un análisis de rentabilidad asociado.</strong>
                        <br>
                        <a href="{{ route('avicontrol.admin.profitability_analysis.create') }}?production_cost_id={{ $productionCost->id }}" 
                           class="btn btn-sm btn-warning mt-2">
                            <i class="fas fa-plus"></i> Crear Análisis de Rentabilidad
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Gráfico de distribución de costos
    const ctx = document.getElementById('costChart').getContext('2d');
    const costData = {
        labels: [
            'Concentrado', 'Agua', 'Energía', 'Medicamentos', 
            'Biológicos', 'Embalajes', 'Mano de Obra', 'Mantenimiento', 'Otros'
        ],
        datasets: [{
            data: [
                {{ $productionCost->concentrate_cost }},
                {{ $productionCost->water_cost }},
                {{ $productionCost->energy_cost }},
                {{ $productionCost->medication_cost }},
                {{ $productionCost->biological_cost }},
                {{ $productionCost->packaging_cost }},
                {{ $productionCost->labor_cost }},
                {{ $productionCost->maintenance_cost }},
                {{ $productionCost->other_costs }}
            ],
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0'
            ]
        }]
    };

    new Chart(ctx, {
        type: 'doughnut',
        data: costData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush 