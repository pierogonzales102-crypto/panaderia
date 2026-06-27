@extends('layouts.admin')
@section('page-title', 'Producción')
@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <form action="{{ route('admin.production.store') }}" method="POST" class="card p-6 space-y-4">
        @csrf
        <h3 class="font-serif font-bold">Nueva Orden</h3>
        <select name="product_id" class="input-field" required><option value="">Producto</option>@foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select>
        <input type="number" name="quantity" class="input-field" placeholder="Cantidad" min="1" required>
        <input type="date" name="production_date" class="input-field" value="{{ date('Y-m-d') }}" required>
        <textarea name="notes" class="input-field" rows="2" placeholder="Notas"></textarea>
        <button type="submit" class="btn-primary w-full">Crear Orden</button>
    </form>
    <div class="lg:col-span-2 card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-panis-100"><tr><th class="p-4 text-left">Producto</th><th class="p-4 text-center">Cant.</th><th class="p-4 text-center">Fecha</th><th class="p-4 text-center">Estado</th><th class="p-4 text-right">Acción</th></tr></thead>
            <tbody>
            @foreach($productionOrders as $po)
            <tr class="border-t border-panis-100">
                <td class="p-4">{{ $po->product->name }}</td>
                <td class="p-4 text-center">{{ $po->quantity }}</td>
                <td class="p-4 text-center">{{ $po->production_date->format('d/m/Y') }}</td>
                <td class="p-4 text-center"><span class="badge bg-yellow-100 text-yellow-800 capitalize">{{ str_replace('_',' ',$po->status) }}</span></td>
                <td class="p-4 text-right">
                    <form action="{{ route('admin.production.status', $po) }}" method="POST" class="inline">@csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="text-xs border rounded-lg px-2 py-1">
                            @foreach(['pendiente','en_proceso','completado','cancelado'] as $s)<option value="{{ $s }}" @selected($po->status==$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        {{ $productionOrders->links() }}
    </div>
</div>
@endsection
