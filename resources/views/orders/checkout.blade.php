@extends('layouts.panis')
@section('title', 'Checkout')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="section-title mb-8">Finalizar Pedido</h1>
    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="card p-6">
            <h3 class="font-serif font-bold text-lg mb-4">Dirección de Entrega</h3>
            <textarea name="delivery_address" rows="3" class="input-field" required placeholder="Ingresa tu dirección completa">{{ old('delivery_address', auth()->user()->address) }}</textarea>
        </div>

        <div class="card p-6">
            <h3 class="font-serif font-bold text-lg mb-4">Método de Pago</h3>
            <div class="space-y-3">
                @foreach(['tarjeta' => '💳 Tarjeta de crédito/débito', 'efectivo' => '💵 Efectivo contra entrega', 'transferencia' => '🏦 Transferencia bancaria'] as $value => $label)
                <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:border-gold-500 transition has-[:checked]:border-gold-500 has-[:checked]:bg-gold-50">
                    <input type="radio" name="payment_method" value="{{ $value }}" class="text-gold-500 focus:ring-gold-500" @checked(old('payment_method', 'tarjeta') == $value) required>
                    <span>{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-serif font-bold text-lg mb-4">Notas (opcional)</h3>
            <textarea name="notes" rows="2" class="input-field" placeholder="Instrucciones especiales...">{{ old('notes') }}</textarea>
        </div>

        <div class="card p-6">
            <h3 class="font-serif font-bold text-lg mb-4">Resumen del Pedido</h3>
            @foreach($items as $item)
            <div class="flex justify-between py-2 text-sm border-b border-panis-100">
                <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                <span>S/ {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
            </div>
            @endforeach
            <div class="mt-4 space-y-1 text-sm">
                <div class="flex justify-between"><span>Subtotal</span><span>S/ {{ number_format($subtotal, 2) }}</span></div>
                @if($discount > 0)<div class="flex justify-between text-green-600"><span>Descuento</span><span>- S/ {{ number_format($discount, 2) }}</span></div>@endif
                <div class="flex justify-between"><span>IGV</span><span>S/ {{ number_format($tax, 2) }}</span></div>
                <div class="flex justify-between font-bold text-xl pt-2"><span>Total</span><span class="text-gold-600">S/ {{ number_format($total, 2) }}</span></div>
            </div>
            <button type="submit" class="btn-primary w-full mt-6">Confirmar Pedido</button>
        </div>
    </form>
</div>
@endsection
