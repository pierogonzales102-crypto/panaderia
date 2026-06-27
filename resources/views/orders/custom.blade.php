@extends('layouts.panis')
@section('title', 'Pedido Personalizado')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
    <h1 class="section-title mb-2">✨ Pedido Personalizado</h1>
    <p class="text-panis-600 mb-8">Configura tu producto a medida: ingredientes, diseño, tamaño y fecha de entrega.</p>

    <form action="{{ route('orders.custom.store') }}" method="POST" enctype="multipart/form-data" class="card p-8 space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-2">Producto Base</label>
            <select name="product_id" class="input-field" required>
                <option value="">Selecciona un producto</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }} — desde S/ {{ number_format($product->price, 2) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Detalles de Personalización *</label>
            <textarea name="customization_details" rows="5" class="input-field" required placeholder="Describe ingredientes, diseño, tamaño, decoración...">{{ old('customization_details') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Instrucciones Adicionales</label>
            <textarea name="custom_instructions" rows="2" class="input-field" placeholder="Notas especiales...">{{ old('custom_instructions') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Imagen de Referencia</label>
            <input type="file" name="reference_image" accept="image/*" class="input-field">
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Fecha de Entrega *</label>
            <input type="datetime-local" name="delivery_date" class="input-field" required value="{{ old('delivery_date') }}">
        </div>
        <div>
            <label class="block text-sm font-medium mb-2">Dirección de Entrega *</label>
            <textarea name="delivery_address" rows="2" class="input-field" required>{{ old('delivery_address', auth()->user()->address ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn-primary w-full">Enviar Solicitud</button>
    </form>
</div>
@endsection
