@extends('layouts.admin')
@section('page-title', 'Productos')
@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-panis-600">{{ $products->total() }} productos registrados</p>
    <a href="{{ route('admin.products.create') }}" class="btn-primary">+ Nuevo Producto</a>
</div>
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-panis-100"><tr>
            <th class="text-left p-4">Producto</th><th class="text-left p-4">Categoría</th><th class="text-right p-4">Precio</th><th class="text-right p-4">Stock</th><th class="text-center p-4">Estado</th><th class="text-right p-4">Acciones</th>
        </tr></thead>
        <tbody>
        @foreach($products as $product)
        <tr class="border-t border-panis-100 hover:bg-panis-50">
            <td class="p-4 font-medium">{{ $product->name }}</td>
            <td class="p-4 text-panis-600">{{ $product->category->name }}</td>
            <td class="p-4 text-right font-semibold">S/ {{ number_format($product->price, 2) }}</td>
            <td class="p-4 text-right">{{ $product->stock }}</td>
            <td class="p-4 text-center"><span class="badge {{ $product->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $product->is_available ? 'Activo' : 'Inactivo' }}</span></td>
            <td class="p-4 text-right space-x-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="text-gold-600 hover:underline">Editar</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button class="text-red-500 hover:underline">Eliminar</button></form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $products->links() }}
@endsection
