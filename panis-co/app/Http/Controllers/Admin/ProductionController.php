<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProductionOrder::with(['product', 'order', 'assignedUser']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('production_date', $request->date);
        } else {
            $query->whereDate('production_date', today());
        }

        $productionOrders = $query->latest()->paginate(20)->withQueryString();
        $products = Product::where('is_available', true)->get();

        return view('admin.production.index', compact('productionOrders', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'production_date' => 'required|date',
        ]);

        ProductionOrder::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'production_date' => $request->production_date,
            'status' => 'pendiente',
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Orden de producción creada.');
    }

    public function updateStatus(Request $request, ProductionOrder $productionOrder): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pendiente,en_proceso,completado,cancelado',
        ]);

        $productionOrder->update(['status' => $request->status]);

        if ($request->status === 'completado') {
            $productionOrder->product->increment('stock', $productionOrder->quantity);
        }

        return back()->with('success', 'Estado de producción actualizado.');
    }
}
