@extends('layouts.admin')
@section('page-title', 'Inventario')
@section('content')
@if($lowStock->isNotEmpty())
<div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-red-800">
    ⚠️ {{ $lowStock->count() }} insumo(s) con stock bajo
</div>
@endif
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <form action="{{ route('admin.inventory.ingredient') }}" method="POST" class="card p-6 space-y-3">
        @csrf<h3 class="font-serif font-bold">Nuevo Insumo</h3>
        <input type="text" name="name" class="input-field" placeholder="Nombre" required>
        <input type="text" name="unit" class="input-field" placeholder="Unidad (kg, L)" value="kg" required>
        <div class="grid grid-cols-2 gap-2">
            <input type="number" name="stock" step="0.01" class="input-field" placeholder="Stock" required>
            <input type="number" name="min_stock" step="0.01" class="input-field" placeholder="Stock mín." required>
        </div>
        <input type="number" name="unit_cost" step="0.01" class="input-field" placeholder="Costo unitario" required>
        <button type="submit" class="btn-primary w-full">Registrar</button>
    </form>
    <form action="{{ route('admin.inventory.movement') }}" method="POST" class="card p-6 space-y-3 lg:col-span-2">
        @csrf<h3 class="font-serif font-bold">Registrar Movimiento</h3>
        <div class="grid md:grid-cols-4 gap-3">
            <select name="ingredient_id" class="input-field" required><option value="">Insumo</option>@foreach($ingredients as $i)<option value="{{ $i->id }}">{{ $i->name }} ({{ $i->stock }} {{ $i->unit }})</option>@endforeach</select>
            <select name="type" class="input-field" required><option value="entrada">Entrada</option><option value="salida">Salida</option></select>
            <input type="number" name="quantity" step="0.01" class="input-field" placeholder="Cantidad" required>
            <input type="text" name="reason" class="input-field" placeholder="Motivo">
        </div>
        <button type="submit" class="btn-primary">Registrar Movimiento</button>
    </form>
</div>
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-panis-100"><tr><th class="p-4 text-left">Insumo</th><th class="p-4 text-right">Stock</th><th class="p-4 text-right">Mínimo</th><th class="p-4 text-right">Costo</th><th class="p-4 text-center">Estado</th></tr></thead>
        <tbody>
        @foreach($ingredients as $ing)
        <tr class="border-t border-panis-100 {{ $ing->isLowStock() ? 'bg-red-50' : '' }}">
            <td class="p-4 font-medium">{{ $ing->name }}</td>
            <td class="p-4 text-right">{{ $ing->stock }} {{ $ing->unit }}</td>
            <td class="p-4 text-right">{{ $ing->min_stock }}</td>
            <td class="p-4 text-right">S/ {{ number_format($ing->unit_cost, 2) }}</td>
            <td class="p-4 text-center"><span class="badge {{ $ing->isLowStock() ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">{{ $ing->isLowStock() ? 'Bajo' : 'OK' }}</span></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
