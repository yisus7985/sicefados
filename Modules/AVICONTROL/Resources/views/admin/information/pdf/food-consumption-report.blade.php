<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Consumo de Alimentos - AVICONTROL</title>
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
            background: linear-gradient(135deg, #28a745, #20c997);
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
            border-left: 4px solid #28a745;
        }
        
        .info-section h3 {
            font-size: 12px;
            margin-bottom: 8px;
            color: #28a745;
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
            background-color: #e8f5e8;
            border: 1px solid #ddd;
        }
        
        .stats-cell.header {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        
        .stats-value {
            font-size: 14px;
            font-weight: bold;
            color: #155724;
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
            background-color: #28a745;
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
            background-color: #e8f5e8;
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
            background-color: #f0f8f0;
            border-radius: 5px;
            border: 1px solid #28a745;
        }
        
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🍽️ INFORME DE CONSUMO DE ALIMENTOS</h1>
        <p>Sistema de Control AVICONTROL - Generado el {{ $generatedAt }}</p>
    </div>

    <!-- Resumen Estadísticas -->
    @if(!empty($estadisticas))
    <div class="summary-section">
        <div class="summary-title">📊 RESUMEN EJECUTIVO DE CONSUMO</div>
        
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">Total Gastado</div>
                <div class="stats-cell header">Promedio por Galpón</div>
                <div class="stats-cell header">Total Kilos</div>
                <div class="stats-cell header">Promedio Diario</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">${{ number_format($estadisticas['total_gasto'] ?? 0, 2) }}</div>
                    <div class="stats-label">Inversión total</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">${{ number_format($estadisticas['promedio_galpon'] ?? 0, 2) }}</div>
                    <div class="stats-label">Por instalación</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['total_kilos'] ?? 0) }} kg</div>
                    <div class="stats-label">Consumo total</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['promedio_diario'] ?? 0, 2) }} kg</div>
                    <div class="stats-label">Consumo diario</div>
                </div>
            </div>
        </div>

        <div class="stats-grid" style="margin-top: 10px;">
            <div class="stats-row">
                <div class="stats-cell header">Mejor Galpón</div>
                <div class="stats-cell header">Producto Más Consumido</div>
                <div class="stats-cell header">Aves Alimentadas</div>
                <div class="stats-cell header">Eficiencia</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">{{ $estadisticas['mejor_galpon'] ?? 'N/A' }}</div>
                    <div class="stats-label">Mayor consumo</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $estadisticas['producto_mas_consumido'] ?? 'N/A' }}</div>
                    <div class="stats-label">Más demandado</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['total_aves'] ?? 0) }}</div>
                    <div class="stats-label">Aves activas</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['eficiencia_consumo'] ?? 0, 2) }}g</div>
                    <div class="stats-label">Consumo por ave</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de Datos -->
    <div class="table-container">
        <h3 style="color: #28a745; margin-bottom: 10px;">📋 DETALLE DE CONSUMO DE ALIMENTOS</h3>
        
        @if($data && $data->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 10%;">Fecha</th>
                        <th style="width: 12%;">Galpón</th>
                        <th style="width: 15%;">Producto</th>
                        <th style="width: 8%;">Cantidad (kg)</th>
                        <th style="width: 8%;">Bultos</th>
                        <th style="width: 8%;">Peso/Bulto</th>
                        <th style="width: 8%;">Nº Aves</th>
                        <th style="width: 8%;">g/Ave</th>
                        <th style="width: 10%;">Costo Total</th>
                        <th style="width: 13%;">Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $registro)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($registro['fecha_registro'])->format('d/m/Y') }}</td>
                        <td>{{ $registro['galpon_nombre'] ?? 'N/A' }}</td>
                        <td>{{ $registro['producto_nombre'] ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($registro['cantidad_kg'] ?? 0, 2) }}</td>
                        <td class="text-right">{{ $registro['cantidad_bultos'] ?? 0 }}</td>
                        <td class="text-right">{{ number_format($registro['peso_por_bulto'] ?? 0, 2) }}</td>
                        <td class="text-right">{{ number_format($registro['numero_aves'] ?? 0) }}</td>
                        <td class="text-right text-info">
                            @php
                                $consumoPorAve = 0;
                                if (($registro['numero_aves'] ?? 0) > 0) {
                                    $consumoPorAve = ($registro['cantidad_kg'] ?? 0) / $registro['numero_aves'] * 1000;
                                }
                            @endphp
                            {{ number_format($consumoPorAve, 2) }}
                        </td>
                        <td class="text-right text-success">
                            ${{ number_format(($registro['cantidad_kg'] ?? 0) * ($registro['precio_por_kg'] ?? 0), 2) }}
                        </td>
                        <td>{{ $registro['responsable'] ?? 'N/A' }}</td>
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
                            <th>Total Consumido (kg)</th>
                            <th>Registros</th>
                            <th>Costo Total</th>
                            <th>Promedio por Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->groupBy('galpon_nombre') as $galpon => $registros)
                        <tr>
                            <td>{{ $galpon }}</td>
                            <td class="text-right">{{ number_format($registros->sum('cantidad_kg'), 2) }} kg</td>
                            <td class="text-center">{{ $registros->count() }}</td>
                            <td class="text-right">
                                ${{ number_format($registros->sum(function($r) { return ($r['cantidad_kg'] ?? 0) * ($r['precio_por_kg'] ?? 0); }), 2) }}
                            </td>
                            <td class="text-right">{{ number_format($registros->avg('cantidad_kg'), 2) }} kg</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Resumen por Producto -->
            <div class="info-section">
                <h3>📦 RESUMEN POR PRODUCTO</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Total Consumido (kg)</th>
                            <th>Registros</th>
                            <th>Costo Total</th>
                            <th>Precio Promedio/kg</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->groupBy('producto_nombre') as $producto => $registros)
                        <tr>
                            <td>{{ $producto }}</td>
                            <td class="text-right">{{ number_format($registros->sum('cantidad_kg'), 2) }} kg</td>
                            <td class="text-center">{{ $registros->count() }}</td>
                            <td class="text-right">
                                ${{ number_format($registros->sum(function($r) { return ($r['cantidad_kg'] ?? 0) * ($r['precio_por_kg'] ?? 0); }), 2) }}
                            </td>
                            <td class="text-right">${{ number_format($registros->avg('precio_por_kg'), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-data">
                <p>📝 No hay registros de consumo de alimentos disponibles para mostrar.</p>
                <p style="margin-top: 10px; font-size: 9px;">Asegúrese de que existan datos de consumo en el sistema.</p>
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
