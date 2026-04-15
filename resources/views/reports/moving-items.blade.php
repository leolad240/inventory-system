<x-app-layout>
    <x-slot name="title">Fast / Slow Moving Items</x-slot>
    <x-slot name="actions">
        <form method="GET" class="flex items-center gap-2">
            <select name="days" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach([7, 14, 30, 60, 90] as $d)
                    <option value="{{ $d }}" {{ $days == $d ? 'selected' : '' }}>Last {{ $d }} days</option>
                @endforeach
            </select>
        </form>
    </x-slot>

    <div class="mt-2 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Fast Moving -->
        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Top 10 Fast Moving Items
            </h3>
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase">Sold</th>
                            <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($fastMoving as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $item->product->name }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-600">{{ $item->total_sold }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">${{ number_format($item->total_revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">No sales data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Slow Moving -->
        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                Slowest Moving Items
            </h3>
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase">Sold</th>
                            <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($slowMoving as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $item->product->name }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-amber-600">{{ $item->total_sold }}</td>
                                <td class="px-4 py-3 text-right text-gray-600">${{ number_format($item->total_revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">No sales data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- No Movement -->
        @if($noMovement->isNotEmpty())
            <div class="lg:col-span-2">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    No Movement in {{ $days }} Days ({{ $noMovement->count() }} products)
                </h3>
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase">Stock</th>
                                <th class="text-right px-4 py-3 text-xs font-medium text-gray-500 uppercase">Stock Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($noMovement as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $product->category?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-right text-gray-600">{{ $product->quantity }}</td>
                                    <td class="px-4 py-3 text-right font-medium">${{ number_format($product->stock_value, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
