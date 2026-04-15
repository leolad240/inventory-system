<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('user');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_number', 'like', "%{$request->search}%")
                  ->orWhere('customer_name', 'like', "%{$request->search}%");
            });
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sales = $query->latest()->paginate(15)->withQueryString();
        $totalRevenue = Sale::sum('total_amount');

        return view('sales.index', compact('sales', 'totalRevenue'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->where('quantity', '>', 0)->orderBy('name')->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}.");
                }
            }

            $total = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_price']);

            $sale = Sale::create([
                'sale_number' => Sale::generateSaleNumber(),
                'customer_name' => $request->customer_name,
                'total_amount' => $total,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);

                $product = Product::find($item['product_id']);
                $product->decrement('quantity', $item['quantity']);

                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'user_id' => auth()->id(),
                    'type' => 'sale',
                    'quantity' => -$item['quantity'],
                    'reference' => $sale->sale_number,
                    'notes' => "Sale: {$sale->sale_number}",
                ]);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'user']);
        return view('sales.show', compact('sale'));
    }
}
