<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Comprobante {{ $order->order_number }}</title>
<style>body{font-family:Arial,sans-serif;color:#3E2723;padding:40px}h1{color:#B8860B;border-bottom:2px solid #C9A227;padding-bottom:10px}.header{text-align:center;margin-bottom:30px}table{width:100%;border-collapse:collapse;margin:20px 0}th,td{padding:8px;text-align:left;border-bottom:1px solid #E8D5B5}th{background:#F5EDE0}.total{font-size:18px;font-weight:bold;color:#B8860B}</style>
</head>
<body>
<div class="header">
    <h1>🥖 Panis & Co</h1>
    <p>Panadería y Pastelería Artesanal</p>
    <p><strong>{{ strtoupper($order->receipt->type ?? 'boleta') }} N° {{ $order->receipt->receipt_number ?? $order->order_number }}</strong></p>
</div>
<p><strong>Cliente:</strong> {{ $order->user->name }}<br>
<strong>Pedido:</strong> {{ $order->order_number }}<br>
<strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
<table>
    <thead><tr><th>Producto</th><th>Cant.</th><th>P.Unit</th><th>Subtotal</th></tr></thead>
    <tbody>
    @foreach($order->items as $item)
    <tr><td>{{ $item->product_name }}</td><td>{{ $item->quantity }}</td><td>S/ {{ number_format($item->unit_price,2) }}</td><td>S/ {{ number_format($item->subtotal,2) }}</td></tr>
    @endforeach
    </tbody>
</table>
<p>Subtotal: S/ {{ number_format($order->subtotal,2) }}</p>
@if($order->discount > 0)<p>Descuento: - S/ {{ number_format($order->discount,2) }}</p>@endif
<p>IGV: S/ {{ number_format($order->tax,2) }}</p>
<p class="total">TOTAL: S/ {{ number_format($order->total,2) }}</p>
</body>
</html>
