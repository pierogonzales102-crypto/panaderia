@extends('layouts.admin')
@section('page-title', 'Recetas')
@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    <form action="{{ route('admin.recipes.store') }}" method="POST" class="card p-6 space-y-3">
        @csrf<h3 class="font-serif font-bold">Nueva Receta</h3>
        <select name="product_id" class="input-field" required><option value="">Producto</option>@foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select>
        <textarea name="instructions" class="input-field" rows="4" placeholder="Instrucciones de preparación"></textarea>
        <input type="number" name="prep_time_minutes" class="input-field" placeholder="Tiempo prep. (min)">
        <div id="ing-rows" class="space-y-2">
            <div class="grid grid-cols-2 gap-2 ing-row">
                <select name="ingredient_id[]" class="input-field text-xs" required><option value="">Insumo</option>@foreach($ingredients as $i)<option value="{{ $i->id }}">{{ $i->name }}</option>@endforeach</select>
                <input type="number" name="quantity[]" step="0.01" class="input-field text-xs" placeholder="Cantidad" required>
            </div>
        </div>
        <button type="button" onclick="document.getElementById('ing-rows').appendChild(document.querySelector('.ing-row').cloneNode(true))" class="text-sm text-gold-600">+ Ingrediente</button>
        <button type="submit" class="btn-primary w-full">Guardar Receta</button>
    </form>
    <div class="lg:col-span-2 space-y-4">
        @foreach($recipes as $recipe)
        <div class="card p-5">
            <div class="flex justify-between items-start">
                <div><h4 class="font-serif font-bold text-lg">{{ $recipe->product->name }}</h4>
                @if($recipe->prep_time_minutes)<p class="text-sm text-panis-500">⏱ {{ $recipe->prep_time_minutes }} min</p>@endif</div>
                <form action="{{ route('admin.recipes.destroy', $recipe) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button class="text-red-500 text-sm">Eliminar</button></form>
            </div>
            @if($recipe->instructions)<p class="text-sm text-panis-600 mt-2">{{ $recipe->instructions }}</p>@endif
            <div class="mt-3 flex flex-wrap gap-2">@foreach($recipe->recipeIngredients as $ri)<span class="badge bg-panis-100 text-panis-800">{{ $ri->ingredient->name }}: {{ $ri->quantity }} {{ $ri->unit }}</span>@endforeach</div>
        </div>
        @endforeach
    </div>
</div>
@endsection
