@extends('layouts.admin')
@section('page-title', 'Categorías')
@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <form action="{{ route('admin.categories.store') }}" method="POST" class="card p-6 space-y-4">
        @csrf
        <h3 class="font-serif font-bold">Nueva Categoría</h3>
        <input type="text" name="name" class="input-field" placeholder="Nombre" required>
        <textarea name="description" class="input-field" rows="2" placeholder="Descripción"></textarea>
        <button type="submit" class="btn-primary w-full">Crear</button>
    </form>
    <div class="lg:col-span-2 card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-panis-100"><tr><th class="p-4 text-left">Nombre</th><th class="p-4 text-center">Productos</th><th class="p-4 text-center">Estado</th><th class="p-4 text-right">Acciones</th></tr></thead>
            <tbody>
            @foreach($categories as $cat)
            <tr class="border-t border-panis-100">
                <td class="p-4 font-medium">{{ $cat->name }}</td>
                <td class="p-4 text-center">{{ $cat->products_count }}</td>
                <td class="p-4 text-center"><span class="badge {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $cat->is_active ? 'Activa' : 'Inactiva' }}</span></td>
                <td class="p-4 text-right">
                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button class="text-red-500 text-sm">Eliminar</button></form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
