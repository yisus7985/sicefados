@extends('avicontrol::layouts.admin')

@section('title', 'Estadísticas Simples')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line"></i>
            Estadísticas Simples
        </h1>
        <a href="{{ route('avicontrol.admin.food_conversion.estadisticas') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Estadísticas
        </a>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Conversión por Galpón</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="simpleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">Estado de Chart.js</h6>
                </div>
                <div class="card-body">
                    <div id="chartStatus" class="alert alert-info">
                        Verificando Chart.js...
                    </div>
                    <button class="btn btn-primary" onclick="testChart()">
                        Probar Gráfica
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
let simpleChart = null;

function testChart() {
    const statusDiv = document.getElementById('chartStatus');
    
    try {
        // Verificar si Chart.js está disponible
        if (typeof Chart === 'undefined') {
            statusDiv.className = 'alert alert-danger';
            statusDiv.innerHTML = '❌ Error: Chart.js no está disponible';
            return;
        }
        
        statusDiv.className = 'alert alert-success';
        statusDiv.innerHTML = '✅ Chart.js está funcionando correctamente';
        
        // Crear gráfica simple
        const ctx = document.getElementById('simpleChart');
        if (ctx && !simpleChart) {
            simpleChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Galpón 1', 'Galpón 2', 'Galpón 3'],
                    datasets: [{
                        label: 'Conversión',
                        data: [2.1, 2.3, 1.9],
                        backgroundColor: ['#28a745', '#007bff', '#ffc107']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            statusDiv.innerHTML += '<br>✅ Gráfica creada exitosamente';
        }
        
    } catch (error) {
        statusDiv.className = 'alert alert-danger';
        statusDiv.innerHTML = '❌ Error: ' + error.message;
        console.error('Error en testChart:', error);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado');
    
    // Verificar Chart.js automáticamente
    setTimeout(() => {
        if (typeof Chart !== 'undefined') {
            document.getElementById('chartStatus').className = 'alert alert-success';
            document.getElementById('chartStatus').innerHTML = '✅ Chart.js cargado correctamente';
        } else {
            document.getElementById('chartStatus').className = 'alert alert-warning';
            document.getElementById('chartStatus').innerHTML = '⚠️ Chart.js no detectado automáticamente';
        }
    }, 1000);
});
</script>
@endpush

