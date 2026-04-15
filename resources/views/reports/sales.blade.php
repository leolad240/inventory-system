<x-app-layout>
    <x-slot name="title">Sales Report</x-slot>
    <x-slot name="actions">
        <form method="GET" class="flex items-center gap-2">
            <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <span class="text-gray-400 text-sm">to</span>
            <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium">Apply</button>
        </form>
    </x-slot>

    <!-- Summary -->
    <div class="mt-2 grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Orders</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
        </div>
        <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-5">
            <p class="text-xs text-emerald-600 uppercase tracking-wider mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-emerald-700">${{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Sales -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Daily Sales Breakdown</h3>
            @if($dailySales->isEmpty())
                <p class="text-sm text-gray-500 text-center py-4">No sales in this period.</p>
            @else
                <div class="space-y-2">
                    @foreach($dailySales->sortKeysDesc() as $date => $data)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>
                            <div class="text-right">
                                <span class="font-semibold">${{ number_format($data['total'], 2) }}</span>
                                <span class="text-gray-400 text-xs ml-2">({{ $data['count'] }} orders)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Top Products by Revenue</h3>
            @if($topProducts->isEmpty())
                <p class="text-sm text-gray-500 text-center py-4">No sales in this period.</p>
            @else
                <div class="space-y-3">
                    @foreach($topProducts as $i => $item)
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-gray-400 w-4">{{ $i + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $item->total_qty }} units sold</p>
                            </div>
                            <span class="text-sm font-semibold text-emerald-600">${{ number_format($item->total_revenue, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
