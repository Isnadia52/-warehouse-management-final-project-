<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <div id="product-header-target" 
                 class="typing-target" 
                 data-text="{{ __('EDIT QUANTUM PRODUCT: ') . $product->name }}" 
                 style="min-height: 25px;">
            </div>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8" data-aos="fade-up">

            {{-- ERROR HANDLING GLOBAL --}}
            @if ($errors->any())
                <div class="bg-neon-red/20 border border-neon-red text-white p-4 rounded mb-4">
                    <h4 class="font-bold mb-2">Product Update Failed: Review Input Data</h4>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route(auth()->user()->role . '.products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3">Product Core Data</h3>

                {{-- Baris 1: Name dan SKU --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <x-input-label for="name" :value="__('Product Name')" class="text-electric-cyan" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('name', $product->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="sku" :value="__('SKU (Stock Keeping Unit) - Unique')" class="text-electric-cyan" />
                        {{-- SKU DIBUAT READONLY agar tidak diubah --}}
                        <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full bg-gray-700/50 text-white" :value="old('sku', $product->sku)" readonly />
                        <x-input-error class="mt-2" :messages="$errors->get('sku')" />
                    </div>
                </div>

                {{-- Baris 2: Category dan Unit --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <x-input-label for="category_id" :value="__('Category')" class="text-electric-cyan" />
                        <select id="category_id" name="category_id" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                    </div>
                    <div>
                        <x-input-label for="unit" :value="__('Unit (pcs, box, kg, etc.)')" class="text-electric-cyan" />
                        <x-text-input id="unit" name="unit" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('unit', $product->unit)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('unit')" />
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3 mt-8">Stock & Pricing</h3>

                {{-- Baris 3: Prices --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <x-input-label for="buy_price" :value="__('Buy Price (Rp)')" class="text-electric-cyan" />
                        <x-text-input id="buy_price" name="buy_price" type="number" step="0.01" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('buy_price', $product->buy_price)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('buy_price')" />
                    </div>
                    <div>
                        <x-input-label for="sell_price" :value="__('Sell Price (Rp)')" class="text-electric-cyan" />
                        <x-text-input id="sell_price" name="sell_price" type="number" step="0.01" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('sell_price', $product->sell_price)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('sell_price')" />
                    </div>
                </div>

                {{-- Baris 4: Stock Levels --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <x-input-label for="current_stock" :value="__('Current Stock Level')" class="text-electric-cyan" />
                        <x-text-input id="current_stock" name="current_stock" type="number" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('current_stock', $product->current_stock)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('current_stock')" />
                    </div>
                    <div>
                        <x-input-label for="min_stock" :value="__('Minimum Stock for Alert')" class="text-electric-cyan" />
                        <x-text-input id="min_stock" name="min_stock" type="number" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('min_stock', $product->min_stock)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('min_stock')" />
                    </div>
                    <div>
                        <x-input-label for="rack_location" :value="__('Rack Location (e.g., A-01-B)')" class="text-electric-cyan" />
                        <x-text-input id="rack_location" name="rack_location" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('rack_location', $product->rack_location)" />
                        <x-input-error class="mt-2" :messages="$errors->get('rack_location')" />
                    </div>
                </div>
                
                {{-- Deskripsi --}}
                <div class="mb-6">
                    <x-input-label for="description" :value="__('Description')" class="text-electric-cyan" />
                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm">{{ old('description', $product->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                {{-- Image (optional) --}}
                <div class="mb-6">
                    <x-input-label for="image" :value="__('Replace Product Image (Max 2MB)')" class="text-electric-cyan" />
                    
                    @if ($product->image)
                        <p class="text-sm text-gray-400 mt-2 mb-2">Current Image:</p>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Current Product Image" class="w-24 h-24 object-cover rounded-md border border-gray-700 mb-4">
                    @else
                        <p class="text-sm text-yellow-300 mt-2 mb-2">No image currently uploaded.</p>
                    @endif

                    <input id="image" name="image" type="file" class="mt-1 block w-full text-white bg-gray-900 border-gray-700 rounded-md shadow-sm" />
                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route(auth()->user()->role . '.products.index') }}" class="text-gray-400 hover:text-white mr-4 transition duration-300">
                        Cancel
                    </a>

                    <x-primary-button class="bg-neon-green hover:bg-green-600 text-dark-charcoal font-bold py-2 px-4">
                        {{ __('Update Quantum Data') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>