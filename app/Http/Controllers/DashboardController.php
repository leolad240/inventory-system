<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->count();
        $outOfStockProducts = Product::where('is_active', true)
            ->where('quantity', 0)
            ->count();

        $totalStockValue = Product::where('is_active', true)
            ->selectRaw('SUM(quantity * cost_price) as total')
            ->value('total') ?? 0;

        $todaySales = Sale::whereDate('created_at', today())->sum('total_amount');
        $monthlySales = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $pendingOrders = PurchaseOrder::whereIn('status', ['draft', 'ordered'])->count();

        $recentSales = Sale::with('user')->latest()->take(5)->get();
        $recentMovements = StockMovement::with(['product', 'user'])->latest()->take(8)->get();

        $lowStockItems = Product::with('category')
            ->where('is_active', true)
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->orderBy('quantity')
            ->take(8)
            ->get();

        $salesChart = Sale::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M d');
            $chartData[] = round($salesChart[$date]->total ?? 0, 2);
        }

        return view('dashboard', compact(
            'totalProducts', 'lowStockProducts', 'outOfStockProducts',
            'totalStockValue', 'todaySales', 'monthlySales', 'pendingOrders',
            'recentSales', 'recentMovements', 'lowStockItems',
            'chartLabels', 'chartData'
        ));
    }
}
