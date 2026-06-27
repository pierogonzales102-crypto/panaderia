@extends('layouts.admin')
@section('page-title', 'Gestión de Pedidos')
@section('content')
<form method="GET" class="card p-4 mb-6 flex flex-wrap gap-4 items-end">
    <div><label class="text-xs font-medium">Buscar</label><input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="N° pedido o cliente"></div>
    <div><label class="text-xs font-medium">Estado</label><select name="status" class="input-field"><option value="">Todos</option>@foreach(['pendiente','confirmado','en_produccion','listo','entregado','cancelado'] as $s)<option value="{{ $s }}" @selected(request('status')==$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
    <div><label class="text-xs font-medium">Tipo</label><select name="type" class="input-field"><option value="">Todos</option><option value="estandar" @selected(request('type')=='estandar')>Estándar</option><option value="personalizado" @selected(request('type')=='personalizado')>Personalizado</option></select></div>
    <button type="submit" class="btn-secondary !py-2">Filtrar</button>
</form>
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-panis-100"><tr><th class="p-4 text-left">Pedido</th><th class="p-4 text-left">Cliente</th><th class="p-4 text-center">Tipo</th><th class="p-4 text-center">Estado</th><th class="p-4 text-right">Total</th><th class="p-4 text-right">Acciones</th></tr></thead>
        <tbody>
        @foreach($orders as $order)
        <tr class="border-t border-panis-100 hover:bg-panis-50">
            <td class="p-4 font-mono text-xs">{{ $order->order_number }}</td>
            <td class="p-4">{{ $order->user->name }}</td>
            <td class="p-4 text-center capitalize">{{ $order->type }}</td>
            <td class="p-4 text-center"><span class="badge {{ $order->statusColor() }}">{{ $order->statusLabel() }}</span></td>
            <td class="p-4 text-right font-bold">S/ {{ number_format($order->total, 2) }}</td>
            <td class="p-4 text-right"><a href="{{ route('admin.orders.show', $order) }}" class="text-gold-600 hover:underline">Ver</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $orders->links() }}
@endsection
