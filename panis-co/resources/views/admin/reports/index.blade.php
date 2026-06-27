@extends('layouts.admin')
@section('page-title', 'Reportes de Ventas')
@section('content')
<form method="GET" class="card p-4 mb-6 flex flex-wrap gap-4 items-end">
    <div><label class="text-xs font-medium">Desde</label><input type="date" name="from" value="{{ $from }}" class="input-field"></div>
    <div><label class="text-xs font-medium">Hasta</label><input type="date" name="to" value="{{ $to }}" class="input-field"></div>
    <button type="submit" class="btn-secondary !py-2">Generar</button>
</form>
<div class="grid md:grid-cols-3 gap-4 mb-6">
    <div class="card p-5 text-center"><p class="text-xs text-panis-500 uppercase">Ventas Totales</p><p class="text-3xl font-bold text-gold-600 mt-1">S/ {{ number_format($stats['total_sales'], 2) }}</p></div>
    <div class="card p-5 text-center"><p class="text-xs text-panis-500 uppercase">Pedidos</p><p class="text-3xl font-bold mt-1">{{ $stats['orders_count'] }}</p></div>
    <div class="card p-5 text-center"><p class="text-xs text-panis-500 uppercase">Ticket Promedio</p><p class="text-3xl font-bold mt-1">S/ {{ number_format($stats['avg_order'], 2) }}</p></div>
</div>
<div class="grid lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h3 class="font-serif font-bold mb-4">Top Productos</h3>
        @foreach($topProducts as $i => $tp)
        <div class="flex justify-between py-2 border-b border-panis-100"><span>{{ $i+1 }}. {{ $tp->product_name }}</span><span class="font-semibold">{{ $tp->total_qty }} uds · S/ {{ number_format($tp->revenue, 2) }}</span></div>
        @endforeach
    </div>
    <div class="card p-6">
        <h3 class="font-serif font-bold mb-4">Ventas por Día</h3>
        @foreach($salesByDay as $day)
        <div class="flex justify-between py-2 border-b border-panis-100"><span>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</span><span>{{ $day->count }} pedidos · S/ {{ number_format($day->total, 2) }}</span></div>
        @endforeach
    </div>
</div>
@endsection
