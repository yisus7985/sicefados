<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Inventario</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h1 { text-align: center; color: #333; }
        .date-range { text-align: center; margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .badge { padding: 3px 7px; color: white; border-radius: 5px; font-size: 12px; }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; }
        .badge-danger { background-color: #dc3545; }
    </style>
</head>
<body>
    <h1>Reporte de Inventario de Productos</h1>
    @if(isset($startDate) && isset($endDate))
        <div class="date-range">Reporte del {{ $startDate }} al {{ $endDate }}</div>
    @endif
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>${{ number_format($product->unit_price, 2) }}</td>
                    <td>
                        @if($product->status === 'active')
                            <span class="badge badge-success">Activo</span>
                        @elseif($product->status === 'inactive')
                            <span class="badge badge-warning">Inactivo</span>
                        @else
                            <span class="badge badge-danger">Vencido</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No hay productos que mostrar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
