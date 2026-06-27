@extends('layouts.admin')
@section('page-title', 'Compras de Insumos')
@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <form action="{{ route('admin.purchases.store') }}" method="POST" class="card p-6 space-y-3" id="purchase-form">
        @csrf<h3 class="font-serif font-bold">Nueva Orden de Compra</h3>
        <select name="supplier_id" class="input-field" required><option value="">Proveedor</option>@foreach($suppliers as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select>
        <input type="date" name="expected_date" class="input-field">
        <div id="items-container" class="space-y-2">
            <div class="grid grid-cols-3 gap-2 item-row">
                <select name="ingredient_id[]" class="input-field text-xs" required><option value="">Insumo</option>@foreach($ingredients as $i)<option value="{{ $i->id }}">{{ $i->name }}</option>@endforeach</select>
                <input type="number" name="quantity[]" step="0.01" class="input-field text-xs" placeholder="Cant." required>
                <input type="number" name="unit_price[]" step="0.01" class="input-field text-xs" placeholder="Precio" required>
            </div>
        </div>
        <button type="button" onclick="addRow()" class="text-sm text-gold-600 hover:underline">+ Agregar insumo</button>
        <textarea name="notes" class="input-field" rows="2" placeholder="Notas"></textarea>
        <button type="submit" class="btn-primary w-full">Crear OC</button>
    </form>
    <div class="lg:col-span-2 card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-panis-100"><tr><th class="p-4 text-left">N° OC</th><th class="p-4 text-left">Proveedor</th><th class="p-4 text-center">Estado</th><th class="p-4 text-right">Total</th><th class="p-4 text-right">Acción</th></tr></thead>
            <tbody>@foreach($purchaseOrders as $po)
            <tr class="border-t border-panis-100">
                <td class="p-4 font-mono text-xs">{{ $po->po_number }}</td>
                <td class="p-4">{{ $po->supplier->name }}</td>
                <td class="p-4 text-center capitalize">{{ $po->status }}</td>
                <td class="p-4 text-right font-bold">S/ {{ number_format($po->total, 2) }}</td>
                <td class="p-4 text-right">@if($po->status !== 'recibida')<form action="{{ route('admin.purchases.receive', $po) }}" method="POST" class="inline">@csrf @method('PATCH')<button class="text-green-600 text-sm hover:underline">Recibir</button></form>@else<span class="text-green-600 text-sm">✓ Recibida</span>@endif</td>
            </tr>@endforeach</tbody>
        </table>
        {{ $purchaseOrders->links() }}
    </div>
</div>
<script>
function addRow(){const c=document.getElementById('items-container');const r=c.querySelector('.item-row').cloneNode(true);r.querySelectorAll('input').forEach(i=>i.value='');r.querySelectorAll('select').forEach(s=>s.selectedIndex=0);c.appendChild(r);}
</script>
@endsection
