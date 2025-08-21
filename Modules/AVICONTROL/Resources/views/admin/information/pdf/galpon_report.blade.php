<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Galpón</title>
    <style>
        body { font-family: sans-serif; margin: 20px; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 26px; color: #2c3e50; }
        .header p { margin: 5px 0; font-size: 14px; color: #7f8c8d; }
        .card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 20px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .card h2 { font-size: 18px; color: #3498db; margin-top: 0; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 8px;}
        .grid-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .detail-item { background-color: #f9f9f9; padding: 10px; border-radius: 5px; }
        .detail-label { font-weight: bold; display: block; color: #555; font-size: 12px; margin-bottom: 4px; }
        .detail-value { font-size: 15px; color: #333; }
        .description { grid-column: span 3; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 9px; text-align: left; }
        th { background-color: #3498db; color: white; font-size: 12px; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #f2f9ff; }
        .footer { text-align: center; margin-top: 25px; font-size: 12px; color: #95a5a6; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte del Galpón: {{ $galpon->name }}</h1>
        <p>Fecha de Generación: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="card">
        <h2>Detalles del Galpón</h2>
        <div class="grid-container">
            <div class="detail-item"><span class="detail-label">Dimensiones</span> <span class="detail-value">{{ $galpon->length }}m x {{ $galpon->width }}m x {{ $galpon->height }}m</span></div>
            <div class="detail-item"><span class="detail-label">Área</span> <span class="detail-value">{{ $galpon->area }} m²</span></div>
            <div class="detail-item"><span class="detail-label">Estado</span> <span class="detail-value">{{ $galpon->status }}</span></div>
            <div class="detail-item"><span class="detail-label">Capacidad</span> <span class="detail-value">{{ number_format($galpon->capacity) }} aves</span></div>
            <div class="detail-item"><span class="detail-label">Aves Activas</span> <span class="detail-value">{{ $galpon->total_active_birds }}</span></div>
            <div class="detail-item"><span class="detail-label">Ocupación</span> <span class="detail-value">{{ $galpon->occupancy_percentage }}%</span></div>
            <div class="detail-item description"><span class="detail-label">Descripción</span> <span class="detail-value">{{ $galpon->description ?: 'Sin descripción.' }}</span></div>
        </div>
    </div>

    <div class="card">
        <h2>Aves Registradas en el Galpón</h2>
        <table>
            <thead>
                <tr>
                    <th>Lote</th>
                    <th>Tipo de Ave</th>
                    <th>Raza</th>
                    <th>Cantidad</th>
                    <th>Edad (Semanas)</th>
                    <th>Peso Promedio (kg)</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($galpon->birds as $bird)
                    <tr>
                        <td>{{ $bird->batch_name ?: 'N/A' }}</td>
                        <td>{{ $bird->bird_type_name }}</td>
                        <td>{{ $bird->breed }}</td>
                        <td>{{ $bird->quantity }}</td>
                        <td>{{ $bird->current_age_weeks }}</td>
                        <td>{{ $bird->average_weight }}</td>
                        <td>{{ $bird->status_name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">No hay aves registradas en este galpón.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>SICEFA - Sistema de Información para el Control y Seguimiento de Fincas</p>
    </div>
</body>
</html>
