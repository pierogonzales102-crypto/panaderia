<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderManageController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductionController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\RecipeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalogo/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/carrito/descuento', [CartController::class, 'applyDiscount'])->name('cart.discount');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/pedidos', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/mis-pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/pedidos/{order}/comprobante', [OrderController::class, 'receipt'])->name('orders.receipt');

    Route::get('/pedido-personalizado', [CustomOrderController::class, 'create'])->name('orders.custom');
    Route::post('/pedido-personalizado', [CustomOrderController::class, 'store'])->name('orders.custom.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:administrador,gerente,moderador_web')->group(function () {
        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::middleware('role:administrador,gerente,ventas,moderador_web')->group(function () {
        Route::get('orders', [OrderManageController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderManageController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [OrderManageController::class, 'updateStatus'])->name('orders.status');
        Route::get('pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('pos', [PosController::class, 'store'])->name('pos.store');
    });

    Route::middleware('role:administrador,gerente,produccion')->group(function () {
        Route::get('production', [ProductionController::class, 'index'])->name('production.index');
        Route::post('production', [ProductionController::class, 'store'])->name('production.store');
        Route::patch('production/{productionOrder}/status', [ProductionController::class, 'updateStatus'])->name('production.status');
        Route::get('recipes', [RecipeController::class, 'index'])->name('recipes.index');
        Route::post('recipes', [RecipeController::class, 'store'])->name('recipes.store');
        Route::delete('recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    });

    Route::middleware('role:administrador,gerente')->group(function () {
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('inventory/movement', [InventoryController::class, 'storeMovement'])->name('inventory.movement');
        Route::post('inventory/ingredient', [InventoryController::class, 'storeIngredient'])->name('inventory.ingredient');
        Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::put('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::post('purchases', [PurchaseController::class, 'store'])->name('purchases.store');
        Route::patch('purchases/{purchaseOrder}/receive', [PurchaseController::class, 'receive'])->name('purchases.receive');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });
});

require __DIR__.'/auth.php';
