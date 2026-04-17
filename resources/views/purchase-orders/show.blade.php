<x-app-layout>
    <x-slot name="title">{{ $purchaseOrder->po_number }}</x-slot>
    <x-slot name="actions">
        @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
            <form method="POST" action="{{ route('purchase-orders.receive', $purchaseOrder) }}">
                @csrf
                <button type="submit" onclick="return confirm('Mark as received? This will update stock levels.')"
                    class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors">
                    Receive Order
                </button>
            </form>
        @endif
        @if(!in_array($purchaseOrder->status, ['received', 'cancelled']))
            <form method="POST" action="{{ route('purchase-orders.destroy', $purchaseOrder) }}">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Cancel this order?')"
                    class="inline-flex items-center gap-2 border border-red-300 text-red-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors">
                    Cancel Order
                </button>
            </form>
        @endif
    </x-slot>

    <div class="mt-2 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Info -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-mono font-bold text-indigo-600">{{ $purchaseOrder->po_number }}</h2>
                    @php $colors = ['draft'=>'bg-gray-100 text-gray-600','ordered'=>'bg-blue-100 text-blue-700','received'=>'bg-green-100 text-green-700','partial'=>'bg-amber-100 text-amber-700','cancelled'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $colors[$purchaseOrder->status] ?? '' }}">{{ ucfirst($purchaseOrder->status) }}</span>
                </div>
                <div class="space-y-3 text-sm">
                    <div><p class="text-gray-500 text-xs">Supplier</p><p class="font-medium">{{ $purchaseOrder->supplier_name }}</p></div>
                    @if($purchaseOrder->supplier_contact)
                        <div><p class="text-gray-500 text-xs">Contact</p><p class="font-medium">{{ $purchaseOrder->supplier_contact }}</p></div>
                    @endif
                    @if($purchaseOrder->supplier_email)
                        <div><p class="text-gray-500 text-xs">Email</p><p class="font-medium">{{ $purchaseOrder->supplier_email }}</p></div>
                    @endif
                    <div><p class="text-gray-500 text-xs">Order Date</p><p class="font-medium">{{ $purchaseOrder->order_date->format('M d, Y') }}</p></div>
                    @if($purchaseOrder->expected_date)
                        <div><p class="text-gray-500 text-xs">Expected</p><p class="font-medium">{{ $purchaseOrder->expected_date->format('M d, Y') }}</p></div>
                    @endif
                    @if($purchaseOrder->received_date)
                        <div><p class="text-gray-500 text-xs">Received</p><p class="font-medium text-emerald-600">{{ $purchaseOrder->received_date->format('M d, Y') }}</p></div>
                    @endif
                    <div><p class="text-gray-500 text-xs">Created by</p><p class="font-medium">{{ $purchaseOrder->user?->name ?? 'System' }}</p></div>
                    @if($purchaseOrder->notes)
                        <div><p class="text-gray-500 text-xs">Notes</p><p class="text-gray-700">{{ $purchaseOrder->notes }}</p></div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Order Items</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 uppercase">Ordered</th>
                            <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 uppercase">Received</th>
                            <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                            <th class="text-right px-5 py-3 text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($purchaseOrder->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 font-medium text-gray-900">{{ $item->product->name }}</td>
                                <td class="px-5 py-3 text-right text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-5 py-3 text-right {{ $item->received_quantity >= $item->quantity ? 'text-emerald-600 font-medium' : 'text-gray-600' }}">{{ $item->received_quantity }}</td>
                                <td class="px-5 py-3 text-right text-gray-600">₦{{ number_format($item->unit_cost, 2) }}</td>
                                <td class="px-5 py-3 text-right font-semibold">₦{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="4" class="px-5 py-3 text-right text-sm font-semibold text-gray-700">Total</td>
                            <td class="px-5 py-3 text-right text-sm font-bold">₦{{ number_format($purchaseOrder->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
