<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Producción - AVICONTROL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #4d7c0f;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #4d7c0f;
            margin: 0;
            font-size: 24px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background-color: #4d7c0f;
            color: white;
            padding: 10px;
            text-align: left;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>AVICONTROL</h1>
        <p>Sistema de Gestión Avícola</p>
        <p><strong>Reporte de Producción de Huevos</strong></p>
        @if($semana)
            <p>Semana: {{ $semana }}</p>
        @else
            <p>Reporte General</p>
        @endif
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <h2>Estadísticas Generales</h2>
    <table>
        <tr>
            <td><strong>Total Huevos:</strong></td>
            <td>{{ number_format($stats['total_huevos'] ?? 0) }}</td>
            <td><strong>Valor Total:</strong></td>
            <td>${{ number_format($stats['valor_total'] ?? 0, 0) }}</td>
        </tr>
        <tr>
            <td><strong>Promedio por Día:</strong></td>
            <td>{{ number_format($stats['promedio_por_dia'] ?? 0, 0) }}</td>
            <td><strong>Tipos Disponibles:</strong></td>
            <td>{{ $stats['tipos_disponibles'] ?? 0 }}</td>
        </tr>
    </table>

    <h2>Detalle de Producción</h2>
    @if($productions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Valor/Unidad</th>
                    <th>Valor Total</th>
                    <th>Destino</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productions as $production)
                <tr>
                    <td>{{ $production->fecha ? $production->fecha->format('d/m/Y') : 'N/A' }}</td>
                    <td>Tipo {{ $production->tipo }}</td>
                    <td class="text-center">{{ number_format($production->cantidad) }}</td>
                    <td class="text-right">${{ number_format($production->valor_unidad, 0) }}</td>
                    <td class="text-right">${{ number_format($production->valor_total, 0) }}</td>
                    <td>{{ $production->destino ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay registros de producción</p>
    @endif

    <div style="margin-top: 40px; text-align: center; border-top: 1px solid #dee2e6; padding-top: 20px; color: #666; font-size: 10px;">
        <p>Este reporte fue generado automáticamente por el sistema AVICONTROL</p>
    </div>
</body>
</html>
