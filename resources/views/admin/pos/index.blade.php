@extends('layouts.admin')
@section('page-title', 'Punto de Venta (POS)')
@section('content')
<div x-data="posApp()" class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($products as $product)
            <button @click="addItem({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})" class="card p-4 text-left hover:ring-2 hover:ring-gold-400 transition">
                <p class="font-medium text-sm">{{ $product->name }}</p>
                <p class="text-gold-600 font-bold">S/ {{ number_format($product->price, 2) }}</p>
                <p class="text-xs text-panis-500">Stock: {{ $product->stock }}</p>
            </button>
            @endforeach
        </div>
    </div>
    <div class="card p-6 sticky top-6 h-fit">
        <h3 class="font-serif font-bold mb-4">🛒 Venta Actual</h3>
        <template x-if="items.length === 0"><p class="text-panis-500 text-sm">Selecciona productos</p></template>
        <div class="space-y-2 mb-4">
            <template x-for="(item, idx) in items" :key="idx">
                <div class="flex justify-between items-center text-sm bg-panis-50 p-2 rounded-lg">
                    <span x-text="item.name + ' x' + item.quantity"></span>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold" x-text="'S/ ' + (item.price * item.quantity).toFixed(2)"></span>
                        <button @click="items.splice(idx,1)" class="text-red-500">✕</button>
                    </div>
                </div>
            </template>
        </div>
        <div class="border-t pt-3 mb-4">
            <div class="flex justify-between font-bold text-lg"><span>Total</span><span class="text-gold-600" x-text="'S/ ' + total.toFixed(2)"></span></div>
        </div>
        <form action="{{ route('admin.pos.store') }}" method="POST" @submit="prepareForm">
            @csrf
            <div id="pos-items"></div>
            <select name="payment_method" class="input-field mb-3" required>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
            </select>
            <button type="submit" class="btn-primary w-full" :disabled="items.length === 0">Registrar Venta</button>
        </form>
    </div>
</div>
<script>
function posApp(){return{items:[],get total(){return this.items.reduce((s,i)=>s+i.price*i.quantity,0)},addItem(id,name,price,stock){const e=this.items.find(i=>i.product_id===id);if(e){if(e.quantity<stock)e.quantity++;}else this.items.push({product_id:id,name,price,quantity:1})},prepareForm(e){if(!this.items.length){e.preventDefault();return}const c=document.getElementById('pos-items');c.innerHTML='';this.items.forEach((item,i)=>{c.innerHTML+=`<input type="hidden" name="items[${i}][product_id]" value="${item.product_id}"><input type="hidden" name="items[${i}][quantity]" value="${item.quantity}">`})}}}
</script>
@endsection
