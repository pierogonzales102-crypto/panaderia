@extends('layouts.admin')
@section('page-title', 'Pedido '.$order->order_number)
@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="card p-6">
            <h3 class="font-serif font-bold mb-4">Productos</h3>
            @foreach($order->items as $item)
            <div class="flex justify-between py-3 border-b border-panis-100">
                <div><p class="font-medium">{{ $item->product_name }}</p><p class="text-sm text-panis-500">x{{ $item->quantity }}</p></div>
                <span class="font-semibold">S/ {{ number_format($item->subtotal, 2) }}</span>
            </div>
            @endforeach
            <p class="text-xl font-bold text-right mt-4 text-gold-600">Total: S/ {{ number_format($order->total, 2) }}</p>
        </div>
        <div class="card p-6">
            <h3 class="font-serif font-bold mb-2">Cliente: {{ $order->user->name }}</h3>
            <p class="text-sm text-panis-600">{{ $order->user->email }} · {{ $order->user->phone }}</p>
            <p class="text-sm mt-2"><strong>Entrega:</strong> {{ $order->delivery_address }}</p>
            @if($order->customization_details)<p class="text-sm mt-2 bg-panis-50 p-3 rounded-xl"><strong>Personalización:</strong> {{ $order->customization_details }}</p>@endif
        </div>
    </div>
    <div class="card p-6 h-fit">
        <h3 class="font-serif font-bold mb-4">Actualizar Estado</h3>
        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
            @csrf @method('PATCH')
            <select name="status" class="input-field mb-4">
                @foreach(['pendiente','confirmado','en_produccion','listo','en_camino','entregado','cancelado'] as $s)
                <option value="{{ $s }}" @selected($order->status == $s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary w-full">Actualizar</button>
        </form>
        <div class="mt-4 pt-4 border-t text-sm space-y-1">
            <p>Pago: <strong>{{ ucfirst($order->payment_status) }}</strong></p>
            <p>Método: <strong>{{ ucfirst($order->payment_method ?? 'N/A') }}</strong></p>
        </div>
    </div>
</div>
@endsection
