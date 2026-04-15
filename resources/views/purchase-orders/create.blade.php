<x-app-layout>
    <x-slot name="title">New Purchase Order</x-slot>

    <div class="mt-2 max-w-3xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <form method="POST" action="{{ route('purchase-orders.store') }}" class="space-y-6" id="poForm">
                @csrf

                <!-- Supplier Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Supplier Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier Name <span class="text-red-500">*</span></label>
                            <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                            <input type="text" name="supplier_contact" value="{{ old('supplier_contact') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="supplier_email" value="{{ old('supplier_email') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order Date <span class="text-red-500">*</span></label>
                            <input type="date" name="order_date" value="{{ old('order_date', today()->format('Y-m-d')) }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Delivery</label>
                            <input type="date" name="expected_date" value="{{ old('expected_date') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">Order Items</h3>
                        <button type="button" onclick="addItem()" class="text-xs text-indigo-600 font-medium hover:text-indigo-800">+ Add Item</button>
                    </div>
                    <div id="itemsContainer" class="space-y-3">
                        <!-- Items added dynamically -->
                    </div>
                    <div class="mt-3 text-right">
                        <span class="text-sm font-semibold text-gray-700">Total: $<span id="orderTotal">0.00</span></span>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                        Create Purchase Order
                    </button>
                    <a href="{{ route('purchase-orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const products = {!! $products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'cost_price' => (float)$p->cost_price])->values()->toJson() !!};
        let itemIndex = 0;

        function addItem() {
            const container = document.getElementById('itemsContainer');
            const idx = itemIndex++;
            const opts = products.map(p => `<option value="${p.id}" data-cost="${p.cost_price}">${p.name}</option>`).join('');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-start';
            div.innerHTML = `
                <select name="items[${idx}][product_id]" required onchange="updateCost(this, ${idx})"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select product...</option>${opts}
                </select>
                <input type="number" name="items[${idx}][quantity]" placeholder="Qty" min="1" required
                    oninput="recalcTotal()"
                    class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <input type="number" id="cost_${idx}" name="items[${idx}][unit_cost]" placeholder="Unit cost" min="0" step="0.01" required
                    oninput="recalcTotal()"
                    class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <button type="button" onclick="this.parentElement.remove(); recalcTotal()" class="mt-2 text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            container.appendChild(div);
        }

        function updateCost(select, idx) {
            const opt = select.selectedOptions[0];
            const cost = opt ? opt.dataset.cost : '';
            document.getElementById('cost_' + idx).value = cost;
            recalcTotal();
        }

        function recalcTotal() {
            let total = 0;
            document.querySelectorAll('#itemsContainer > div').forEach(row => {
                const qty = parseFloat(row.querySelector('[name$="[quantity]"]')?.value) || 0;
                const cost = parseFloat(row.querySelector('[name$="[unit_cost]"]')?.value) || 0;
                total += qty * cost;
            });
            document.getElementById('orderTotal').textContent = total.toFixed(2);
        }

        // Add first row on load
        addItem();
    </script>
    @endpush
</x-app-layout>
