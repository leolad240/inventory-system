<x-app-layout>
    <x-slot name="title">Stock Management</x-slot>

    <div class="mt-2 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Adjust Stock Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Stock Adjustment</h3>
                <form method="POST" action="{{ route('stock.adjust') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product <span class="text-red-500">*</span></label>
                        <select name="product_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->quantity }} in stock)</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="adjustment">Manual Adjustment</option>
                            <option value="shrinkage">Shrinkage / Loss</option>
                            <option value="return">Customer Return</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-500 mb-1">Use negative to reduce stock (e.g. -5)</p>
                        <input type="number" name="quantity" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="e.g. +10 or -5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Reason for adjustment..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                        Apply Adjustment
                    </button>
                </form>
            </div>
        </div>

        <!-- Movement History -->
        <div class="lg:col-span-2">
            <div class="mb-3">
                <form method="GET" class="flex gap-3">
                    <select name="type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Types</option>
                        @foreach(['purchase', 'sale', 'adjustment', 'shrinkage', 'return'] as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                    <select name="product_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium">Filter</button>
                    @if(request()->hasAny(['type', 'product_id']))
                        <a href="{{ route('stock.index') }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Clear</a>
                    @endif
                </form>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">By</th>
                            <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($movements as $m)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 font-medium text-gray-900">{{ $m->product->name }}</td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-0.5 text-xs font-medium rounded capitalize
                                        {{ $m->type === 'purchase' ? 'bg-blue-100 text-blue-700' :
                                           ($m->type === 'sale' ? 'bg-purple-100 text-purple-700' :
                                           ($m->type === 'shrinkage' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                                        {{ $m->type }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right font-semibold {{ $m->quantity > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}
                                </td>
                                <td class="px-5 py-3 text-gray-500 text-xs">{{ $m->user?->name ?? 'System' }}</td>
                                <td class="px-5 py-3 text-gray-500 text-xs">{{ $m->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500">No movements found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $movements->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
