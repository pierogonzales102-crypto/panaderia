<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::with('category')
            ->where('is_available', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)->withCount('products')->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
