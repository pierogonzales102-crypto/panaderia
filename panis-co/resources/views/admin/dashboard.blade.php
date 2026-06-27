@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    @foreach([
        ['Pedidos Hoy', $stats['orders_today'], '📦', 'bg-blue-50 text-blue-700'],
        ['Pendientes', $stats['pending_orders'], '⏳', 'bg-yellow-50 text-yellow-700'],
        ['Ventas Hoy', 'S/ '.number_format($stats['sales_today'],0), '💰', 'bg-green-50 text-green-700'],
        ['Stock Bajo', $stats['low_stock'], '⚠️', 'bg-red-50 text-red-700'],
        ['Productos', $stats['products_count'], '🍞', 'bg-purple-50 text-purple-700'],
        ['Producción', $stats['production_pending'], '👨‍🍳', 'bg-orange-50 text-orange-700'],
    ] as [$label, $value, $icon, $color])
    <div class="card p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-panis-500 uppercase tracking-wide">{{ $label }}</p>
                <p class="text-2xl font-bold mt-1">{{ $value }}</p>
            </div>
            <span class="text-2xl w-10 h-10 {{ $color }} rounded-xl flex items-center justify-center">{{ $icon }}</span>
        </div>
    </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h3 class="font-serif font-bold text-lg mb-4">Pedidos Recientes</h3>
        <div class="space-y-3">
            @foreach($recentOrders as $order)
            <a href="{{ route('admin.orders.show', $order) }}" class="flex justify-between items-center p-3 rounded-xl hover:bg-panis-50 transition">
                <div>
                    <p class="font-mono text-xs text-panis-500">{{ $order->order_number }}</p>
                    <p class="font-medium">{{ $order->user->name }}</p>
                </div>
                <div class="text-right">
                    <span class="badge {{ $order->statusColor() }}">{{ $order->statusLabel() }}</span>
                    <p class="font-bold text-sm mt-1">S/ {{ number_format($order->total, 2) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    <div class="card p-6">
        <h3 class="font-serif font-bold text-lg mb-4">⚠️ Alertas de Stock Bajo</h3>
        @forelse($lowStockItems as $item)
        <div class="flex justify-between items-center p-3 rounded-xl bg-red-50 mb-2">
            <span class="font-medium">{{ $item->name }}</span>
            <span class="text-red-600 font-bold">{{ $item->stock }} {{ $item->unit }} (mín: {{ $item->min_stock }})</span>
        </div>
        @empty
        <p class="text-panis-500 text-sm">✅ Todos los insumos con stock suficiente.</p>
        @endforelse
    </div>
</div>
@endsection
