<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductionOrder;
use App\Models\Product;
use App\Models\Receipt;
use App\Services\CartService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function checkout(): View|RedirectResponse
    {
        if (empty($this->cart->items())) {
            return redirect()->route('catalog.index')->with('error', 'Tu carrito está vacío.');
        }

        $discount = session('cart_discount', 0);

        return view('orders.checkout', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
            'discount' => $discount,
            'tax' => $this->cart->tax(),
            'total' => $this->cart->total() - $discount,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_method' => 'required|in:tarjeta,efectivo,transferencia',
            'delivery_address' => 'required|string|max:500',
        ]);

        if (empty($this->cart->items())) {
            return redirect()->route('catalog.index')->with('error', 'Tu carrito está vacío.');
        }

        $order = DB::transaction(function () use ($request) {
            $discount = session('cart_discount', 0);
            $subtotal = $this->cart->subtotal();
            $tax = $this->cart->tax();
            $total = $subtotal + $tax - $discount;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'type' => 'estandar',
                'status' => 'pendiente',
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'discount_code' => session('cart_discount_code'),
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'efectivo' ? 'pendiente' : 'pagado',
                'delivery_address' => $request->delivery_address,
                'notes' => $request->notes,
            ]);

            foreach ($this->cart->items() as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'customization' => $item['customization'] ?? null,
                ]);

                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);

                ProductionOrder::create([
                    'product_id' => $item['product_id'],
                    'order_id' => $order->id,
                    'quantity' => $item['quantity'],
                    'status' => 'pendiente',
                    'production_date' => now()->toDateString(),
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'method' => $request->payment_method,
                'status' => $request->payment_method === 'efectivo' ? 'pendiente' : 'completado',
                'transaction_id' => 'TXN-'.uniqid(),
            ]);

            Receipt::create([
                'order_id' => $order->id,
                'receipt_number' => Receipt::generateNumber(),
                'type' => 'boleta',
                'total' => $total,
            ]);

            return $order;
        });

        $this->cart->clear();
        session()->forget(['cart_discount', 'cart_discount_code']);

        return redirect()->route('orders.show', $order)->with('success', '¡Pedido realizado con éxito!');
    }

    public function index(): View
    {
        $orders = Auth::user()->orders()->with('items')->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === Auth::id() || Auth::user()->isAdmin(), 403);

        $order->load(['items.product', 'payment', 'receipt']);

        return view('orders.show', compact('order'));
    }

    public function receipt(Order $order)
    {
        abort_unless($order->user_id === Auth::id() || Auth::user()->isAdmin(), 403);

        $order->load(['items', 'user', 'receipt']);

        $pdf = Pdf::loadView('orders.receipt-pdf', compact('order'));

        return $pdf->download('comprobante-'.$order->order_number.'.pdf');
    }
}
