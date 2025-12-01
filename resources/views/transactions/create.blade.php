<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <div id="transactions-header-target" 
                 class="typing-target" 
                 data-text="{{ __('NEW RECORD TRANSACTION') }}" 
                 style="min-height: 25px;">
            </div>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8" data-aos="fade-up">

            {{-- Menampilkan Error Stok jika ada --}}
            @if(session('errors') && session('errors')->has('stok'))
                <div class="bg-neon-red/20 border border-neon-red text-white p-4 rounded mb-4">
                    {{ session('errors')->first('stok') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('staff.transactions.store') }}">
                @csrf
                
                {{-- Menampilkan Error Validasi Global (seperti item yang kosong) --}}
                @if ($errors->any())
                    <div class="bg-neon-red/20 border border-neon-red text-white p-4 rounded mb-4">
                        <h4 class="font-bold mb-2">Transaction Failed: Review Input Data</h4>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Menampilkan Error Stok jika ada (kode ini sudah ada) --}}
                @if(session('errors') && session('errors')->has('stok'))
                    <div class="bg-neon-red/20 border border-neon-red text-white p-4 rounded mb-4">
                        {{ session('errors')->first('stok') }}
                    </div>
                @endif

                <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3">Transaction Details</h3>

                {{-- Baris 1: Type dan Date --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <x-input-label for="type" :value="__('Transaction Type')" class="text-electric-cyan" />
                        <select id="type" name="type" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm" required>
                            <option value="">Select Type</option>
                            <option value="incoming" {{ old('type') == 'incoming' ? 'selected' : '' }}>Incoming (Barang Masuk)</option>
                            <option value="outgoing" {{ old('type') == 'outgoing' ? 'selected' : '' }}>Outgoing (Barang Keluar)</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('type')" />
                    </div>
                    <div>
                        <x-input-label for="transaction_date" :value="__('Date')" class="text-electric-cyan" />
                        <x-text-input id="transaction_date" name="transaction_date" type="date" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('transaction_date', now()->toDateString())" required />
                        <x-input-error class="mt-2" :messages="$errors->get('transaction_date')" />
                    </div>
                </div>

                {{-- Dynamic Field Area --}}
                <div id="dynamic-fields">
                    {{-- Diisi oleh JS --}}
                </div>

                {{-- Baris 3: Notes --}}
                <div class="mb-6">
                    <x-input-label for="notes" :value="__('Notes/Reference')" class="text-electric-cyan" />
                    <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm">{{ old('notes') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                </div>
                
                {{-- Item List --}}
                <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3 mt-8 flex justify-between items-center">
                    Transaction Items
                    <button type="button" id="add-item-btn" class="bg-electric-cyan hover:bg-cyan-600 text-dark-charcoal text-sm font-bold py-1 px-3 rounded transition duration-300 disabled:opacity-50">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </h3>
                
                <div id="item-list-container">
                    {{-- Items akan ditambahkan di sini --}}
                </div>
                
                <div class="flex items-center justify-end mt-8">
                    <a href="{{ route('staff.transactions.index') }}" class="text-gray-400 hover:text-white mr-4 transition duration-300">
                        Cancel
                    </a>

                    <x-primary-button id="submit-btn" class="bg-neon-green hover:bg-green-600 text-dark-charcoal font-bold py-2 px-4 disabled:opacity-50" disabled>
                        {{ __('Record Transaction') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Script untuk dynamic form items --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const products = @json($products);
        const suppliers = @json($suppliers);
        let itemIndex = 0;

        // Fungsi untuk mengupdate field dinamis (Supplier/Customer)
        function updateDynamicFields(type) {
            const container = $('#dynamic-fields');
            container.empty();

            if (type === 'incoming') {
                container.append(`
                    <div class="mb-6">
                        <x-input-label for="supplier_id" value="Supplier" class="text-electric-cyan" />
                        <select id="supplier_id" name="supplier_id" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm" required>
                            <option value="">Select Supplier</option>
                            ${suppliers.map(s => `<option value="${s.id}" ${'{{ old("supplier_id") }}' == s.id ? 'selected' : ''}>${s.name}</option>`).join('')}
                        </select>
                        @error('supplier_id') <span class="text-neon-red text-sm mt-2 block">{{ $message }}</span> @enderror
                    </div>
                `);
            } else if (type === 'outgoing') {
                container.append(`
                    <div class="mb-6">
                        <x-input-label for="related_party_name" value="Customer Name / Destination" class="text-electric-cyan" />
                        <x-text-input id="related_party_name" name="related_party_name" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" value="{{ old('related_party_name') }}" required />
                        @error('related_party_name') <span class="text-neon-red text-sm mt-2 block">{{ $message }}</span> @enderror
                    </div>
                `);
            }
        }

        // Fungsi untuk menambahkan baris item produk
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
                                <option value="">Select Product (SKU, Stock: QTY)</option>
                                ${products.map(p => `<option value="${p.id}" data-stock="${p.current_stock}">${p.sku} - ${p.name} (Stock: ${p.current_stock})</option>`).join('')}
                            </select>
                            <p class="text-xs text-gray-500 mt-1 max-stock-info" id="max-stock-info-${itemIndex}"></p>
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
            checkSubmitButton();
        }

        // Fungsi untuk mengaktifkan tombol submit
        function checkSubmitButton() {
            const itemCount = $('.item-row').length;
            $('#submit-btn').prop('disabled', itemCount === 0 || !$('#type').val());
            $('#add-item-btn').prop('disabled', !$('#type').val());
        }

        $(document).ready(function() {
            // Event listener untuk perubahan tipe transaksi
            $('#type').on('change', function() {
                const type = $(this).val();
                updateDynamicFields(type);
                checkSubmitButton();
                
                // Reset item list jika tipe berubah
                $('#item-list-container').empty();
                itemIndex = 0;
            });

            // Event listener untuk tombol Add Item
            $('#add-item-btn').on('click', addItem);

            // Event listener untuk tombol Remove Item (menggunakan delegation)
            $(document).on('click', '.remove-item-btn', function() {
                $(this).closest('.item-row').remove();
                checkSubmitButton();
            });
            
            // Logika untuk menampilkan stok maksimum jika Outgoing dipilih
            $(document).on('change', '.product-select', function() {
                const type = $('#type').val();
                const selectedOption = $(this).find('option:selected');
                const stock = selectedOption.data('stock');
                const index = $(this).data('index');
                
                const infoElement = $(`#max-stock-info-${index}`);
                
                if (type === 'outgoing') {
                    infoElement.text(`Max Outgoing Stock: ${stock}`).removeClass('text-gray-500').addClass('text-neon-red');
                    
                    // Set max attribute pada input quantity
                    $(`#quantity_${index}`).attr('max', stock);
                } else {
                    infoElement.text('');
                    $(`#quantity_${index}`).removeAttr('max');
                }
            });
            
            // Panggil pada saat load pertama (jika ada old input)
            updateDynamicFields($('#type').val());
            checkSubmitButton();
        });
    </script>
</x-app-layout>