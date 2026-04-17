<x-app-layout>
    <x-slot name="title">Products</x-slot>
    <x-slot name="actions">
        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Product
        </a>
    </x-slot>

    <!-- Filters -->
    <div class="mt-2 mb-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or SKU..."
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-56">
            <select name="category_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="stock_status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Stock</option>
                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-900">Filter</button>
            @if(request()->hasAny(['search', 'category_id', 'stock_status']))
                <a href="{{ route('products.index') }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('products.show', $product) }}" class="font-medium text-gray-900 hover:text-indigo-600">{{ $product->name }}</a>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $product->category?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ $product->sku }}</td>
                        <td class="px-6 py-4 text-right text-gray-600">₦{{ number_format($product->cost_price, 2) }}</td>
                        <td class="px-6 py-4 text-right font-medium text-gray-900">₦{{ number_format($product->selling_price, 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($product->quantity == 0)
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">0</span>
                            @elseif($product->isLowStock())
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700">{{ $product->quantity }}</span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">{{ $product->quantity }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($product->is_active)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">Edit</a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Deactivate this product?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Deactivate</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            No products found. <a href="{{ route('products.create') }}" class="text-indigo-600 hover:underline">Add one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
