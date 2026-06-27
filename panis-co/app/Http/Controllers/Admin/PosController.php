<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Receipt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->where('is_available', true)->where('stock', '>', 0)->get();

        return view('admin.pos.index', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:tarjeta,efectivo,transferencia',
        ]);

        $order = DB::transaction(function () use ($request) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw new \RuntimeException("Stock insuficiente: {$product->name}");
                }
                $lineTotal = $product->price * $item['quantity'];
                $subtotal += $lineTotal;
                $itemsData[] = ['product' => $product, 'quantity' => $item['quantity'], 'lineTotal' => $lineTotal];
            }

            $tax = round($subtotal * 0.18, 2);
            $total = $subtotal + $tax;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'type' => 'estandar',
                'status' => 'entregado',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pagado',
                'delivery_address' => 'Venta en tienda',
            ]);

            foreach ($itemsData as $data) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $data['product']->id,
                    'product_name' => $data['product']->name,
                    'quantity' => $data['quantity'],
                    'unit_price' => $data['product']->price,
                    'subtotal' => $data['lineTotal'],
                ]);
                $data['product']->decrement('stock', $data['quantity']);
            }

            Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'method' => $request->payment_method,
                'status' => 'completado',
                'transaction_id' => 'POS-'.uniqid(),
            ]);

            Receipt::create([
                'order_id' => $order->id,
                'receipt_number' => Receipt::generateNumber(),
                'type' => 'boleta',
                'total' => $total,
            ]);

            return $order;
        });

        return redirect()->route('admin.pos.index')->with('success', "Venta registrada: {$order->order_number} - S/ ".number_format($order->total, 2));
    }
}
