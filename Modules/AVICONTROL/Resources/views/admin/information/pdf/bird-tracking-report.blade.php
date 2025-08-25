<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Seguimiento de Aves - AVICONTROL</title>
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
            background: linear-gradient(135deg, #007bff, #6610f2);
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
            border-left: 4px solid #007bff;
        }
        
        .info-section h3 {
            font-size: 12px;
            margin-bottom: 8px;
            color: #007bff;
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
            background-color: #e3f2fd;
            border: 1px solid #ddd;
        }
        
        .stats-cell.header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        
        .stats-value {
            font-size: 14px;
            font-weight: bold;
            color: #0056b3;
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
            background-color: #007bff;
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
            background-color: #e3f2fd;
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
            background-color: #f0f8ff;
            border-radius: 5px;
            border: 1px solid #007bff;
        }
        
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        
        .status-active { color: #28a745; }
        .status-inactive { color: #dc3545; }
        .status-sold { color: #ffc107; }
        .status-deceased { color: #6c757d; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🐥 INFORME DE SEGUIMIENTO DE AVES</h1>
        <p>Sistema de Control AVICONTROL - Generado el {{ $generatedAt }}</p>
    </div>

    <!-- Resumen Estadísticas -->
    @if(!empty($estadisticas))
    <div class="summary-section">
        <div class="summary-title">📊 RESUMEN EJECUTIVO DE SEGUIMIENTO</div>
        
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">Total Aves</div>
                <div class="stats-cell header">Peso Promedio</div>
                <div class="stats-cell header">Edad Promedio</div>
                <div class="stats-cell header">Tasa Crecimiento</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['total_aves_seguimiento'] ?? 0) }}</div>
                    <div class="stats-label">En seguimiento</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['promedio_peso'] ?? 0) }} g</div>
                    <div class="stats-label">Peso actual</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['edad_promedio'] ?? 0) }} días</div>
                    <div class="stats-label">Edad media</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['tasa_crecimiento'] ?? 0, 1) }}%</div>
                    <div class="stats-label">Crecimiento</div>
                </div>
            </div>
        </div>

        <div class="stats-grid" style="margin-top: 10px;">
            <div class="stats-row">
                <div class="stats-cell header">Mejor Galpón</div>
                <div class="stats-cell header">Conversión Alimenticia</div>
                <div class="stats-cell header">Mortalidad</div>
                <div class="stats-cell header">Eficiencia</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">{{ $estadisticas['mejor_galpon'] ?? 'N/A' }}</div>
                    <div class="stats-label">Mayor rendimiento</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['conversion_promedio'] ?? 0, 2) }}</div>
                    <div class="stats-label">Promedio general</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['tasa_mortalidad'] ?? 0, 2) }}%</div>
                    <div class="stats-label">Tasa general</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['eficiencia_productiva'] ?? 0, 1) }}%</div>
                    <div class="stats-label">Productividad</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de Datos -->
    <div class="table-container">
        <h3 style="color: #007bff; margin-bottom: 10px;">📋 DETALLE DE SEGUIMIENTO DE AVES</h3>
        
        @if($data && $data->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 8%;">Fecha</th>
                        <th style="width: 12%;">Galpón</th>
                        <th style="width: 10%;">Raza</th>
                        <th style="width: 8%;">Cantidad</th>
                        <th style="width: 8%;">Peso (g)</th>
                        <th style="width: 8%;">Edad (días)</th>
                        <th style="width: 8%;">Estado</th>
                        <th style="width: 8%;">Mortalidad</th>
                        <th style="width: 8%;">Conv. Alim.</th>
                        <th style="width: 8%;">Costo/Ave</th>
                        <th style="width: 14%;">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $registro)
                    <tr>
                        <td>
                            @php
                                $fecha = isset($registro['created_at']) ? \Carbon\Carbon::parse($registro['created_at']) : null;
                            @endphp
                            {{ $fecha ? $fecha->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td>{{ $registro['galpon_nombre'] ?? 'N/A' }}</td>
                        <td>{{ $registro['breed'] ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($registro['quantity'] ?? 0) }}</td>
                        <td class="text-right text-info">{{ number_format($registro['weight'] ?? 0) }}</td>
                        <td class="text-right">
                            @php
                                $edad = 0;
                                if (isset($registro['birth_date']) && isset($registro['created_at'])) {
                                    $fechaNacimiento = \Carbon\Carbon::parse($registro['birth_date']);
                                    $fechaRegistro = \Carbon\Carbon::parse($registro['created_at']);
                                    $edad = $fechaNacimiento->diffInDays($fechaRegistro);
                                }
                            @endphp
                            {{ $edad }}
                        </td>
                        <td class="text-center">
                            @php
                                $estado = $registro['status'] ?? 'active';
                                $claseEstado = 'status-' . $estado;
                            @endphp
                            <span class="{{ $claseEstado }}">
                                @if($estado === 'active') 🟢 Activa
                                @elseif($estado === 'inactive') 🔴 Inactiva
                                @elseif($estado === 'sold') 💰 Vendida
                                @elseif($estado === 'deceased') 💀 Fallecida
                                @else {{ ucfirst($estado) }}
                                @endif
                            </span>
                        </td>
                        <td class="text-right text-danger">
                            @php
                                $tasaMortalidad = 0;
                                if (isset($registro['initial_quantity']) && $registro['initial_quantity'] > 0) {
                                    $muertos = ($registro['initial_quantity'] ?? 0) - ($registro['quantity'] ?? 0);
                                    $tasaMortalidad = ($muertos / $registro['initial_quantity']) * 100;
                                }
                            @endphp
                            {{ number_format($tasaMortalidad, 1) }}%
                        </td>
                        <td class="text-right">{{ number_format($registro['feed_conversion'] ?? 0, 2) }}</td>
                        <td class="text-right text-success">${{ number_format($registro['cost_per_bird'] ?? 0, 2) }}</td>
                        <td>{{ $registro['observations'] ?? 'Sin observaciones' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Resumen por Galpón -->
            @if($data->groupBy('galpon_nombre')->count() > 1)
            <div class="page-break"></div>
            <div class="info-section">
                <h3>📊 RESUMEN POR GALPÓN</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Galpón</th>
                            <th>Total Aves</th>
                            <th>Peso Promedio</th>
                            <th>Edad Promedio</th>
                            <th>Conversión Alimenticia</th>
                            <th>Costo Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->groupBy('galpon_nombre') as $galpon => $registros)
                        <tr>
                            <td>{{ $galpon }}</td>
                            <td class="text-right">{{ number_format($registros->sum('quantity')) }}</td>
                            <td class="text-right">{{ number_format($registros->avg('weight'), 0) }} g</td>
                            <td class="text-right">
                                @php
                                    $edadPromedio = 0;
                                    $count = 0;
                                    foreach($registros as $r) {
                                        if (isset($r['birth_date']) && isset($r['created_at'])) {
                                            $fechaNacimiento = \Carbon\Carbon::parse($r['birth_date']);
                                            $fechaRegistro = \Carbon\Carbon::parse($r['created_at']);
                                            $edadPromedio += $fechaNacimiento->diffInDays($fechaRegistro);
                                            $count++;
                                        }
                                    }
                                    $edadPromedio = $count > 0 ? $edadPromedio / $count : 0;
                                @endphp
                                {{ number_format($edadPromedio, 0) }} días
                            </td>
                            <td class="text-right">{{ number_format($registros->avg('feed_conversion'), 2) }}</td>
                            <td class="text-right">
                                ${{ number_format($registros->sum(function($r) { return ($r['quantity'] ?? 0) * ($r['cost_per_bird'] ?? 0); }), 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Resumen por Estado -->
            <div class="info-section">
                <h3>📈 RESUMEN POR ESTADO</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Total Aves</th>
                            <th>Porcentaje</th>
                            <th>Peso Promedio</th>
                            <th>Costo Promedio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->groupBy('status') as $estado => $registros)
                        @php
                            $totalAves = $data->sum('quantity');
                            $avesPorEstado = $registros->sum('quantity');
                            $porcentaje = $totalAves > 0 ? ($avesPorEstado / $totalAves) * 100 : 0;
                        @endphp
                        <tr>
                            <td>
                                @if($estado === 'active') 🟢 Activas
                                @elseif($estado === 'inactive') 🔴 Inactivas
                                @elseif($estado === 'sold') 💰 Vendidas
                                @elseif($estado === 'deceased') 💀 Fallecidas
                                @else {{ ucfirst($estado) }}
                                @endif
                            </td>
                            <td class="text-right">{{ number_format($avesPorEstado) }}</td>
                            <td class="text-right">{{ number_format($porcentaje, 1) }}%</td>
                            <td class="text-right">{{ number_format($registros->avg('weight'), 0) }} g</td>
                            <td class="text-right">${{ number_format($registros->avg('cost_per_bird'), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-data">
                <p>📝 No hay registros de seguimiento de aves disponibles para mostrar.</p>
                <p style="margin-top: 10px; font-size: 9px;">Asegúrese de que existan datos de seguimiento en el sistema.</p>
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
