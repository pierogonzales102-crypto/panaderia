@extends('layouts.panis')
@section('title', 'Carrito')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="section-title mb-8">🛒 Mi Carrito</h1>

    @if(empty($items))
        <div class="card p-12 text-center">
            <p class="text-5xl mb-4">🛒</p>
            <h3 class="font-serif text-xl font-bold mb-2">Tu carrito está vacío</h3>
            <p class="text-panis-600 mb-6">Explora nuestro catálogo y añade productos deliciosos.</p>
            <a href="{{ route('catalog.index') }}" class="btn-primary">Ver Catálogo</a>
        </div>
    @else
        <div class="space-y-4 mb-8">
            @foreach($items as $key => $item)
            <div class="card p-4 flex gap-4 items-center">
                <img src="{{ $item['image'] }}" alt="" class="w-20 h-20 rounded-xl object-cover">
                <div class="flex-1">
                    <h3 class="font-serif font-bold">{{ $item['name'] }}</h3>
                    <p class="text-gold-600 font-semibold">S/ {{ number_format($item['price'], 2) }} c/u</p>
                </div>
                <form action="{{ route('cart.update', $key) }}" method="POST" class="flex items-center gap-2">
                    @csrf @method('PATCH')
                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="input-field w-20" onchange="this.form.submit()">
                </form>
                <span class="font-bold w-24 text-right">S/ {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                <form action="{{ route('cart.remove', $key) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700 p-2">✕</button>
                </form>
            </div>
            @endforeach
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <form action="{{ route('cart.discount') }}" method="POST" class="card p-6">
                @csrf
                <h3 class="font-serif font-bold mb-3">Código de Descuento</h3>
                <div class="flex gap-2">
                    <input type="text" name="code" placeholder="Ej: PANIS10" class="input-field flex-1">
                    <button type="submit" class="btn-secondary !py-2">Aplicar</button>
                </div>
                <p class="text-xs text-panis-500 mt-2">Prueba: PANIS10, DULCE15, HORNO20</p>
            </form>

            <div class="card p-6">
                <h3 class="font-serif font-bold mb-4">Resumen</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span>Subtotal</span><span>S/ {{ number_format($subtotal, 2) }}</span></div>
                    @if(session('cart_discount'))
                    <div class="flex justify-between text-green-600"><span>Descuento</span><span>- S/ {{ number_format(session('cart_discount'), 2) }}</span></div>
                    @endif
                    <div class="flex justify-between"><span>IGV (18%)</span><span>S/ {{ number_format($tax, 2) }}</span></div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2"><span>Total</span><span class="text-gold-600">S/ {{ number_format($total - session('cart_discount', 0), 2) }}</span></div>
                </div>
                @auth
                    <a href="{{ route('orders.checkout') }}" class="btn-primary w-full mt-6 text-center block">Proceder al Pago</a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary w-full mt-6 text-center block">Inicia sesión para comprar</a>
                @endauth
                <form action="{{ route('cart.clear') }}" method="POST" class="mt-3">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm text-red-500 hover:underline w-full text-center">Vaciar carrito</button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
