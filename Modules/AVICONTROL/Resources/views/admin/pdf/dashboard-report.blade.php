<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard AVICONTROL - Reporte</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, #4d7c0f 0%, #65a30d 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #4d7c0f;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #4d7c0f;
        }
        
        .production-chart {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .chart-data {
            display: flex;
            justify-content: space-between;
            align-items: end;
            height: 150px;
            margin: 20px 0;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 10px;
        }
        
        .chart-bar {
            background: #4d7c0f;
            color: white;
            padding: 5px;
            text-align: center;
            font-size: 10px;
            border-radius: 4px 4px 0 0;
            min-height: 30px;
            display: flex;
            align-items: end;
            justify-content: center;
            flex: 1;
            margin: 0 2px;
        }
        
        .chart-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        
        .chart-label {
            font-size: 10px;
            color: #64748b;
            text-align: center;
            flex: 1;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th,
        .table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }
        
        .table th {
            background: #f1f5f9;
            font-weight: bold;
            color: #1e293b;
        }
        
        .table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .alert {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
        }
        
        .alert-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 5px;
        }
        
        .alert-message {
            color: #78350f;
            font-size: 11px;
        }
        
        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .footer {
            text-align: center;
            color: #64748b;
            font-size: 10px;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }
        
        .no-data {
            text-align: center;
            color: #64748b;
            font-style: italic;
            padding: 20px;
        }
        
        .highlight {
            background: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>🐔 AVICONTROL - Dashboard</h1>
        <p>Reporte de Estadísticas y Producción Avícola</p>
        <p>Generado el: {{ $generatedAt }}</p>
    </div>

    <!-- Estadísticas Principales -->
    <div class="section">
        <h2 class="section-title">📊 Estadísticas Principales</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['instalaciones_activas'] }}</div>
                <div class="stat-label">Instalaciones Activas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($estadisticas['produccion_diaria']) }}</div>
                <div class="stat-label">Producción Diaria</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['tasa_postura'] }}%</div>
                <div class="stat-label">Tasa de Postura</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $estadisticas['alertas_pendientes'] }}</div>
                <div class="stat-label">Alertas Pendientes</div>
            </div>
        </div>
    </div>

    <!-- Gráfica de Producción -->
    <div class="section">
        <h2 class="section-title">📈 Producción de los Últimos 7 Días</h2>
        <div class="production-chart">
            <div class="chart-data">
                @foreach($datosProduccion['data'] as $index => $valor)
                    @php
                        $maxValue = max($datosProduccion['data']) ?: 1;
                        $height = $maxValue > 0 ? ($valor / $maxValue) * 120 + 30 : 30;
                    @endphp
                    <div class="chart-bar" style="height: {{ $height }}px;">
                        {{ number_format($valor) }}
                    </div>
                @endforeach
            </div>
            <div class="chart-labels">
                @foreach($datosProduccion['labels'] as $label)
                    <div class="chart-label">{{ $label }}</div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Dos columnas: Top Galpones y Producción por Tipo -->
    <div class="two-columns">
        <!-- Top Galpones -->
        <div class="section">
            <h2 class="section-title">🏆 Top Galpones Productivos (30 días)</h2>
            @if($datosAdicionales['top_galpones']->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Galpón</th>
                            <th>Producción Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datosAdicionales['top_galpones'] as $galpon)
                            <tr>
                                <td>{{ $galpon->name }}</td>
                                <td class="highlight">{{ number_format($galpon->total_produccion) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">No hay datos de producción disponibles</div>
            @endif
        </div>

        <!-- Producción por Tipo -->
        <div class="section">
            <h2 class="section-title">🥚 Producción por Tipo (30 días)</h2>
            @if($datosAdicionales['produccion_por_tipo']->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($datosAdicionales['produccion_por_tipo'] as $tipo)
                            <tr>
                                <td>{{ ucfirst($tipo->tipo_produccion) }}</td>
                                <td class="highlight">{{ number_format($tipo->total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">No hay datos de producción por tipo</div>
            @endif
        </div>
    </div>

    <!-- Resumen de Inventario -->
    <div class="section">
        <h2 class="section-title">📦 Resumen de Inventario</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $datosAdicionales['inventario_resumen']['productos_totales'] }}</div>
                <div class="stat-label">Productos Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $datosAdicionales['inventario_resumen']['productos_bajo_stock'] }}</div>
                <div class="stat-label">Productos Bajo Stock</div>
            </div>
        </div>
    </div>

    <!-- Alertas del Sistema -->
    <div class="section">
        <h2 class="section-title">⚠️ Alertas del Sistema</h2>
        @if(count($dashboardAlerts) > 0)
            @foreach($dashboardAlerts as $alert)
                <div class="alert">
                    <div class="alert-title">{{ $alert['title'] }}</div>
                    <div class="alert-message">{{ $alert['message'] }}</div>
                </div>
            @endforeach
        @else
            <div class="no-data">✅ No hay alertas activas. El sistema está funcionando correctamente.</div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>AVICONTROL - Sistema de Gestión Avícola | Generado automáticamente el {{ $generatedAt }}</p>
        <p>Este reporte contiene información confidencial y está destinado únicamente para uso interno.</p>
    </div>
</body>
</html>
