<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <x-slot name="actions">
        <a href="{{ route('sales.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Sale
        </a>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 mt-2">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Products</span>
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
            <p class="text-xs text-gray-500 mt-1">Active products</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Value</span>
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($totalStockValue, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">At cost price</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Today's Sales</span>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($todaySales, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Monthly: ${{ number_format($monthlySales, 2) }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Low Stock</span>
                <div class="w-8 h-8 {{ $lowStockProducts > 0 ? 'bg-amber-100' : 'bg-gray-100' }} rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 {{ $lowStockProducts > 0 ? 'text-amber-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $lowStockProducts }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $outOfStockProducts }} out of stock</p>
        </div>
    </div>

    <!-- Charts + Low Stock -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Sales (Last 7 Days)</h3>
            <canvas id="salesChart" height="100"></canvas>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Low Stock Alert</h3>
                <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @if($lowStockItems->isEmpty())
                <p class="text-sm text-gray-500 text-center py-4">All stock levels are healthy!</p>
            @else
                <div class="space-y-3">
                    @foreach($lowStockItems as $item)
                        <div class="flex items-center justify-between">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->name }}</p>
                                <p class="text-xs text-gray-500">Min: {{ $item->reorder_level }}</p>
                            </div>
                            <span class="ml-2 px-2 py-1 text-xs font-bold rounded-full {{ $item->quantity == 0 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $item->quantity }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Sales -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Recent Sales</h3>
                <a href="{{ route('sales.index') }}" class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @if($recentSales->isEmpty())
                <p class="text-sm text-gray-500 text-center py-4">No sales yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($recentSales as $sale)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $sale->sale_number }}</p>
                                <p class="text-xs text-gray-500">{{ $sale->customer_name ?? 'Walk-in' }} &bull; {{ $sale->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-sm font-semibold text-emerald-600">${{ number_format($sale->total_amount, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Stock Movements -->
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">Recent Stock Movements</h3>
                <a href="{{ route('stock.index') }}" class="text-xs text-indigo-600 hover:underline">View all</a>
            </div>
            @if($recentMovements->isEmpty())
                <p class="text-sm text-gray-500 text-center py-4">No movements yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($recentMovements as $movement)
                        <div class="flex items-center justify-between">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $movement->product->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ $movement->type }} &bull; {{ $movement->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="ml-2 text-sm font-semibold {{ $movement->quantity > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.08)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { callback: v => '$' + v }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
