@extends('layouts.panis')
@section('title', $product->name)
@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="grid md:grid-cols-2 gap-10">
        <div class="card overflow-hidden">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full aspect-square object-cover">
        </div>
        <div>
            <span class="text-gold-600 font-medium text-sm uppercase">{{ $product->category->name }}</span>
            <h1 class="font-serif text-3xl md:text-4xl font-bold mt-2 mb-4">{{ $product->name }}</h1>
            <p class="text-panis-600 leading-relaxed mb-6">{{ $product->description }}</p>

            @if($product->ingredients_text)
            <div class="bg-panis-100 rounded-xl p-4 mb-6">
                <h3 class="font-semibold mb-2">Ingredientes</h3>
                <p class="text-sm text-panis-700">{{ $product->ingredients_text }}</p>
            </div>
            @endif

            <div class="flex items-baseline gap-3 mb-6">
                <span class="text-3xl font-bold text-panis-900">S/ {{ number_format($product->price, 2) }}</span>
                <span class="text-sm text-panis-500">{{ $product->stock > 0 ? $product->stock.' disponibles' : 'Agotado' }}</span>
            </div>

            @if($product->stock > 0)
            <form action="{{ route('cart.add') }}" method="POST" class="flex gap-4 items-end">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div>
                    <label class="block text-sm font-medium mb-1">Cantidad</label>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="input-field w-24">
                </div>
                <button type="submit" class="btn-primary flex-1">🛒 Añadir al Carrito</button>
            </form>
            @else
                <p class="text-red-600 font-medium">Producto temporalmente agotado</p>
            @endif

            @if($product->is_customizable)
            <a href="{{ route('orders.custom') }}" class="btn-outline w-full mt-4 text-center block">✨ Personalizar este producto</a>
            @endif
        </div>
    </div>

    @if($related->isNotEmpty())
    <section class="mt-16">
        <h2 class="section-title mb-6">Productos Relacionados</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($related as $item)
            <a href="{{ route('catalog.show', $item) }}" class="card group">
                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full aspect-[4/3] object-cover group-hover:scale-105 transition">
                <div class="p-4">
                    <h3 class="font-serif font-bold">{{ $item->name }}</h3>
                    <p class="text-gold-600 font-bold">S/ {{ number_format($item->price, 2) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
