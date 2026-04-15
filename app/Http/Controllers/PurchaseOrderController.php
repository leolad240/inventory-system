<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('user');

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('po_number', 'like', "%{$request->search}%")
                  ->orWhere('supplier_name', 'like', "%{$request->search}%");
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('purchase-orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('purchase-orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'supplier_email' => 'nullable|email',
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_cost']);

            $order = PurchaseOrder::create([
                'po_number' => PurchaseOrder::generatePoNumber(),
                'supplier_name' => $request->supplier_name,
                'supplier_contact' => $request->supplier_contact,
                'supplier_email' => $request->supplier_email,
                'status' => 'ordered',
                'total_amount' => $total,
                'order_date' => $request->order_date,
                'expected_date' => $request->expected_date,
                'notes' => $request->notes,
                'user_id' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                ]);
            }
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order created successfully.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['items.product', 'user']);
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function receive(PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['ordered', 'partial'])) {
            return back()->with('error', 'This order cannot be received.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            foreach ($purchaseOrder->items as $item) {
                $remaining = $item->quantity - $item->received_quantity;
                if ($remaining > 0) {
                    $item->product->increment('quantity', $remaining);
                    $item->product->update(['cost_price' => $item->unit_cost]);
                    $item->update(['received_quantity' => $item->quantity]);

                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'user_id' => auth()->id(),
                        'type' => 'purchase',
                        'quantity' => $remaining,
                        'reference' => $purchaseOrder->po_number,
                        'notes' => "Received from PO: {$purchaseOrder->po_number}",
                    ]);
                }
            }

            $purchaseOrder->update([
                'status' => 'received',
                'received_date' => today(),
            ]);
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder)->with('success', 'Purchase order received successfully.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return back()->with('error', 'Cannot delete a received purchase order.');
        }
        $purchaseOrder->update(['status' => 'cancelled']);
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order cancelled.');
    }
}
