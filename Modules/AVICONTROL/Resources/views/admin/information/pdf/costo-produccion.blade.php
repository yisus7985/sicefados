<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Costo de Producción #{{ $cost->id ?? '' }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; margin: 0; }
        .subtitle { font-size: 12px; color: #666; margin: 3px 0 0 0; }
        .meta { font-size: 11px; color: #555; margin-top: 8px; text-align: right; }
        .grid { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .grid th, .grid td { border: 1px solid #ddd; padding: 6px 8px; }
        .grid th { background: #f0f2f5; text-transform: uppercase; font-size: 11px; letter-spacing: 0.3px; text-align: left; }
        .label { width: 30%; font-weight: bold; background: #fafafa; }
        .text-right { text-align: right; }
        .small { font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Costo de Producción #{{ $cost->id }}</p>
        <p class="subtitle">Detalle del registro</p>
    </div>

    <div class="meta">
        Generado: {{ $generatedAt->format('d/m/Y H:i') }}
    </div>

    <table class="grid">
        <tr>
            <th class="label">Galpón</th>
            <td>{{ $cost->facility_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th class="label">Ave (ID)</th>
            <td>{{ $cost->bird_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th class="label">Lote</th>
            <td>{{ $cost->batch_code ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th class="label">Período</th>
            <td>
                @if($cost->period_start && $cost->period_end)
                    {{ \Carbon\Carbon::parse($cost->period_start)->format('d/m/Y') }} -
                    {{ \Carbon\Carbon::parse($cost->period_end)->format('d/m/Y') }}
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th class="label">Tipo de costo</th>
            <td>{{ ($cost->cost_type === 'batch') ? 'Por Lote' : (($cost->cost_type === 'period') ? 'Por Período' : strtoupper($cost->cost_type ?? '')) }}</td>
        </tr>
        <tr>
            <th class="label">Costo Total</th>
            <td class="text-right">${{ number_format($cost->total_cost ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th class="label">Costo por Unidad</th>
            <td class="text-right">{{ is_null($cost->cost_per_unit) ? 'N/A' : '$'.number_format($cost->cost_per_unit, 2) }}</td>
        </tr>
        <tr>
            <th class="label">Unidad</th>
            <td>{{ $cost->unit_type === 'egg' ? 'Por Huevo' : ($cost->unit_type === 'kg_meat' ? 'Por Kg' : ($cost->unit_type === 'bird' ? 'Por Ave' : ($cost->unit_type ?? ''))) }}</td>
        </tr>
        <tr>
            <th class="label">Estado</th>
            <td>{{ $cost->status === 'draft' ? 'Borrador' : ($cost->status === 'confirmed' ? 'Confirmado' : ($cost->status === 'cancelled' ? 'Cancelado' : strtoupper($cost->status ?? ''))) }}</td>
        </tr>
        <tr>
            <th class="label">Creado</th>
            <td>{{ isset($cost->created_at) ? \Carbon\Carbon::parse($cost->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
        </tr>
        <tr>
            <th class="label">Actualizado</th>
            <td>{{ isset($cost->updated_at) ? \Carbon\Carbon::parse($cost->updated_at)->format('d/m/Y H:i') : 'N/A' }}</td>
        </tr>
    </table>

    <br>
    <div class="header" style="text-align:left; margin-top: 10px;">
        <p class="title" style="font-size:14px;">Desglose de Costos</p>
    </div>

    <table class="grid">
        <tr>
            <th class="label">Concentrado</th>
            <td class="text-right">${{ number_format($cost->concentrate_cost ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th class="label">Agua</th>
            <td class="text-right">${{ number_format($cost->water_cost ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th class="label">Energía</th>
            <td class="text-right">${{ number_format($cost->energy_cost ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th class="label">Medicamentos</th>
            <td class="text-right">${{ number_format($cost->medication_cost ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th class="label">Biológicos</th>
            <td class="text-right">${{ number_format($cost->biological_cost ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th class="label">Empaque</th>
            <td class="text-right">${{ number_format($cost->packaging_cost ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th class="label">Mano de obra</th>
            <td class="text-right">${{ number_format($cost->labor_cost ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th class="label">Mantenimiento</th>
            <td class="text-right">${{ number_format($cost->maintenance_cost ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th class="label">Otros</th>
            <td class="text-right">${{ number_format($cost->other_costs ?? 0, 2) }}</td>
        </tr>
    </table>

    @if(!empty($cost->notes))
        <br>
        <div class="header" style="text-align:left; margin-top: 10px;">
            <p class="title" style="font-size:14px;">Notas</p>
        </div>
        <table class="grid">
            <tr>
                <td>{{ $cost->notes }}</td>
            </tr>
        </table>
    @endif

    <p class="small">Este documento fue generado automáticamente y resume la información registrada para este costo de producción.</p>
</body>
</html>
