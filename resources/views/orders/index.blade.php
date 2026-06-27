@extends('layouts.panis')
@section('title', 'Mis Pedidos')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="section-title mb-8">Mis Pedidos</h1>
    @forelse($orders as $order)
    <a href="{{ route('orders.show', $order) }}" class="card p-5 mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:-translate-y-0.5 transition block">
        <div>
            <p class="font-mono text-sm text-panis-500">{{ $order->order_number }}</p>
            <p class="font-serif font-bold text-lg">{{ $order->type === 'personalizado' ? 'Pedido Personalizado' : 'Pedido Estándar' }}</p>
            <p class="text-sm text-panis-600">{{ $order->created_at->format('d/m/Y H:i') }} · {{ $order->items->count() }} producto(s)</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="badge {{ $order->statusColor() }}">{{ $order->statusLabel() }}</span>
            <span class="font-bold text-lg">S/ {{ number_format($order->total, 2) }}</span>
        </div>
    </a>
    @empty
    <div class="card p-12 text-center">
        <p class="text-4xl mb-4">📦</p>
        <p class="text-panis-600">Aún no tienes pedidos.</p>
        <a href="{{ route('catalog.index') }}" class="btn-primary mt-4 inline-block">Explorar Catálogo</a>
    </div>
    @endforelse
    {{ $orders->links() }}
</div>
@endsection
