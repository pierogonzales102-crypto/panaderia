@extends('layouts.admin')
@section('page-title', 'Proveedores')
@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <form action="{{ route('admin.suppliers.store') }}" method="POST" class="card p-6 space-y-3">
        @csrf<h3 class="font-serif font-bold">Nuevo Proveedor</h3>
        <input type="text" name="name" class="input-field" placeholder="Nombre empresa" required>
        <input type="text" name="contact_name" class="input-field" placeholder="Contacto">
        <input type="email" name="email" class="input-field" placeholder="Email">
        <input type="text" name="phone" class="input-field" placeholder="Teléfono">
        <textarea name="address" class="input-field" rows="2" placeholder="Dirección"></textarea>
        <button type="submit" class="btn-primary w-full">Registrar</button>
    </form>
    <div class="lg:col-span-2 card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-panis-100"><tr><th class="p-4 text-left">Proveedor</th><th class="p-4 text-left">Contacto</th><th class="p-4 text-center">Insumos</th><th class="p-4 text-center">Estado</th></tr></thead>
            <tbody>@foreach($suppliers as $s)
            <tr class="border-t border-panis-100"><td class="p-4 font-medium">{{ $s->name }}</td><td class="p-4">{{ $s->contact_name }} · {{ $s->phone }}</td><td class="p-4 text-center">{{ $s->ingredients_count }}</td><td class="p-4 text-center"><span class="badge {{ $s->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $s->is_active ? 'Activo' : 'Inactivo' }}</span></td></tr>
            @endforeach</tbody>
        </table>
    </div>
</div>
@endsection
