<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Movements - AVICONTROL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            color: #28a745;
            background: #d4edda;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
        }
        .info {
            color: #17a2b8;
            background: #d1ecf1;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #bee5eb;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Vista de Movimientos - Funcionando</h1>
        
        <div class="success">
            <h3>¡Éxito!</h3>
            <p>Esta vista simple está funcionando correctamente. Esto significa que:</p>
            <ul>
                <li>Las rutas de AVICONTROL están registradas</li>
                <li>El middleware permite el acceso</li>
                <li>El sistema de vistas funciona</li>
            </ul>
        </div>
        
        <div class="info">
            <h3>Información del Sistema</h3>
            <p><strong>Fecha y Hora:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
            <p><strong>Ruta Actual:</strong> {{ request()->url() }}</p>
            <p><strong>Nombre de Ruta:</strong> {{ request()->route()->getName() }}</p>
            <p><strong>Método HTTP:</strong> {{ request()->method() }}</p>
        </div>
        
        <div style="margin-top: 30px;">
            <h3>Acciones de Prueba</h3>
            <a href="{{ route('avicontrol.admin.inventory.index') }}" class="btn">← Volver al Inventario</a>
            <a href="{{ route('avicontrol.admin.inventory.movements.index') }}" class="btn">Ir a Movimientos Completos</a>
        </div>
        
        <div style="margin-top: 30px;">
            <h3>Diagnóstico</h3>
            <p>Si esta página funciona pero la página de movimientos completa no, el problema está en:</p>
            <ol>
                <li>El método del controlador InventoryController@movements</li>
                <li>La vista complex movements/index.blade.php</li>
                <li>Los datos de la base de datos</li>
            </ol>
        </div>
    </div>
</body>
</html>
