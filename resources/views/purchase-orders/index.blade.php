<x-app-layout>
    <x-slot name="title">Purchase Orders</x-slot>
    <x-slot name="actions">
        <a href="{{ route('purchase-orders.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Order
        </a>
    </x-slot>

    <div class="mt-2 mb-4">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search PO# or supplier..."
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 w-56">
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Status</option>
                @foreach(['draft', 'ordered', 'received', 'partial', 'cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('purchase-orders.index') }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">PO Number</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Supplier</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Order Date</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Expected</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs font-semibold text-indigo-600">{{ $order->po_number }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $order->supplier_name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $order->order_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $order->expected_date?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4 text-right font-semibold">₦{{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4 text-center">
                            @php $colors = ['draft'=>'bg-gray-100 text-gray-600','ordered'=>'bg-blue-100 text-blue-700','received'=>'bg-green-100 text-green-700','partial'=>'bg-amber-100 text-amber-700','cancelled'=>'bg-red-100 text-red-700']; @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $colors[$order->status] ?? '' }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('purchase-orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No purchase orders yet. <a href="{{ route('purchase-orders.create') }}" class="text-indigo-600 hover:underline">Create one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
