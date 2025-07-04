<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Galpones</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background:rgb(255, 255, 255);
            min-height: 100vh;
            color: #2c3e50;
        }

        .top-bar {
            background-color: #4E7E0E;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .top-bar h1 {
            color:rgb(255, 255, 255);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color:rgb(255, 255, 255);
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            background:rgba(27, 121, 6, 0.63);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            flex-shrink: 0;
        }

        .stat-icon.green {
            background: #e8f5e8;
            color: #4CAF50;
        }

        .stat-icon.blue {
            background: #e3f2fd;
            color: #2196F3;
        }

        .stat-icon.orange {
            background: #fff3e0;
            color: #ff9800;
        }

        .stat-icon.red {
            background: #ffebee;
            color: #f44336;
        }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.95rem;
            margin-top: 5px;
            font-weight: 500;
        }

        .section-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .section-header {
            background: linear-gradient(135deg, #4E7E0E 0%, #4E7E0E 100%);
            padding: 25px 30px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .section-title h2 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .section-title .icon {
            font-size: 1.8rem;
        }

        .btn-primary {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .table-container {
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background: #f8f9fa;
            color: #2c3e50;
            padding: 18px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
            border-bottom: 2px solid #e9ecef;
        }

        td {
            padding: 18px 20px;
            border-bottom: 1px solid #f1f3f4;
            transition: background-color 0.3s ease;
        }

        tr:hover td {
            background-color: #f8f9ff;
        }

        .galpon-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .capacity-cell {
            color: #5a6c7d;
            font-weight: 500;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-activo, .status-active, .status-operativo, .status-funcionando {
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

        .btn-action {
            padding: 8px 16px;
            background: linear-gradient(135deg, #4E7E0E 0%, #4E7E0E 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            padding: 12px 24px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .search-container {
            margin-bottom: 30px;
        }

        .search-box {
            position: relative;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            padding: 12px 45px 12px 20px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .search-input:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        .search-input::placeholder {
            color: #adb5bd;
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.1rem;
        }

        .search-results-info {
            margin-top: 10px;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .highlight {
            background-color: #fff3cd;
            padding: 2px 4px;
            border-radius: 3px;
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .no-results .icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .download-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 30px;
        }

        .download-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .download-header h3 {
            color: #2c3e50;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .download-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-download {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            min-width: 180px;
            justify-content: center;
        }

        .btn-download.daily {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-download.weekly {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }

        .btn-download.monthly {
            background: linear-gradient(135deg, #6f42c1 0%, #5a2d8c 100%);
            color: white;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-download:active {
            transform: translateY(0);
        }

        .download-info {
            margin-top: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .top-bar {
                padding: 15px 20px;
            }
            
            .top-bar h1 {
                font-size: 1.2rem;
            }

            .container {
                padding: 0 20px;
                margin: 20px auto;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .stat-card {
                padding: 20px;
            }

            .section-header {
                padding: 20px;
            }

            .table-container {
                padding: 20px;
                overflow-x: auto;
            }

            table {
                min-width: 600px;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <h1>INFORMES</h1>
        <div class="admin-info">
            <span>Informes al dia</span>
            <div class="admin-avatar">AV</div>
        </div>
    </div>

    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    🏢
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ count($galpones) }}</div>
                    <div class="stat-label">Total Galpones</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue">
                    🥚
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ number_format($galpones->sum('capacity')) }}</div>
                    <div class="stat-label">Capacidad Total</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    📊
                </div>
                <div class="stat-content">
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
            </div>
            <div class="stat-card">
                <div class="stat-icon red">
                    📋
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ count($galpones) }}</div>
                    <div class="stat-label">Reportes Disponibles</div>
                </div>
            </div>
        </div>

        <div class="download-section">
            <div class="download-header">
                <span style="font-size: 1.5rem;">📥</span>
                <h3>Descargar Informes</h3>
            </div>
            <div class="download-buttons">
                <button class="btn-download daily" onclick="downloadReport('daily')">
                    📅 Informe Diario
                </button>
                <button class="btn-download weekly" onclick="downloadReport('weekly')">
                    📊 Informe Semanal
                </button>
                <button class="btn-download monthly" onclick="downloadReport('monthly')">
                    📋 Informe Mensual
                </button>
            </div>
            <div class="download-info">
                💡 Los informes se generan automáticamente con los datos actuales de todos los galpones y se descargan en formato CSV.
            </div>
        </div>

        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" class="search-input" placeholder="Buscar galpones por nombre o estado...">
                <span class="search-icon">🔍</span>
            </div>
            <div class="search-results-info" id="searchResults"></div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <div class="section-title">
                    <span class="icon">📋</span>
                    <h2>Reportes de Galpones</h2>
                </div>
                <a href="#" class="btn-primary">
                    📊 Ver Todos los Reportes
                </a>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre del Galpón</th>
                            <th>Capacidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="galponesTable">
                        @foreach($galpones as $galpon)
                            <tr class="galpon-row" data-name="{{ strtolower($galpon->name) }}" data-status="{{ strtolower($galpon->status) }}">
                                <td class="galpon-name">{{ $galpon->name }}</td>
                                <td class="capacity-cell">{{ number_format($galpon->capacity) }} aves</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $galpon->status)) }}">
                                        {{ $galpon->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('avicontrol.admin.information.show', $galpon->id) }}" class="btn-action">
                                        👁️ Ver Reporte
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="no-results" id="noResults" style="display: none;">
                    <div class="icon">🔍</div>
                    <h3>No se encontraron resultados</h3>
                    <p>No hay galpones que coincidan con tu búsqueda</p>
                </div>
            </div>
        </div>

        <a href="{{ route('avicontrol.admin.welcome') }}" class="btn-back">
            ⬅️ Volver al Inicio
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const galponesTable = document.getElementById('galponesTable');
            const noResults = document.getElementById('noResults');
            const galponRows = document.querySelectorAll('.galpon-row');
            
            let totalGalpones = galponRows.length;
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;
                
                galponRows.forEach(row => {
                    const name = row.getAttribute('data-name');
                    const status = row.getAttribute('data-status');
                    
                    if (name.includes(searchTerm) || status.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                        
                        // Highlight matching text
                        const nameCell = row.querySelector('.galpon-name');
                        const statusBadge = row.querySelector('.status-badge');
                        
                        if (searchTerm) {
                            nameCell.innerHTML = highlightText(nameCell.textContent, searchTerm);
                            statusBadge.innerHTML = highlightText(statusBadge.textContent, searchTerm);
                        } else {
                            nameCell.innerHTML = nameCell.textContent;
                            statusBadge.innerHTML = statusBadge.textContent;
                        }
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Update search results info
                if (searchTerm) {
                    searchResults.textContent = `Mostrando ${visibleCount} de ${totalGalpones} galpones`;
                    searchResults.style.display = 'block';
                } else {
                    searchResults.style.display = 'none';
                }
                
                // Show/hide no results message
                if (visibleCount === 0 && searchTerm) {
                    noResults.style.display = 'block';
                    galponesTable.style.display = 'none';
                } else {
                    noResults.style.display = 'none';
                    galponesTable.style.display = '';
                }
            });
            
            function highlightText(text, searchTerm) {
                if (!searchTerm) return text;
                
                const regex = new RegExp(`(${searchTerm})`, 'gi');
                return text.replace(regex, '<span class="highlight">$1</span>');
            }
        });

        // Download functionality
        function downloadReport(type) {
            const now = new Date();
            const galpones = @json($galpones);
            
            let reportData = [];
            let filename = '';
            let dateRange = '';
            
            switch(type) {
                case 'daily':
                    dateRange = now.toLocaleDateString('es-ES');
                    filename = `informe_diario_${now.getFullYear()}-${(now.getMonth()+1).toString().padStart(2,'0')}-${now.getDate().toString().padStart(2,'0')}.csv`;
                    break;
                case 'weekly':
                    const startWeek = new Date(now);
                    startWeek.setDate(now.getDate() - now.getDay());
                    const endWeek = new Date(startWeek);
                    endWeek.setDate(startWeek.getDate() + 6);
                    dateRange = `${startWeek.toLocaleDateString('es-ES')} - ${endWeek.toLocaleDateString('es-ES')}`;
                    filename = `informe_semanal_semana_${getWeekNumber(now)}_${now.getFullYear()}.csv`;
                    break;
                case 'monthly':
                    const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                    dateRange = `${monthNames[now.getMonth()]} ${now.getFullYear()}`;
                    filename = `informe_mensual_${now.getFullYear()}-${(now.getMonth()+1).toString().padStart(2,'0')}.csv`;
                    break;
            }
            
            // Generate report header
            const header = [
                'INFORME DE GALPONES',
                `Tipo: ${type.toUpperCase()}`,
                `Período: ${dateRange}`,
                `Fecha de generación: ${now.toLocaleString('es-ES')}`,
                `Total de galpones: ${galpones.length}`,
                '',
                'DATOS DE GALPONES',
                ''
            ];
            
            // CSV Headers
            const csvHeaders = ['Nombre del Galpón', 'Capacidad', 'Estado', 'Capacidad Total', 'Porcentaje de Uso'];
            
            // Calculate totals
            const totalCapacity = galpones.reduce((sum, galpon) => sum + galpon.capacity, 0);
            const activeGalpones = galpones.filter(galpon => 
                galpon.status.toLowerCase().includes('activ') || 
                galpon.status.toLowerCase().includes('operativo') ||
                galpon.status.toLowerCase().includes('funcionando')
            ).length;
            
            // Generate CSV content
            let csvContent = '';
            
            // Add header info
            header.forEach(line => {
                csvContent += line + '\n';
            });
            
            // Add CSV headers
            csvContent += csvHeaders.join(',') + '\n';
            
            // Add data rows
            galpones.forEach(galpon => {
                const row = [
                    `"${galpon.name}"`,
                    galpon.capacity,
                    `"${galpon.status}"`,
                    totalCapacity,
                    `"${((galpon.capacity / totalCapacity) * 100).toFixed(2)}%"`
                ];
                csvContent += row.join(',') + '\n';
            });
            
            // Add summary
            csvContent += '\n';
            csvContent += 'RESUMEN EJECUTIVO\n';
            csvContent += `Total de galpones,${galpones.length}\n`;
            csvContent += `Galpones activos,${activeGalpones}\n`;
            csvContent += `Capacidad total,${totalCapacity.toLocaleString()} aves\n`;
            csvContent += `Porcentaje de galpones activos,"${((activeGalpones / galpones.length) * 100).toFixed(2)}%"\n`;
            
            // Add operational insights
            csvContent += '\n';
            csvContent += 'ANÁLISIS OPERACIONAL\n';
            csvContent += `Fecha del informe,${now.toLocaleString('es-ES')}\n`;
            csvContent += `Período analizado,${dateRange}\n`;
            csvContent += `Estado general,${activeGalpones > galpones.length/2 ? 'Óptimo' : 'Requiere atención'}\n`;
            
            // Download file
            downloadCSV(csvContent, filename);
        }
        
        function downloadCSV(content, filename) {
            const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', filename);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            }
        }
        
        function getWeekNumber(date) {
            const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
            const dayNum = d.getUTCDay() || 7;
            d.setUTCDate(d.getUTCDate() + 4 - dayNum);
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
            return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
        }
    </script>
</body>
</html>