<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductionOrder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::whereIn('status', ['pendiente', 'confirmado'])->count(),
            'sales_today' => Order::whereDate('created_at', today())->where('payment_status', 'pagado')->sum('total'),
            'low_stock' => Ingredient::whereColumn('stock', '<=', 'min_stock')->count(),
            'products_count' => Product::count(),
            'production_pending' => ProductionOrder::where('status', 'pendiente')->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $lowStockItems = Ingredient::whereColumn('stock', '<=', 'min_stock')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockItems'));
    }
}
