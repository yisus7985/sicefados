<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe Alimento {{ $alimento->id ?? 'N/A' }}</title>
    <style>
        @page {
            margin: 2cm;
            size: A4 portrait;
        }
        
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        
        .header {
            text-align: center;
            border-bottom: 2pt solid #007bff;
            padding-bottom: 15pt;
            margin-bottom: 20pt;
        }
        
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 12pt;
            margin-top: 5pt;
        }
        
        .info-section {
            margin-bottom: 20pt;
            page-break-inside: avoid;
        }
        
        .info-section h2 {
            color: #007bff;
            font-size: 14pt;
            border-bottom: 1pt solid #ddd;
            padding-bottom: 5pt;
            margin-bottom: 10pt;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15pt;
        }
        
        .info-table th,
        .info-table td {
            border: 1pt solid #ddd;
            padding: 8pt;
            text-align: left;
            font-size: 9pt;
        }
        
        .info-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 35%;
        }
        
        .info-table td {
            width: 65%;
        }
        
        .footer {
            margin-top: 30pt;
            text-align: center;
            font-size: 8pt;
            color: #666;
            border-top: 1pt solid #ddd;
            padding-top: 15pt;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4pt 8pt;
            border-radius: 4pt;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-active {
            background-color: #28a745;
            color: white;
        }
        
        .status-cancelled {
            background-color: #dc3545;
            color: white;
        }
        
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        
        .summary-box {
            background-color: #e9ecef;
            border: 1pt solid #dee2e6;
            border-radius: 6pt;
            padding: 12pt;
            margin: 15pt 0;
        }
        
        .summary-box h3 {
            margin-top: 0;
            color: #495057;
            font-size: 12pt;
        }
        
        .summary-grid {
            width: 100%;
            margin-top: 10pt;
        }
        
        .summary-item {
            display: inline-block;
            text-align: center;
            padding: 8pt;
            background-color: white;
            border: 1pt solid #dee2e6;
            width: 30%;
            margin: 0 1%;
            vertical-align: top;
        }
        
        .summary-item .value {
            font-size: 14pt;
            font-weight: bold;
            color: #007bff;
        }
        
        .summary-item .label {
            font-size: 8pt;
            color: #666;
            margin-top: 3pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INFORME DE ALIMENTO - AVICONTROL</h1>
        <div class="subtitle">Sistema de Control Avícola</div>
    </div>

    <div class="info-section">
        <h2>Información del Registro</h2>
        <table class="info-table">
            <tr>
                <th>ID del Registro:</th>
                <td><strong>{{ $alimento->id ?? 'N/A' }}</strong></td>
            </tr>
            <tr>
                <th>Fecha de Registro:</th>
                <td>{{ $alimento->fecha_registro ? \Carbon\Carbon::parse($alimento->fecha_registro)->format('d/m/Y H:i:s') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Estado:</th>
                <td>
                    @php
                        $estado = $alimento->estado ?? 'active';
                        $estadoTexto = $estado === 'active' ? 'Activo' : ($estado === 'cancelled' ? 'Cancelado' : 'Pendiente');
                        $estadoClase = 'status-' . $estado;
                    @endphp
                    <span class="status-badge {{ $estadoClase }}">
                        {{ $estadoTexto }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <h2>Información del Galpón</h2>
        <table class="info-table">
            <tr>
                <th>Nombre del Galpón:</th>
                <td>{{ $alimento->galpon_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>ID del Galpón:</th>
                <td>{{ $alimento->poultry_facility_id ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <h2>Información del Producto</h2>
        <table class="info-table">
            <tr>
                <th>Nombre del Producto:</th>
                <td>{{ $alimento->producto_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>ID del Producto:</th>
                <td>{{ $alimento->product_id ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <h2>Detalles del Consumo</h2>
        <table class="info-table">
            <tr>
                <th>Cantidad (kg):</th>
                <td>{{ number_format($alimento->cantidad_kg ?? 0, 2) }} kg</td>
            </tr>
            <tr>
                <th>Cantidad de Bultos:</th>
                <td>{{ $alimento->cantidad_bultos ?? 0 }}</td>
            </tr>
            <tr>
                <th>Peso por Bulto:</th>
                <td>{{ number_format($alimento->peso_por_bulto ?? 0, 2) }} kg</td>
            </tr>
            <tr>
                <th>Número de Aves:</th>
                <td>{{ number_format($alimento->numero_aves ?? 0) }}</td>
            </tr>
        </table>
    </div>

    <div class="summary-box">
        <h3>Resumen del Consumo</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="value">{{ number_format(($alimento->cantidad_kg ?? 0) + (($alimento->cantidad_bultos ?? 0) * ($alimento->peso_por_bulto ?? 0)), 2) }}</div>
                <div class="label">Total Consumido (kg)</div>
            </div>
            <div class="summary-item">
                <div class="value">{{ number_format(($alimento->cantidad_kg ?? 0) / max(($alimento->numero_aves ?? 1), 1) * 1000, 2) }}</div>
                <div class="label">Consumo por Ave (g)</div>
            </div>
            <div class="summary-item">
                <div class="value">{{ $alimento->numero_aves ?? 0 }}</div>
                <div class="label">Total de Aves</div>
            </div>
        </div>
    </div>

    @if(isset($alimento->responsable) || isset($alimento->observaciones))
    <div class="info-section">
        <h2>Información Adicional</h2>
        <table class="info-table">
            @if(isset($alimento->responsable))
            <tr>
                <th>Responsable:</th>
                <td>{{ $alimento->responsable }}</td>
            </tr>
            @endif
            
            @if(isset($alimento->observaciones))
            <tr>
                <th>Observaciones:</th>
                <td>{{ $alimento->observaciones }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    <div class="footer">
        <p><strong>Generado el:</strong> {{ $generatedAt->format('d/m/Y H:i:s') }}</p>
        <p><strong>Sistema:</strong> AVICONTROL - Módulo de Información</p>
        <p>Este es un informe automático generado por el sistema</p>
    </div>
</body>
</html>
