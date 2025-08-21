<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Galpones</title>
    <style>
        body { font-family: sans-serif; margin: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #2c3e50; }
        .header p { margin: 5px 0; font-size: 14px; color: #7f8c8d; }
        .galpon-card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 20px; background-color: #ffffff; page-break-inside: avoid; }
        .galpon-card h2 { font-size: 18px; color: #3498db; margin-top: 0; margin-bottom: 10px; border-bottom: 2px solid #3498db; padding-bottom: 5px; }
        .grid-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 10px; }
        .detail-item { background-color: #f9f9f9; padding: 8px; border-radius: 5px; }
        .detail-label { font-weight: bold; display: block; color: #555; font-size: 11px; margin-bottom: 3px; }
        .detail-value { font-size: 13px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 7px; text-align: left; }
        th { background-color: #3498db; color: white; font-size: 11px; text-transform: uppercase; }
        .footer { text-align: center; margin-top: 25px; font-size: 12px; color: #95a5a6; }
        .no-records { text-align: center; padding: 20px; font-style: italic; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte General de Galpones</h1>
        @if(isset($startDate) && isset($endDate))
            <p>Periodo del {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        @else
            <p>Reporte Completo</p>
        @endif
    </div>

    @forelse($galpones as $galpon)
        <div class="galpon-card">
            <h2>{{ $galpon->name }}</h2>
            <div class="grid-container">
                <div class="detail-item"><span class="detail-label">Capacidad</span> <span class="detail-value">{{ number_format($galpon->capacity) }} aves</span></div>
                <div class="detail-item"><span class="detail-label">Aves Activas</span> <span class="detail-value">{{ $galpon->total_active_birds }}</span></div>
                <div class="detail-item"><span class="detail-label">Ocupación</span> <span class="detail-value">{{ $galpon->occupancy_percentage }}%</span></div>
            </div>
            
            <h4>Aves Registradas</h4>
            <table>
                <thead>
                    <tr>
                        <th>Lote</th>
                        <th>Tipo</th>
                        <th>Raza</th>
                        <th>Cantidad</th>
                        <th>Edad (Semanas)</th>
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
                            <td>{{ $bird->status_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">No hay aves registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <div class="no-records">
            <p>No se encontraron galpones para el criterio seleccionado.</p>
        </div>
    @endforelse

    <div class="footer">
        <p>SICEFA - Sistema de Información para el Control y Seguimiento de Fincas</p>
    </div>
</body>
</html>
