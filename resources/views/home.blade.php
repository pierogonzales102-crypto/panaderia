@extends('layouts.panis')

@section('title', 'Inicio')

@section('content')
<section class="relative bg-panis-gradient text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('https://images.unsplash.com/photo-1509440159596-0249088772ff?w=1920'); background-size: cover; background-position: center;"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 py-24 md:py-32 text-center">
        <span class="inline-block px-4 py-1 bg-gold-500/20 border border-gold-400/30 rounded-full text-gold-300 text-sm font-medium mb-6">Panadería Artesanal desde 2010</span>
        <h1 class="font-serif text-4xl md:text-6xl font-bold mb-6 leading-tight">
            El sabor auténtico de<br><span class="text-gold-400">Panis & Co</span>
        </h1>
        <p class="text-lg md:text-xl text-panis-200 max-w-2xl mx-auto mb-10">
            Panes artesanales, pastelería fina y tortas personalizadas. Hechos con ingredientes naturales y mucho amor.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('catalog.index') }}" class="btn-primary text-lg">Ver Catálogo</a>
            @auth
            <a href="{{ route('orders.custom') }}" class="btn-outline !border-white !text-white hover:!bg-white hover:!text-panis-900 text-lg">Pedido Personalizado</a>
            @else
            <a href="{{ route('register') }}" class="btn-outline !border-white !text-white hover:!bg-white hover:!text-panis-900 text-lg">Pedido Personalizado</a>
            @endauth
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-16">
    <div class="text-center mb-12">
        <h2 class="section-title mb-3">Nuestras Categorías</h2>
        <p class="text-panis-600">Explora nuestra variedad de productos artesanales</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($categories as $category)
        <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="card group text-center p-6 hover:-translate-y-1 transition-transform duration-300">
            <div class="w-16 h-16 mx-auto mb-4 bg-panis-100 rounded-2xl flex items-center justify-center text-3xl group-hover:bg-gold-100 transition">
                @switch($category->slug)
                    @case('panes') 🍞 @break
                    @case('pasteles') 🎂 @break
                    @case('tortas') 🎂 @break
                    @case('galletas') 🍪 @break
                    @default 🥐
                @endswitch
            </div>
            <h3 class="font-serif font-bold text-panis-900">{{ $category->name }}</h3>
            <p class="text-sm text-panis-500 mt-1">{{ $category->products_count }} productos</p>
        </a>
        @endforeach
    </div>
</section>

<section class="bg-panis-100 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="section-title mb-2">Productos Destacados</h2>
                <p class="text-panis-600">Lo más popular de nuestra panadería</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="text-gold-600 hover:text-gold-700 font-semibold text-sm">Ver todos →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <div class="card group">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-5">
                    <span class="text-xs text-gold-600 font-medium uppercase tracking-wide">{{ $product->category->name }}</span>
                    <h3 class="font-serif font-bold text-lg mt-1 mb-2">{{ $product->name }}</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-panis-900">S/ {{ number_format($product->price, 2) }}</span>
                        <a href="{{ route('catalog.show', $product) }}" class="btn-primary !py-2 !px-4 text-sm">Ver</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-16">
    <div class="grid md:grid-cols-3 gap-8">
        <div class="text-center p-8">
            <div class="w-14 h-14 mx-auto mb-4 bg-gold-100 rounded-2xl flex items-center justify-center text-2xl">🌾</div>
            <h3 class="font-serif font-bold text-xl mb-2">Ingredientes Naturales</h3>
            <p class="text-panis-600 text-sm">Seleccionamos las mejores materias primas para garantizar calidad y sabor.</p>
        </div>
        <div class="text-center p-8">
            <div class="w-14 h-14 mx-auto mb-4 bg-gold-100 rounded-2xl flex items-center justify-center text-2xl">👨‍🍳</div>
            <h3 class="font-serif font-bold text-xl mb-2">Elaboración Artesanal</h3>
            <p class="text-panis-600 text-sm">Cada producto es hecho a mano por nuestros maestros panaderos y pasteleros.</p>
        </div>
        <div class="text-center p-8">
            <div class="w-14 h-14 mx-auto mb-4 bg-gold-100 rounded-2xl flex items-center justify-center text-2xl">🚚</div>
            <h3 class="font-serif font-bold text-xl mb-2">Entrega a Domicilio</h3>
            <p class="text-panis-600 text-sm">Recibe tus productos frescos directamente en la puerta de tu casa.</p>
        </div>
    </div>
</section>
@endsection
