<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CustomOrderController extends Controller
{
    public function create(): View
    {
        $products = Product::where('is_customizable', true)->where('is_available', true)->get();

        return view('orders.custom', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customization_details' => 'required|string|max:2000',
            'delivery_date' => 'required|date|after:today',
            'delivery_address' => 'required|string|max:500',
            'reference_image' => 'nullable|image|max:4096',
        ]);

        $product = Product::findOrFail($request->product_id);
        $imagePath = null;

        if ($request->hasFile('reference_image')) {
            $imagePath = $request->file('reference_image')->store('custom-orders', 'public');
        }

        $estimatedPrice = $product->price * 1.5;

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => Auth::id(),
            'type' => 'personalizado',
            'status' => 'pendiente',
            'subtotal' => $estimatedPrice,
            'tax' => round($estimatedPrice * 0.18, 2),
            'total' => round($estimatedPrice * 1.18, 2),
            'payment_status' => 'pendiente',
            'delivery_address' => $request->delivery_address,
            'delivery_date' => $request->delivery_date,
            'custom_instructions' => $request->custom_instructions,
            'customization_details' => $request->customization_details,
            'reference_image' => $imagePath,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name.' (Personalizado)',
            'quantity' => 1,
            'unit_price' => $estimatedPrice,
            'subtotal' => $estimatedPrice,
            'customization' => $request->customization_details,
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Solicitud de pedido personalizado enviada. Te contactaremos para confirmar el precio final.');
    }
}
