<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecipeController extends Controller
{
    public function index(): View
    {
        $recipes = Recipe::with(['product', 'recipeIngredients.ingredient'])->get();
        $products = Product::where('is_available', true)->get();
        $ingredients = Ingredient::orderBy('name')->get();

        return view('admin.recipes.index', compact('recipes', 'products', 'ingredients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:recipes,product_id',
            'instructions' => 'nullable|string',
            'prep_time_minutes' => 'nullable|integer|min:1',
            'ingredient_id' => 'required|array|min:1',
            'quantity' => 'required|array|min:1',
        ]);

        $recipe = Recipe::create([
            'product_id' => $request->product_id,
            'instructions' => $request->instructions,
            'prep_time_minutes' => $request->prep_time_minutes,
        ]);

        foreach ($request->ingredient_id as $i => $ingredientId) {
            RecipeIngredient::create([
                'recipe_id' => $recipe->id,
                'ingredient_id' => $ingredientId,
                'quantity' => $request->quantity[$i],
                'unit' => Ingredient::find($ingredientId)?->unit ?? 'kg',
            ]);
        }

        return back()->with('success', 'Receta registrada.');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $recipe->recipeIngredients()->delete();
        $recipe->delete();

        return back()->with('success', 'Receta eliminada.');
    }
}
