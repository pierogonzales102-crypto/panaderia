@extends('layouts.admin')
@section('page-title', 'Editar Producto')
@section('content')
<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl card p-8 space-y-5">
    @csrf @method('PUT')
    <div><label class="block text-sm font-medium mb-1">Nombre *</label><input type="text" name="name" class="input-field" required value="{{ old('name', $product->name) }}"></div>
    <div><label class="block text-sm font-medium mb-1">Categoría *</label>
        <select name="category_id" class="input-field" required>@foreach($categories as $c)<option value="{{ $c->id }}" @selected($product->category_id == $c->id)>{{ $c->name }}</option>@endforeach</select>
    </div>
    <div><label class="block text-sm font-medium mb-1">Descripción</label><textarea name="description" class="input-field" rows="3">{{ old('description', $product->description) }}</textarea></div>
    <div class="grid grid-cols-2 gap-4">
        <div><label class="block text-sm font-medium mb-1">Precio *</label><input type="number" name="price" step="0.01" class="input-field" required value="{{ old('price', $product->price) }}"></div>
        <div><label class="block text-sm font-medium mb-1">Stock *</label><input type="number" name="stock" class="input-field" required value="{{ old('stock', $product->stock) }}"></div>
    </div>
    <div><label class="block text-sm font-medium mb-1">Nueva Imagen</label><input type="file" name="image" accept="image/*" class="input-field"></div>
    <div class="flex gap-6">
        <label class="flex items-center gap-2"><input type="checkbox" name="is_customizable" value="1" @checked($product->is_customizable) class="rounded text-gold-500"> Personalizable</label>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_available" value="1" @checked($product->is_available) class="rounded text-gold-500"> Disponible</label>
        <label class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" @checked($product->is_featured) class="rounded text-gold-500"> Destacado</label>
    </div>
    <button type="submit" class="btn-primary">Actualizar</button>
</form>
@endsection
