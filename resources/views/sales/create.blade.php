<x-app-layout>
    <x-slot name="title">New Sale</x-slot>

    <div class="mt-2 max-w-3xl">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <form method="POST" action="{{ route('sales.store') }}" class="space-y-6" id="saleForm">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" placeholder="Walk-in customer"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <input type="text" name="notes" value="{{ old('notes') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Items -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">Sale Items</h3>
                        <button type="button" onclick="addItem()" class="text-xs text-indigo-600 font-medium hover:text-indigo-800">+ Add Item</button>
                    </div>
                    <div id="itemsContainer" class="space-y-3"></div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Total Amount</p>
                            <p class="text-2xl font-bold text-gray-900">$<span id="saleTotal">0.00</span></p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                        Record Sale
                    </button>
                    <a href="{{ route('sales.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const products = {!! $products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'selling_price' => (float)$p->selling_price, 'quantity' => (int)$p->quantity])->values()->toJson() !!};
        let itemIndex = 0;

        function addItem() {
            const container = document.getElementById('itemsContainer');
            const idx = itemIndex++;
            const opts = products.map(p => `<option value="${p.id}" data-price="${p.selling_price}" data-stock="${p.quantity}">${p.name} (${p.quantity} in stock)</option>`).join('');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-start';
            div.innerHTML = `
                <select name="items[${idx}][product_id]" required onchange="updatePrice(this, ${idx})"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Select product...</option>${opts}
                </select>
                <input type="number" name="items[${idx}][quantity]" placeholder="Qty" min="1" required
                    oninput="recalcTotal()"
                    class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <input type="number" id="price_${idx}" name="items[${idx}][unit_price]" placeholder="Price" min="0" step="0.01" required
                    oninput="recalcTotal()"
                    class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <button type="button" onclick="this.parentElement.remove(); recalcTotal()" class="mt-2 text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            container.appendChild(div);
        }

        function updatePrice(select, idx) {
            const opt = select.selectedOptions[0];
            const price = opt ? opt.dataset.price : '';
            document.getElementById('price_' + idx).value = price;
            recalcTotal();
        }

        function recalcTotal() {
            let total = 0;
            document.querySelectorAll('#itemsContainer > div').forEach(row => {
                const qty = parseFloat(row.querySelector('[name$="[quantity]"]')?.value) || 0;
                const price = parseFloat(row.querySelector('[name$="[unit_price]"]')?.value) || 0;
                total += qty * price;
            });
            document.getElementById('saleTotal').textContent = total.toFixed(2);
        }

        addItem();
    </script>
    @endpush
</x-app-layout>
