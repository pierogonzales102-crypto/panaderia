<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index(): View
    {
        return view('cart.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'tax' => $this->cart->tax(),
            'total' => $this->cart->total(),
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->cart->add($request->product_id, $request->quantity);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Producto añadido al carrito.');
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        try {
            $this->cart->update($key, (int) $request->quantity);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Carrito actualizado.');
    }

    public function remove(string $key): RedirectResponse
    {
        $this->cart->remove($key);

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function clear(): RedirectResponse
    {
        $this->cart->clear();

        return back()->with('success', 'Carrito vaciado.');
    }

    public function applyDiscount(Request $request): RedirectResponse
    {
        $discount = $this->cart->applyDiscount($request->code ?? '');

        if ($discount <= 0) {
            return back()->with('error', 'Código de descuento inválido.');
        }

        session(['cart_discount' => $discount, 'cart_discount_code' => strtoupper($request->code)]);

        return back()->with('success', 'Descuento aplicado: S/ '.number_format($discount, 2));
    }
}
