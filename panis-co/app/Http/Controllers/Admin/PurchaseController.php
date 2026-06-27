<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function index(): View
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'creator'])->latest()->paginate(15);
        $suppliers = Supplier::where('is_active', true)->get();
        $ingredients = Ingredient::orderBy('name')->get();

        return view('admin.purchases.index', compact('purchaseOrders', 'suppliers', 'ingredients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'ingredient_id' => 'required|array|min:1',
            'quantity' => 'required|array|min:1',
            'unit_price' => 'required|array|min:1',
            'expected_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($request) {
            $total = 0;
            $items = [];

            foreach ($request->ingredient_id as $i => $ingredientId) {
                $qty = (float) $request->quantity[$i];
                $price = (float) $request->unit_price[$i];
                $subtotal = $qty * $price;
                $total += $subtotal;
                $items[] = compact('ingredientId', 'qty', 'price', 'subtotal');
            }

            $po = PurchaseOrder::create([
                'po_number' => PurchaseOrder::generatePoNumber(),
                'supplier_id' => $request->supplier_id,
                'status' => 'enviada',
                'total' => $total,
                'expected_date' => $request->expected_date,
                'created_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            foreach ($items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'ingredient_id' => $item['ingredientId'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }
        });

        return back()->with('success', 'Orden de compra creada.');
    }

    public function receive(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        if ($purchaseOrder->status === 'recibida') {
            return back()->with('error', 'Esta orden ya fue recibida.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            foreach ($purchaseOrder->items as $item) {
                $item->ingredient->increment('stock', $item->quantity);

                \App\Models\InventoryMovement::create([
                    'ingredient_id' => $item->ingredient_id,
                    'type' => 'entrada',
                    'quantity' => $item->quantity,
                    'reason' => 'Recepción OC '.$purchaseOrder->po_number,
                    'user_id' => Auth::id(),
                ]);
            }

            $purchaseOrder->update([
                'status' => 'recibida',
                'received_date' => now()->toDateString(),
            ]);
        });

        return back()->with('success', 'Insumos recibidos e inventario actualizado.');
    }
}
