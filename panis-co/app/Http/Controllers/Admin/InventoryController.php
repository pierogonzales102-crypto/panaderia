<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\InventoryMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        $ingredients = Ingredient::with('supplier')->orderBy('name')->get();
        $lowStock = $ingredients->filter(fn ($i) => $i->isLowStock());

        return view('admin.inventory.index', compact('ingredients', 'lowStock'));
    }

    public function storeMovement(Request $request): RedirectResponse
    {
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'type' => 'required|in:entrada,salida',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $ingredient = Ingredient::findOrFail($request->ingredient_id);

            if ($request->type === 'salida' && $ingredient->stock < $request->quantity) {
                throw new \RuntimeException('Stock insuficiente.');
            }

            InventoryMovement::create([
                'ingredient_id' => $request->ingredient_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'reason' => $request->reason,
                'user_id' => Auth::id(),
            ]);

            if ($request->type === 'entrada') {
                $ingredient->increment('stock', $request->quantity);
            } else {
                $ingredient->decrement('stock', $request->quantity);
            }
        });

        return back()->with('success', 'Movimiento registrado.');
    }

    public function storeIngredient(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:20',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        Ingredient::create($request->all());

        return back()->with('success', 'Insumo registrado.');
    }
}
