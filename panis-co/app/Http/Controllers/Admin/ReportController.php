<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to = $request->get('to', now()->toDateString());

        $sales = Order::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->where('payment_status', 'pagado');

        $stats = [
            'total_sales' => (clone $sales)->sum('total'),
            'orders_count' => (clone $sales)->count(),
            'avg_order' => (clone $sales)->avg('total') ?? 0,
        ];

        $salesByDay = Order::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->where('payment_status', 'pagado')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = OrderItem::whereHas('order', function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
                ->where('payment_status', 'pagado');
        })
            ->select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as revenue'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        $recentSales = Order::with('user')
            ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->latest()
            ->take(20)
            ->get();

        return view('admin.reports.index', compact('stats', 'salesByDay', 'topProducts', 'recentSales', 'from', 'to'));
    }
}
