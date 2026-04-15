<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function stockValuation()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->get()
            ->map(function ($p) {
                $p->stock_value = $p->quantity * $p->cost_price;
                $p->retail_value = $p->quantity * $p->selling_price;
                return $p;
            });

        $totalCostValue = $products->sum('stock_value');
        $totalRetailValue = $products->sum('retail_value');
        $totalPotentialProfit = $totalRetailValue - $totalCostValue;

        return view('reports.stock-valuation', compact('products', 'totalCostValue', 'totalRetailValue', 'totalPotentialProfit'));
    }

    public function movingItems(Request $request)
    {
        $days = $request->get('days', 30);

        $itemSales = SaleItem::selectRaw('product_id, SUM(quantity) as total_sold, SUM(quantity * unit_price) as total_revenue')
            ->whereHas('sale', fn($q) => $q->where('created_at', '>=', now()->subDays($days)))
            ->groupBy('product_id')
            ->with('product.category')
            ->get()
            ->sortByDesc('total_sold');

        $fastMoving = $itemSales->take(10);
        $slowMoving = $itemSales->reverse()->take(10);

        $noMovement = Product::where('is_active', true)
            ->whereNotIn('id', $itemSales->pluck('product_id'))
            ->with('category')
            ->get();

        return view('reports.moving-items', compact('fastMoving', 'slowMoving', 'noMovement', 'days'));
    }

    public function shrinkage(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $shrinkageMovements = StockMovement::with(['product.category', 'user'])
            ->where('type', 'shrinkage')
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->get();

        $totalShrinkageUnits = $shrinkageMovements->sum(fn($m) => abs($m->quantity));
        $totalShrinkageValue = $shrinkageMovements->sum(function ($m) {
            return abs($m->quantity) * $m->product->cost_price;
        });

        return view('reports.shrinkage', compact('shrinkageMovements', 'totalShrinkageUnits', 'totalShrinkageValue', 'from', 'to'));
    }

    public function sales(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $sales = Sale::with(['items.product', 'user'])
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->latest()
            ->get();

        $totalRevenue = $sales->sum('total_amount');
        $totalOrders = $sales->count();

        $dailySales = $sales->groupBy(fn($s) => $s->created_at->format('Y-m-d'))
            ->map(fn($group) => ['count' => $group->count(), 'total' => $group->sum('total_amount')]);

        $topProducts = SaleItem::selectRaw('product_id, SUM(quantity) as total_qty, SUM(quantity * unit_price) as total_revenue')
            ->whereHas('sale', fn($q) => $q->whereBetween('created_at', [$from, $to . ' 23:59:59']))
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get();

        return view('reports.sales', compact('sales', 'totalRevenue', 'totalOrders', 'dailySales', 'topProducts', 'from', 'to'));
    }
}
