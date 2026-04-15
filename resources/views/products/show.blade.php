<x-app-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>
    <x-slot name="actions">
        <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
            Edit Product
        </a>
    </x-slot>

    <div class="mt-2 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Info -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h2>
                        <p class="text-sm text-gray-500 font-mono">{{ $product->sku }}</p>
                    </div>
                    @if($product->is_active)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Active</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">Inactive</span>
                    @endif
                </div>
                @if($product->description)
                    <p class="text-sm text-gray-600 mb-4">{{ $product->description }}</p>
                @endif
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Category</span><span class="font-medium">{{ $product->category?->name ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Unit</span><span class="font-medium capitalize">{{ $product->unit }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Cost Price</span><span class="font-medium">${{ number_format($product->cost_price, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Selling Price</span><span class="font-medium">${{ number_format($product->selling_price, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Margin</span>
                        @if($product->cost_price > 0)
                            <span class="font-medium text-emerald-600">{{ number_format((($product->selling_price - $product->cost_price) / $product->cost_price) * 100, 1) }}%</span>
                        @else
                            <span class="font-medium">—</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stock Status -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Stock Status</h3>
                <div class="text-center py-4">
                    <p class="text-4xl font-bold {{ $product->quantity == 0 ? 'text-red-600' : ($product->isLowStock() ? 'text-amber-600' : 'text-emerald-600') }}">
                        {{ $product->quantity }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">{{ ucfirst($product->unit) }}s in stock</p>
                </div>
                <div class="border-t border-gray-100 pt-3 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Reorder Level</span><span class="font-medium">{{ $product->reorder_level }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Stock Value</span><span class="font-medium">${{ number_format($product->stock_value, 2) }}</span></div>
                </div>
            </div>
        </div>

        <!-- Stock History -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Stock Movement History</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($product->stockMovements as $movement)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 text-xs font-medium rounded capitalize
                                    {{ $movement->type === 'purchase' ? 'bg-blue-100 text-blue-700' :
                                       ($movement->type === 'sale' ? 'bg-purple-100 text-purple-700' :
                                       ($movement->type === 'shrinkage' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                                    {{ $movement->type }}
                                </span>
                                @if($movement->reference)
                                    <span class="text-xs text-gray-500">{{ $movement->reference }}</span>
                                @endif
                            </div>
                            @if($movement->notes)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $movement->notes }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-0.5">{{ $movement->user?->name ?? 'System' }} &bull; {{ $movement->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <span class="text-sm font-bold {{ $movement->quantity > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-500">No movement history.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
