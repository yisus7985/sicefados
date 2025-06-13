<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Galpones</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
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
            font-size: 2.5rem;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px;
        }

        .stats-bar {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #4CAF50;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #4CAF50;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        th {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            padding: 18px 15px;
            text-align: center;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
        }

        tr:hover td {
            background-color: #f8f9ff;
        }

        tr:nth-child(even) td {
            background-color: #fafafa;
        }

        .btn-action {
            padding: 8px 16px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-action::before {
            content: '👁️';
            font-size: 0.9rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 30px;
            padding: 12px 20px;
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-activo, .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-inactivo, .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-mantenimiento, .status-maintenance {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-disponible, .status-available {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }

            .header h2 {
                font-size: 1.8rem;
            }

            .content {
                padding: 20px;
            }

            .stats-bar {
                flex-direction: column;
                gap: 15px;
            }

            table {
                font-size: 0.85rem;
            }

            th, td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📋 Reportes de Galpones</h2>
        </div>

        <div class="content">
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number">{{ count($galpones) }}</div>
                    <div class="stat-label">Total Galpones</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">
                        @php
                            $activosCount = $galpones->filter(function($galpon) {
                                return stripos($galpon->status, 'activ') !== false || 
                                       stripos($galpon->status, 'operativo') !== false ||
                                       stripos($galpon->status, 'funcionando') !== false;
                            })->count();
                        @endphp
                        {{ $activosCount }}
                    </div>
                    <div class="stat-label">Galpones Activos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $galpones->sum('capacity') }}</div>
                    <div class="stat-label">Capacidad Total</div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Nombre del Galpón</th>
                        <th>Capacidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($galpones as $galpon)
                        <tr>
                            <td><strong>{{ $galpon->name }}</strong></td>
                            <td>{{ number_format($galpon->capacity) }} aves</td>
                            <td>
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $galpon->status)) }}">
                                    {{ $galpon->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('avicontrol.admin.information.show', $galpon->id) }}" class="btn-action">
                                    Ver Reporte
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('avicontrol.admin.welcome') }}" class="btn-back">
                ⬅ Volver al Inicio
            </a>
        </div>
    </div>
</body>
</html>