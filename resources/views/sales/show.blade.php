<x-app-layout>
    <x-slot name="title">{{ $sale->sale_number }}</x-slot>

    <div class="mt-2 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sale Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="mb-4">
                    <h2 class="font-mono font-bold text-indigo-600 text-lg">{{ $sale->sale_number }}</h2>
                    <p class="text-xs text-gray-500">{{ $sale->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div class="space-y-3 text-sm">
                    <div><p class="text-gray-500 text-xs">Customer</p><p class="font-medium">{{ $sale->customer_name ?? 'Walk-in' }}</p></div>
                    <div><p class="text-gray-500 text-xs">Sold by</p><p class="font-medium">{{ $sale->user?->name ?? 'System' }}</p></div>
                    @if($sale->notes)
                        <div><p class="text-gray-500 text-xs">Notes</p><p class="text-gray-700">{{ $sale->notes }}</p></div>
                    @endif
                    <div class="border-t border-gray-100 pt-3">
                        <p class="text-gray-500 text-xs">Total Amount</p>
                        <p class="text-2xl font-bold text-emerald-600">₦{{ number_format($sale->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Sale Items</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                            <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($sale->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 font-medium text-gray-900">{{ $item->product->name }}</td>
                                <td class="px-5 py-3 text-right text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-5 py-3 text-right text-gray-600">₦{{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-5 py-3 text-right font-semibold">₦{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="px-5 py-3 text-right text-sm font-semibold text-gray-700">Total</td>
                            <td class="px-5 py-3 text-right text-sm font-bold text-emerald-600">₦{{ number_format($sale->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
