<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Producto</title>
    <style>
        body { font-family: sans-serif; margin: 30px; }
        .header { text-align: center; margin-bottom: 30px; }
        .product-card { border: 1px solid #ccc; border-radius: 10px; padding: 20px; }
        .product-title { font-size: 24px; font-weight: bold; color: #333; }
        .product-code { font-size: 16px; color: #777; margin-bottom: 20px; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .detail-item { padding: 10px; background-color: #f9f9f9; border-left: 4px solid #007bff; }
        .detail-label { font-weight: bold; display: block; margin-bottom: 5px; color: #555; }
    </style>
</head>
<body>
    <div class="product-card">
        <div class="header">
            <div class="product-title">{{ $product->name }}</div>
            <div class="product-code">Código: {{ $product->code }}</div>
        </div>
        <div class="details-grid">
            <div class="detail-item">
                <span class="detail-label">Categoría:</span>
                {{ $product->category->name ?? 'N/A' }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Stock Actual:</span>
                {{ $product->stock }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Stock Mínimo:</span>
                {{ $product->stock_min }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Precio Unitario:</span>
                ${{ number_format($product->unit_price, 2) }}
            </div>
            <div class="detail-item">
                <span class="detail-label">Estado:</span>
                {{ ucfirst($product->status) }}
            </div>
             <div class="detail-item">
                <span class="detail-label">Fecha de Expiración:</span>
                {{ $product->expiration_date ? \Carbon\Carbon::parse($product->expiration_date)->format('d/m/Y') : 'N/A' }}
            </div>
        </div>
    </div>
</body>
</html>
