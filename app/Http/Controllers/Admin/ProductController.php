<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ingredients_text' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_customizable' => 'boolean',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_customizable'] = $request->boolean('is_customizable');
        $data['is_available'] = $request->boolean('is_available', true);
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'ingredients_text' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_customizable' => 'boolean',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['is_customizable'] = $request->boolean('is_customizable');
        $data['is_available'] = $request->boolean('is_available');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado.');
    }
}
