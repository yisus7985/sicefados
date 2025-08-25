<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Costos de Producción - AVICONTROL</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border-radius: 8px;
        }
        
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .info-section {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #dc3545;
        }
        
        .info-section h3 {
            font-size: 12px;
            margin-bottom: 8px;
            color: #dc3545;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 8px;
            text-align: center;
            background-color: #fff5f5;
            border: 1px solid #ddd;
        }
        
        .stats-cell.header {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }
        
        .stats-value {
            font-size: 14px;
            font-weight: bold;
            color: #721c24;
        }
        
        .stats-label {
            font-size: 9px;
            color: #666;
            margin-top: 2px;
        }
        
        .table-container {
            margin-top: 15px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8px;
        }
        
        .table th {
            background-color: #dc3545;
            color: white;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        
        .table td {
            padding: 4px 3px;
            text-align: center;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .table tbody tr:hover {
            background-color: #fff5f5;
        }
        
        .text-right {
            text-align: right !important;
        }
        
        .text-center {
            text-align: center !important;
        }
        
        .text-success {
            color: #28a745;
            font-weight: bold;
        }
        
        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }
        
        .text-warning {
            color: #ffc107;
            font-weight: bold;
        }
        
        .text-info {
            color: #17a2b8;
            font-weight: bold;
        }
        
        .footer {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .summary-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fff5f5;
            border-radius: 5px;
            border: 1px solid #dc3545;
        }
        
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        
        .cost-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        
        .cost-batch { background-color: #007bff; color: white; }
        .cost-monthly { background-color: #28a745; color: white; }
        .cost-weekly { background-color: #ffc107; color: black; }
        .cost-daily { background-color: #17a2b8; color: white; }
        .cost-feed { background-color: #fd7e14; color: white; }
        .cost-medical { background-color: #e83e8c; color: white; }
        .cost-maintenance { background-color: #6f42c1; color: white; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>💰 INFORME DE COSTOS DE PRODUCCIÓN</h1>
        <p>Sistema de Control AVICONTROL - Generado el {{ $generatedAt }}</p>
    </div>

    <!-- Resumen Estadísticas -->
    @if(!empty($estadisticas))
    <div class="summary-section">
        <div class="summary-title">📊 RESUMEN EJECUTIVO DE COSTOS</div>
        
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">Total Costos</div>
                <div class="stats-cell header">Costo Promedio</div>
                <div class="stats-cell header">Costo Más Alto</div>
                <div class="stats-cell header">Total Registros</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">${{ number_format($estadisticas['total_costos'] ?? 0, 2) }}</div>
                    <div class="stats-label">Inversión total</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">${{ number_format($estadisticas['costo_promedio'] ?? 0, 2) }}</div>
                    <div class="stats-label">Por registro</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">${{ number_format($estadisticas['costo_mas_alto'] ?? 0, 2) }}</div>
                    <div class="stats-label">Mayor inversión</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['total_registros'] ?? 0) }}</div>
                    <div class="stats-label">Registros totales</div>
                </div>
            </div>
        </div>

        <div class="stats-grid" style="margin-top: 10px;">
            <div class="stats-row">
                <div class="stats-cell header">Galpón Mayor Costo</div>
                <div class="stats-cell header">Tipo Más Común</div>
                <div class="stats-cell header">Costo Más Bajo</div>
                <div class="stats-cell header">Eficiencia</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">{{ $estadisticas['galpon_mayor_costo'] ?? 'N/A' }}</div>
                    <div class="stats-label">Mayor inversión</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $estadisticas['tipo_mas_comun'] ?? 'N/A' }}</div>
                    <div class="stats-label">Categoría principal</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">${{ number_format($estadisticas['costo_mas_bajo'] ?? 0, 2) }}</div>
                    <div class="stats-label">Menor inversión</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['eficiencia_costos'] ?? 0, 1) }}%</div>
                    <div class="stats-label">Costo/beneficio</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de Datos -->
    <div class="table-container">
        <h3 style="color: #dc3545; margin-bottom: 10px;">📋 DETALLE DE COSTOS DE PRODUCCIÓN</h3>
        
        @if($data && $data->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 6%;">ID</th>
                        <th style="width: 8%;">Fecha</th>
                        <th style="width: 10%;">Galpón</th>
                        <th style="width: 8%;">Lote</th>
                        <th style="width: 12%;">Período</th>
                        <th style="width: 10%;">Tipo</th>
                        <th style="width: 15%;">Descripción</th>
                        <th style="width: 10%;">Costo Total</th>
                        <th style="width: 8%;">Costo/Unidad</th>
                        <th style="width: 6%;">Cant.</th>
                        <th style="width: 7%;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $registro)
                    <tr>
                        <td>{{ $registro['id'] ?? 'N/A' }}</td>
                        <td>
                            @php
                                $fecha = isset($registro['created_at']) ? \Carbon\Carbon::parse($registro['created_at']) : null;
                            @endphp
                            {{ $fecha ? $fecha->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td>{{ $registro['facility_name'] ?? 'N/A' }}</td>
                        <td>{{ $registro['batch_code'] ?? 'N/A' }}</td>
                        <td>
                            @php
                                $inicio = isset($registro['period_start']) ? \Carbon\Carbon::parse($registro['period_start']) : null;
                                $fin = isset($registro['period_end']) ? \Carbon\Carbon::parse($registro['period_end']) : null;
                            @endphp
                            @if($inicio && $fin)
                                {{ $inicio->format('d/m') }} - {{ $fin->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @php
                                $tiposCosto = [
                                    'batch' => ['Por Lote', 'cost-batch'],
                                    'monthly' => ['Mensual', 'cost-monthly'],
                                    'weekly' => ['Semanal', 'cost-weekly'],
                                    'daily' => ['Diario', 'cost-daily'],
                                    'feed' => ['Alimentación', 'cost-feed'],
                                    'medical' => ['Médico', 'cost-medical'],
                                    'maintenance' => ['Mantenimiento', 'cost-maintenance']
                                ];
                                $tipo = $tiposCosto[$registro['cost_type'] ?? ''] ?? [ucfirst($registro['cost_type'] ?? 'N/A'), 'cost-batch'];
                            @endphp
                            <span class="cost-badge {{ $tipo[1] }}">{{ $tipo[0] }}</span>
                        </td>
                        <td>{{ $registro['description'] ?? 'Sin descripción' }}</td>
                        <td class="text-right text-danger">
                            <strong>${{ number_format($registro['total_cost'] ?? 0, 2) }}</strong>
                        </td>
                        <td class="text-right">
                            @if(($registro['cost_per_unit'] ?? 0) > 0)
                                ${{ number_format($registro['cost_per_unit'], 2) }}
                                <small>/{{ $registro['unit_type_name'] ?? $registro['unit_type'] ?? 'u' }}</small>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($registro['quantity'] ?? 0) }}</td>
                        <td class="text-center">
                            @php
                                $estado = $registro['status'] ?? 'active';
                                $claseEstado = $estado === 'active' ? 'text-success' : ($estado === 'completed' ? 'text-info' : 'text-warning');
                                $iconoEstado = $estado === 'active' ? '🟢' : ($estado === 'completed' ? '✅' : '⏳');
                            @endphp
                            <span class="{{ $claseEstado }}">{{ $iconoEstado }} {{ ucfirst($estado) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Resumen por Galpón -->
            @if($data->groupBy('facility_name')->count() > 1)
            <div class="page-break"></div>
            <div class="info-section">
                <h3>📊 RESUMEN POR GALPÓN</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Galpón</th>
                            <th>Total Costos</th>
                            <th>Registros</th>
                            <th>Costo Promedio</th>
                            <th>Costo Más Alto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->groupBy('facility_name') as $galpon => $registros)
                        <tr>
                            <td>{{ $galpon }}</td>
                            <td class="text-right text-danger">
                                <strong>${{ number_format($registros->sum('total_cost'), 2) }}</strong>
                            </td>
                            <td class="text-center">{{ $registros->count() }}</td>
                            <td class="text-right">${{ number_format($registros->avg('total_cost'), 2) }}</td>
                            <td class="text-right">${{ number_format($registros->max('total_cost'), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Resumen por Tipo de Costo -->
            <div class="info-section">
                <h3>📈 RESUMEN POR TIPO DE COSTO</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tipo de Costo</th>
                            <th>Total Invertido</th>
                            <th>Registros</th>
                            <th>Porcentaje del Total</th>
                            <th>Costo Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->groupBy('cost_type') as $tipo => $registros)
                        @php
                            $totalGeneral = $data->sum('total_cost');
                            $totalTipo = $registros->sum('total_cost');
                            $porcentaje = $totalGeneral > 0 ? ($totalTipo / $totalGeneral) * 100 : 0;
                            
                            $tiposCosto = [
                                'batch' => 'Por Lote',
                                'monthly' => 'Mensual',
                                'weekly' => 'Semanal',
                                'daily' => 'Diario',
                                'feed' => 'Alimentación',
                                'medical' => 'Médico',
                                'maintenance' => 'Mantenimiento'
                            ];
                            $tipoNombre = $tiposCosto[$tipo] ?? ucfirst($tipo);
                        @endphp
                        <tr>
                            <td>{{ $tipoNombre }}</td>
                            <td class="text-right text-danger">
                                <strong>${{ number_format($totalTipo, 2) }}</strong>
                            </td>
                            <td class="text-center">{{ $registros->count() }}</td>
                            <td class="text-right">{{ number_format($porcentaje, 1) }}%</td>
                            <td class="text-right">${{ number_format($registros->avg('total_cost'), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Análisis Temporal -->
            <div class="info-section">
                <h3>📅 ANÁLISIS TEMPORAL DE COSTOS</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Total Costos</th>
                            <th>Registros</th>
                            <th>Costo Promedio Diario</th>
                            <th>Tendencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $costosPorMes = $data->groupBy(function($item) {
                                return \Carbon\Carbon::parse($item['created_at'])->format('Y-m');
                            });
                        @endphp
                        @foreach($costosPorMes as $mes => $registros)
                        @php
                            $fechaMes = \Carbon\Carbon::parse($mes . '-01');
                            $diasEnMes = $fechaMes->daysInMonth;
                            $totalMes = $registros->sum('total_cost');
                            $promedioDiario = $totalMes / $diasEnMes;
                        @endphp
                        <tr>
                            <td>{{ $fechaMes->format('F Y') }}</td>
                            <td class="text-right text-danger">
                                <strong>${{ number_format($totalMes, 2) }}</strong>
                            </td>
                            <td class="text-center">{{ $registros->count() }}</td>
                            <td class="text-right">${{ number_format($promedioDiario, 2) }}</td>
                            <td class="text-center">
                                @if($loop->index > 0)
                                    @php
                                        $mesAnterior = $costosPorMes->slice($loop->index - 1, 1)->first();
                                        $totalAnterior = $mesAnterior ? $mesAnterior->sum('total_cost') : 0;
                                        $cambio = $totalAnterior > 0 ? (($totalMes - $totalAnterior) / $totalAnterior) * 100 : 0;
                                    @endphp
                                    @if($cambio > 5)
                                        <span class="text-danger">📈 +{{ number_format($cambio, 1) }}%</span>
                                    @elseif($cambio < -5)
                                        <span class="text-success">📉 {{ number_format($cambio, 1) }}%</span>
                                    @else
                                        <span class="text-info">➡️ {{ number_format($cambio, 1) }}%</span>
                                    @endif
                                @else
                                    <span class="text-info">--</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-data">
                <p>📝 No hay registros de costos de producción disponibles para mostrar.</p>
                <p style="margin-top: 10px; font-size: 9px;">Asegúrese de que existan datos de costos en el sistema.</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>AVICONTROL</strong> - Sistema de Gestión Avícola | 
            Reporte generado el {{ $generatedAt }} | 
            Página <span class="pagenum"></span>
        </p>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("DejaVu Sans", "normal");
                $size = 8;
                $pageText = "Página " . $PAGE_NUM . " de " . $PAGE_COUNT;
                $y = $pdf->get_height() - 35;
                $x = $pdf->get_width() - 100;
                $pdf->text($x, $y, $pageText, $font, $size);
            ');
        }
    </script>
</body>
</html>
