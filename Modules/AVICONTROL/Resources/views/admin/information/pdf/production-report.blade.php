<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Producción - AVICONTROL</title>
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
            background: linear-gradient(135deg, #4472C4, #70AD47);
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
            border-left: 4px solid #4472C4;
        }
        
        .info-section h3 {
            font-size: 12px;
            margin-bottom: 8px;
            color: #4472C4;
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
            width: 33.33%;
            padding: 8px;
            text-align: center;
            background-color: #e7f3ff;
            border: 1px solid #ddd;
        }
        
        .stats-cell.header {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
        }
        
        .stats-value {
            font-size: 14px;
            font-weight: bold;
            color: #2c5aa0;
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
            background-color: #4472C4;
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
            border: 1px solid #4472C4;
        }
        
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #4472C4;
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
        <h1>📊 INFORME DE PRODUCCIÓN AVÍCOLA</h1>
        <p>Sistema de Control AVICONTROL - Generado el {{ $generatedAt }}</p>
    </div>

    <!-- Resumen Estadísticas -->
    @if(!empty($estadisticas))
    <div class="summary-section">
        <div class="summary-title">📈 RESUMEN EJECUTIVO DE PRODUCCIÓN</div>
        
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">Total Producción</div>
                <div class="stats-cell header">Producción Hoy</div>
                <div class="stats-cell header">Producción del Mes</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['total_produccion'] ?? 0) }}</div>
                    <div class="stats-label">Unidades totales</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['produccion_hoy'] ?? 0) }}</div>
                    <div class="stats-label">Unidades hoy</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['produccion_mes'] ?? 0) }}</div>
                    <div class="stats-label">Unidades este mes</div>
                </div>
            </div>
        </div>

        <div class="stats-grid" style="margin-top: 10px;">
            <div class="stats-row">
                <div class="stats-cell header">Promedio Diario</div>
                <div class="stats-cell header">Mejor Galpón</div>
                <div class="stats-cell header">Aves Activas</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['promedio_diario'] ?? 0) }}</div>
                    <div class="stats-label">Unidades/día</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ $estadisticas['mejor_galpon'] ?? 'N/A' }}</div>
                    <div class="stats-label">Mayor productividad</div>
                </div>
                <div class="stats-cell">
                    <div class="stats-value">{{ number_format($estadisticas['total_aves_activas'] ?? 0) }}</div>
                    <div class="stats-label">Aves en producción</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Calidad del Día -->
    <div class="info-section">
        <h3>🥚 CALIDAD DE PRODUCCIÓN DEL DÍA</h3>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell header">Huevos Buenos</div>
                <div class="stats-cell header">Huevos Rotos</div>
                <div class="stats-cell header">Huevos Sucios</div>
                <div class="stats-cell header">Valor Total</div>
            </div>
            <div class="stats-row">
                <div class="stats-cell text-success">{{ number_format($estadisticas['huevos_buenos_hoy'] ?? 0) }}</div>
                <div class="stats-cell text-danger">{{ number_format($estadisticas['huevos_rotos_hoy'] ?? 0) }}</div>
                <div class="stats-cell text-warning">{{ number_format($estadisticas['huevos_sucios_hoy'] ?? 0) }}</div>
                <div class="stats-cell">${{ number_format($estadisticas['valor_total_hoy'] ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de Datos -->
    <div class="table-container">
        <h3 style="color: #4472C4; margin-bottom: 10px;">📋 DETALLE DE REGISTROS DE PRODUCCIÓN</h3>
        
        @if($data && $data->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 8%;">Fecha</th>
                        <th style="width: 10%;">Tipo Prod.</th>
                        <th style="width: 10%;">Galpón</th>
                        <th style="width: 6%;">Tipo</th>
                        <th style="width: 8%;">Cantidad</th>
                        <th style="width: 7%;">Mortalidad</th>
                        <th style="width: 8%;">H. Buenos</th>
                        <th style="width: 7%;">H. Rotos</th>
                        <th style="width: 7%;">H. Sucios</th>
                        <th style="width: 7%;">% Prod.</th>
                        <th style="width: 8%;">Valor Total</th>
                        <th style="width: 14%;">Destino</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $registro)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($registro['fecha'])->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($registro['tipo_produccion'] ?? 'huevos') }}</td>
                        <td>{{ $registro['galpon'] ?? 'N/A' }}</td>
                        <td>{{ $registro['tipo'] ?? 'N/A' }}</td>
                        <td class="text-right">{{ number_format($registro['cantidad'] ?? 0) }}</td>
                        <td class="text-right text-danger">{{ $registro['mortalidad_aves'] ?? 0 }}</td>
                        <td class="text-right text-success">{{ number_format($registro['huevos_buenos'] ?? 0) }}</td>
                        <td class="text-right text-danger">{{ number_format($registro['huevos_rotos'] ?? 0) }}</td>
                        <td class="text-right text-warning">{{ number_format($registro['huevos_sucios'] ?? 0) }}</td>
                        <td class="text-right">{{ number_format($registro['porcentaje'] ?? 0, 1) }}%</td>
                        <td class="text-right">${{ number_format($registro['valor_total'] ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $registro['destino'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Resumen por Tipo de Producción -->
            @if($data->groupBy('tipo_produccion')->count() > 1)
            <div class="page-break"></div>
            <div class="info-section">
                <h3>📊 RESUMEN POR TIPO DE PRODUCCIÓN</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tipo de Producción</th>
                            <th>Total Cantidad</th>
                            <th>Registros</th>
                            <th>Valor Total</th>
                            <th>Promedio por Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->groupBy('tipo_produccion') as $tipo => $registros)
                        <tr>
                            <td>{{ ucfirst($tipo) }}</td>
                            <td class="text-right">{{ number_format($registros->sum('cantidad')) }}</td>
                            <td class="text-center">{{ $registros->count() }}</td>
                            <td class="text-right">${{ number_format($registros->sum('valor_total'), 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($registros->avg('cantidad'), 1) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Resumen por Galpón -->
            <div class="info-section">
                <h3>🏠 RESUMEN POR GALPÓN</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Galpón</th>
                            <th>Total Producción</th>
                            <th>Registros</th>
                            <th>Promedio Diario</th>
                            <th>Mejor Día</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->groupBy('galpon') as $galpon => $registros)
                        <tr>
                            <td>{{ $galpon }}</td>
                            <td class="text-right">{{ number_format($registros->sum('cantidad')) }}</td>
                            <td class="text-center">{{ $registros->count() }}</td>
                            <td class="text-right">{{ number_format($registros->avg('cantidad'), 1) }}</td>
                            <td class="text-right">{{ number_format($registros->max('cantidad')) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-data">
                <p>📝 No hay registros de producción disponibles para mostrar.</p>
                <p style="margin-top: 10px; font-size: 9px;">Asegúrese de que existan datos de producción en el sistema.</p>
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
