<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Costos de Producción - Reporte</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; margin: 0; }
        .subtitle { font-size: 12px; color: #666; margin: 3px 0 0 0; }
        .meta { font-size: 11px; color: #555; margin-top: 8px; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { background: #f0f2f5; text-transform: uppercase; font-size: 11px; letter-spacing: 0.3px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .small { font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Reporte de Costos de Producción</p>
        <p class="subtitle">Listado completo de registros</p>
    </div>

    <div class="meta">
        Generado: {{ $generatedAt->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Galpón</th>
                <th>Lote</th>
                <th>Período</th>
                <th>Tipo</th>
                <th class="text-right">Costo Total</th>
                <th class="text-right">Costo/Unidad</th>
                <th>Unidad</th>
                <th>Estado</th>
                <th>Creado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productionCosts as $cost)
                <tr>
                    <td class="text-center">{{ $cost->id }}</td>
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
                    <td>{{ ($cost->cost_type === 'batch') ? 'Por Lote' : (($cost->cost_type === 'period') ? 'Por Período' : strtoupper($cost->cost_type ?? '')) }}</td>
                    <td class="text-right">${{ number_format($cost->total_cost ?? 0, 2) }}</td>
                    <td class="text-right">{{ is_null($cost->cost_per_unit) ? 'N/A' : '$'.number_format($cost->cost_per_unit, 2) }}</td>
                    <td>{{ $cost->unit_type === 'egg' ? 'Por Huevo' : ($cost->unit_type === 'kg_meat' ? 'Por Kg' : ($cost->unit_type === 'bird' ? 'Por Ave' : ($cost->unit_type ?? ''))) }}</td>
                    <td>{{ $cost->status === 'draft' ? 'Borrador' : ($cost->status === 'confirmed' ? 'Confirmado' : ($cost->status === 'cancelled' ? 'Cancelado' : strtoupper($cost->status ?? ''))) }}</td>
                    <td>{{ isset($cost->created_at) ? \Carbon\Carbon::parse($cost->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center small">No hay datos para mostrar</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
