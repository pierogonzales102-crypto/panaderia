@extends('layouts.panis')
@section('title', 'Catálogo')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="text-center mb-10">
        <h1 class="section-title mb-2">Nuestro Catálogo</h1>
        <p class="text-panis-600">Descubre todos nuestros productos artesanales</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <aside class="lg:w-64 flex-shrink-0">
            <form method="GET" class="card p-6 space-y-4 sticky top-24">
                <h3 class="font-serif font-bold text-lg">Filtros</h3>
                <div>
                    <label class="block text-sm font-medium mb-1">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre..." class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Categoría</label>
                    <select name="category" class="input-field">
                        <option value="">Todas</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm font-medium mb-1">Precio min</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="input-field" step="0.01">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Precio max</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="input-field" step="0.01">
                    </div>
                </div>
                <button type="submit" class="btn-primary w-full">Filtrar</button>
            </form>
        </aside>

        <div class="flex-1">
            @if($products->isEmpty())
                <div class="card p-12 text-center">
                    <p class="text-4xl mb-4">🔍</p>
                    <h3 class="font-serif text-xl font-bold mb-2">No se encontraron productos</h3>
                    <p class="text-panis-600">Intenta con otros filtros o términos de búsqueda.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <div class="card group">
                        <div class="aspect-[4/3] overflow-hidden relative">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @if($product->is_customizable)
                                <span class="absolute top-3 right-3 badge bg-gold-500 text-white">Personalizable</span>
                            @endif
                        </div>
                        <div class="p-5">
                            <span class="text-xs text-gold-600 font-medium">{{ $product->category->name }}</span>
                            <h3 class="font-serif font-bold text-lg mt-1">{{ $product->name }}</h3>
                            <p class="text-sm text-panis-600 mt-1 line-clamp-2">{{ $product->description }}</p>
                            <div class="flex justify-between items-center mt-4">
                                <div>
                                    <span class="text-xl font-bold">S/ {{ number_format($product->price, 2) }}</span>
                                    <p class="text-xs text-panis-500">Stock: {{ $product->stock }}</p>
                                </div>
                                <a href="{{ route('catalog.show', $product) }}" class="btn-primary !py-2 !px-4 text-sm">Ver detalle</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
