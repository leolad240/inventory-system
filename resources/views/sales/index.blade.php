<x-app-layout>
    <x-slot name="title">Sales</x-slot>
    <x-slot name="actions">
        <a href="{{ route('sales.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Sale
        </a>
    </x-slot>

    <!-- Summary -->
    <div class="mt-2 mb-4 flex items-center gap-4">
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-2 text-sm">
            <span class="text-emerald-600 font-medium">Total Revenue: </span>
            <span class="text-emerald-800 font-bold">${{ number_format($totalRevenue, 2) }}</span>
        </div>
    </div>

    <div class="mb-4">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search sale# or customer..."
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-56">
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium">Filter</button>
            @if(request()->hasAny(['search', 'date_from', 'date_to']))
                <a href="{{ route('sales.index') }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Sale #</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">By</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-indigo-600">{{ $sale->sale_number }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $sale->customer_name ?? 'Walk-in' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $sale->items_count ?? '—' }}</td>
                        <td class="px-6 py-4 text-right font-semibold text-emerald-600">${{ number_format($sale->total_amount, 2) }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $sale->user?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-500 text-xs">{{ $sale->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('sales.show', $sale) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No sales yet. <a href="{{ route('sales.create') }}" class="text-indigo-600 hover:underline">Record a sale</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $sales->links() }}
        </div>
    </div>
</x-app-layout>
