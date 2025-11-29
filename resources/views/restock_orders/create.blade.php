<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            {{ __('CREATE PURCHASE ORDER (PO)') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8" data-aos="fade-up">

            {{-- Menampilkan Error Validasi Global --}}
            @if ($errors->any())
                <div class="bg-neon-red/20 border border-neon-red text-white p-4 rounded mb-4">
                    <h4 class="font-bold mb-2">PO Creation Failed: Review Input Data</h4>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route(auth()->user()->role . '.restock_orders.store') }}">
                @csrf

                <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3">Order Header</h3>

                {{-- Supplier dan Delivery Date --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <x-input-label for="supplier_id" :value="__('Select Supplier')" class="text-electric-cyan" />
                        <select id="supplier_id" name="supplier_id" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm" required>
                            <option value="">Choose Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('supplier_id')" />
                    </div>
                    <div>
                        <x-input-label for="expected_delivery_date" :value="__('Expected Delivery Date')" class="text-electric-cyan" />
                        <x-text-input id="expected_delivery_date" name="expected_delivery_date" type="date" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('expected_delivery_date')" />
                        <x-input-error class="mt-2" :messages="$errors->get('expected_delivery_date')" />
                    </div>
                </div>

                {{-- Notes --}}
                <div class="mb-6">
                    <x-input-label for="notes" :value="__('Order Notes')" class="text-electric-cyan" />
                    <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm">{{ old('notes') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                </div>
                
                {{-- Item List --}}
                <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3 mt-8 flex justify-between items-center">
                    Items to Restock
                    <button type="button" id="add-item-btn" class="bg-electric-cyan hover:bg-cyan-600 text-dark-charcoal text-sm font-bold py-1 px-3 rounded transition duration-300">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                </h3>
                
                <div id="item-list-container">
                    {{-- Items akan ditambahkan di sini --}}
                </div>
                
                <div class="flex items-center justify-end mt-8">
                    <a href="{{ route(auth()->user()->role . '.restock_orders.index') }}" class="text-gray-400 hover:text-white mr-4 transition duration-300">
                        Cancel
                    </a>

                    <x-primary-button id="submit-btn" class="bg-neon-green hover:bg-green-600 text-dark-charcoal font-bold py-2 px-4 disabled:opacity-50">
                        {{ __('Send Purchase Order') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Script untuk dynamic form items (Mirip dengan Transaksi, tapi lebih sederhana) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const products = @json($products);
        let itemIndex = 0;

        function addItem() {
            const html = `
                <div class="item-row p-4 border border-gray-700 rounded-lg mb-4" data-index="${itemIndex}">
                    <div class="flex justify-end">
                        <button type="button" class="remove-item-btn text-neon-red hover:text-red-400" data-index="${itemIndex}"><i class="fas fa-trash-alt"></i> Remove</button>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <x-input-label for="product_${itemIndex}" value="Product" class="text-electric-cyan" />
                            <select id="product_${itemIndex}" name="product_id[]" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm product-select" data-index="${itemIndex}" required>
                                <option value="">Select Product (SKU, Unit)</option>
                                ${products.map(p => `<option value="${p.id}">${p.sku} - ${p.name} (${p.unit})</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <x-input-label for="quantity_${itemIndex}" value="Quantity" class="text-electric-cyan" />
                            <x-text-input id="quantity_${itemIndex}" name="quantity[]" type="number" min="1" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white item-quantity" required />
                        </div>
                    </div>
                </div>
            `;
            $('#item-list-container').append(html);
            itemIndex++;
        }

        $(document).ready(function() {
            // Event listener untuk tombol Add Item
            $('#add-item-btn').on('click', addItem);

            // Event listener untuk tombol Remove Item (menggunakan delegation)
            $(document).on('click', '.remove-item-btn', function() {
                $(this).closest('.item-row').remove();
            });
            
            // Tambahkan 1 item default
            if ($('.item-row').length === 0) {
                 addItem();
            }
        });
    </script>
</x-app-layout>