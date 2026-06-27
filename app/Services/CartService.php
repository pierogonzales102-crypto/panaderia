<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    private const SESSION_KEY = 'panis_cart';

    public function items(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public function add(int $productId, int $quantity = 1, ?string $customization = null): void
    {
        $product = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            throw new \RuntimeException('Stock insuficiente para '.$product->name);
        }

        $cart = $this->items();
        $key = $productId.($customization ? '-'.md5($customization) : '');

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'customization' => $customization,
                'image' => $product->image_url,
            ];
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function update(string $key, int $quantity): void
    {
        $cart = $this->items();

        if (! isset($cart[$key])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            $product = Product::find($cart[$key]['product_id']);
            if ($product && $product->stock < $quantity) {
                throw new \RuntimeException('Stock insuficiente');
            }
            $cart[$key]['quantity'] = $quantity;
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(string $key): void
    {
        $cart = $this->items();
        unset($cart[$key]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return array_sum(array_column($this->items(), 'quantity'));
    }

    public function subtotal(): float
    {
        return array_reduce($this->items(), fn ($sum, $item) => $sum + ($item['price'] * $item['quantity']), 0.0);
    }

    public function tax(): float
    {
        return round($this->subtotal() * 0.18, 2);
    }

    public function total(): float
    {
        return $this->subtotal() + $this->tax();
    }

    public function applyDiscount(string $code): float
    {
        $discounts = ['PANIS10' => 0.10, 'DULCE15' => 0.15, 'HORNO20' => 0.20];

        return isset($discounts[strtoupper($code)]) ? round($this->subtotal() * $discounts[strtoupper($code)], 2) : 0;
    }
}
