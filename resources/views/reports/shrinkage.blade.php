<x-app-layout>
    <x-slot name="title">Shrinkage Report</x-slot>
    <x-slot name="actions">
        <form method="GET" class="flex items-center gap-2">
            <input type="date" name="from" value="{{ $from }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <span class="text-gray-400 text-sm">to</span>
            <input type="date" name="to" value="{{ $to }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium">Apply</button>
        </form>
    </x-slot>

    <div class="mt-2 grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Units Lost</p>
            <p class="text-2xl font-bold text-red-600">{{ $totalShrinkageUnits }}</p>
        </div>
        <div class="bg-red-50 rounded-xl border border-red-200 p-5">
            <p class="text-xs text-red-500 uppercase tracking-wider mb-1">Total Value Lost</p>
            <p class="text-2xl font-bold text-red-700">₦{{ number_format($totalShrinkageValue, 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Units Lost</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Value Lost</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Notes</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Recorded By</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($shrinkageMovements as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $m->product->name }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $m->product->category?->name ?? '—' }}</td>
                        <td class="px-6 py-3 text-right font-semibold text-red-600">{{ abs($m->quantity) }}</td>
                        <td class="px-6 py-3 text-right font-semibold text-red-600">₦{{ number_format(abs($m->quantity) * $m->product->cost_price, 2) }}</td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $m->notes ?? '—' }}</td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $m->user?->name ?? 'System' }}</td>
                        <td class="px-6 py-3 text-gray-500 text-xs">{{ $m->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-gray-500">No shrinkage recorded in this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
