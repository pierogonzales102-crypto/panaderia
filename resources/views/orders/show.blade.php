@extends('layouts.panis')
@section('title', 'Pedido '.$order->order_number)
@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="flex justify-between items-start mb-8">
        <div>
            <p class="text-sm text-panis-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
            <h1 class="section-title">{{ $order->order_number }}</h1>
        </div>
        <span class="badge {{ $order->statusColor() }} text-base px-4 py-2">{{ $order->statusLabel() }}</span>
    </div>

    <div class="card p-6 mb-6">
        <h3 class="font-serif font-bold mb-4">Productos</h3>
        @foreach($order->items as $item)
        <div class="flex justify-between py-3 border-b border-panis-100 last:border-0">
            <div>
                <p class="font-medium">{{ $item->product_name }}</p>
                <p class="text-sm text-panis-500">Cantidad: {{ $item->quantity }}</p>
                @if($item->customization)<p class="text-sm text-panis-600 mt-1">{{ $item->customization }}</p>@endif
            </div>
            <span class="font-semibold">S/ {{ number_format($item->subtotal, 2) }}</span>
        </div>
        @endforeach
        <div class="mt-4 pt-4 border-t flex justify-between font-bold text-xl">
            <span>Total</span>
            <span class="text-gold-600">S/ {{ number_format($order->total, 2) }}</span>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-6">
        <div class="card p-5">
            <h4 class="font-semibold mb-2">Entrega</h4>
            <p class="text-sm text-panis-600">{{ $order->delivery_address }}</p>
            @if($order->delivery_date)<p class="text-sm mt-1">Fecha: {{ $order->delivery_date->format('d/m/Y H:i') }}</p>@endif
        </div>
        <div class="card p-5">
            <h4 class="font-semibold mb-2">Pago</h4>
            <p class="text-sm capitalize">{{ str_replace('_', ' ', $order->payment_method ?? 'N/A') }}</p>
            <p class="text-sm mt-1">Estado: <span class="font-medium">{{ ucfirst($order->payment_status) }}</span></p>
        </div>
    </div>

    @if($order->receipt)
    <a href="{{ route('orders.receipt', $order) }}" class="btn-outline inline-block">📄 Descargar Comprobante</a>
    @endif
</div>
@endsection
