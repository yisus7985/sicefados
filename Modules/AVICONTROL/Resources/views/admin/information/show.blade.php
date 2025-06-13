<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Galpón</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .header h2 {
            font-size: 2.2rem;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            border-left: 5px solid #28a745;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .info-card h4 {
            color: #28a745;
            font-size: 1.3rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-list {
            list-style: none;
            padding: 0;
        }

        .info-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
            font-size: 0.95rem;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6c757d;
            font-weight: 500;
        }

        .info-value {
            color: #343a40;
            font-weight: 600;
        }

        .occupancy-bar {
            background: #e9ecef;
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 5px;
        }

        .occupancy-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .birds-section {
            margin-top: 40px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .section-header h4 {
            color: #2196F3;
            font-size: 1.4rem;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        th {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            padding: 18px 15px;
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
            font-size: 0.9rem;
        }

        tr:hover td {
            background-color: #f0f8ff;
        }

        tr:nth-child(even) td {
            background-color: #fafafa;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 30px;
            padding: 14px 24px;
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-activo {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactivo {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-mantenimiento {
            background-color: #fff3cd;
            color: #856404;
        }

        .highlight-number {
            background: linear-gradient(135deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
            font-size: 1.1em;
        }

        @media (max-width: 1024px) {
            .info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }

            .header h2 {
                font-size: 1.6rem;
            }

            .content {
                padding: 20px;
            }

            .info-card {
                padding: 20px;
            }

            table {
                font-size: 0.8rem;
            }

            th, td {
                padding: 10px 8px;
            }

            .info-list li {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🐔 Detalle del Galpón: {{ $galpon->name }}</h2>
        </div>

        <div class="content">
            <div class="info-grid">
                <div class="info-card">
                    <h4>📦 Información General</h4>
                    <ul class="info-list">
                        <li>
                            <span class="info-label">Descripción:</span>
                            <span class="info-value">{{ $galpon->description ?? 'No especificada' }}</span>
                        </li>
                        <li>
                            <span class="info-label">Capacidad:</span>
                            <span class="info-value highlight-number">{{ number_format($galpon->capacity) }} aves</span>
                        </li>
                        <li>
                            <span class="info-label">Área:</span>
                            <span class="info-value">{{ $galpon->area }} m²</span>
                        </li>
                        <li>
                            <span class="info-label">Estado:</span>
                            <span class="status-badge status-{{ strtolower($galpon->status) }}">
                                {{ $galpon->status }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="info-card">
                    <h4>📊 Estadísticas de Ocupación</h4>
                    <ul class="info-list">
                        <li>
                            <span class="info-label">Aves Activas:</span>
                            <span class="info-value highlight-number">{{ number_format($galpon->total_active_birds) }}</span>
                        </li>
                        <li>
                            <span class="info-label">Ocupación:</span>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span class="info-value">{{ $galpon->occupancy_percentage }}%</span>
                                <div class="occupancy-bar" style="width: 100px;">
                                    <div class="occupancy-fill" style="width: {{ $galpon->occupancy_percentage }}%;"></div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <span class="info-label">Capacidad Disponible:</span>
                            <span class="info-value">{{ number_format($galpon->capacity - $galpon->total_active_birds) }} aves</span>
                        </li>
                        <li>
                            <span class="info-label">Densidad:</span>
                            <span class="info-value">{{ round($galpon->total_active_birds / $galpon->area, 2) }} aves/m²</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="birds-section">
                <div class="section-header">
                    <h4>🐣 Aves en este galpón</h4>
                </div>

                @if($galpon->birds->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Código Lote</th>
                                <th>Tipo</th>
                                <th>Cantidad</th>
                                <th>Edad (semanas)</th>
                                <th>Estado</th>
                                <th>Raza</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($galpon->birds as $ave)
                                <tr>
                                    <td><strong>{{ $ave->batch_code }}</strong></td>
                                    <td>{{ $ave->bird_type_name }}</td>
                                    <td class="highlight-number">{{ number_format($ave->quantity) }}</td>
                                    <td>{{ $ave->current_age_weeks ?? $ave->age_weeks }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $ave->status_name)) }}">
                                            {{ $ave->status_name }}
                                        </span>
                                    </td>
                                    <td>{{ $ave->breed ?? 'No especificada' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <p>🐣 No hay aves registradas en este galpón actualmente.</p>
                    </div>
                @endif
            </div>

            <a href="{{ route('avicontrol.admin.information.index') }}" class="btn-back">
                ← Volver a la lista
            </a>
        </div>
    </div>
</body>
</html>