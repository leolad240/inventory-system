<x-app-layout>
    <x-slot name="title">Stock Valuation Report</x-slot>

    <!-- Summary Cards -->
    <div class="mt-2 grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Cost Value</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($totalCostValue, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Retail Value</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($totalRetailValue, 2) }}</p>
        </div>
        <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-5">
            <p class="text-xs text-emerald-600 uppercase tracking-wider mb-1">Potential Profit</p>
            <p class="text-2xl font-bold text-emerald-700">${{ number_format($totalPotentialProfit, 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Cost/unit</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Sell/unit</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Cost Value</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Retail Value</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Margin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-6 py-3 text-right">{{ $product->quantity }}</td>
                        <td class="px-6 py-3 text-right text-gray-600">${{ number_format($product->cost_price, 2) }}</td>
                        <td class="px-6 py-3 text-right text-gray-600">${{ number_format($product->selling_price, 2) }}</td>
                        <td class="px-6 py-3 text-right font-medium">${{ number_format($product->stock_value, 2) }}</td>
                        <td class="px-6 py-3 text-right font-medium">${{ number_format($product->retail_value, 2) }}</td>
                        <td class="px-6 py-3 text-right font-semibold {{ $product->cost_price > 0 && $product->selling_price > $product->cost_price ? 'text-emerald-600' : 'text-red-500' }}">
                            @if($product->cost_price > 0)
                                {{ number_format((($product->selling_price - $product->cost_price) / $product->cost_price) * 100, 1) }}%
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
