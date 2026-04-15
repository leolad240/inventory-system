<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        $movements = $query->latest()->paginate(20)->withQueryString();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('stock.index', compact('movements', 'products'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:adjustment,shrinkage,return',
            'quantity' => 'required|integer|not_in:0',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);
        $newQty = $product->quantity + $request->quantity;

        if ($newQty < 0) {
            return back()->with('error', 'Adjustment would result in negative stock.');
        }

        $product->update(['quantity' => $newQty]);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'quantity' => $request->quantity,
            'notes' => $request->notes,
        ]);

        return redirect()->route('stock.index')->with('success', 'Stock adjusted successfully.');
    }
}
