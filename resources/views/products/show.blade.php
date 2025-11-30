<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <span class="inline-block overflow-hidden whitespace-nowrap border-r-4 border-electric-cyan animate-type-and-blink">
                {{ __('PRODUCT DETAIL: ') . $product->name }}
            </span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8" data-aos="fade-up">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- IMAGE SECTION --}}
                <div class="md:col-span-1">
                    <h3 class="text-2xl font-bold text-neon-green mb-4 border-b border-gray-700 pb-2">Image Preview</h3>
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded-lg border border-gray-700 shadow-lg" />
                    @else
                        <div class="w-full h-64 flex items-center justify-center bg-gray-900 border border-gray-700 rounded-lg">
                            <p class="text-gray-500 italic">No Image Available</p>
                        </div>
                    @endif
                </div>

                {{-- DETAIL SECTION --}}
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-bold text-neon-green mb-4 border-b border-gray-700 pb-2">Product Data</h3>

                    <div class="grid grid-cols-2 gap-4 text-gray-300 mb-6">
                        
                        {{-- Row 1 --}}
                        <div>
                            <p class="text-xs uppercase text-electric-cyan">SKU</p>
                            <p class="text-xl font-bold text-white">{{ $product->sku }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-electric-cyan">Category</p>
                            <p class="text-xl font-bold text-white">{{ $product->category->name ?? 'Uncategorized' }}</p>
                        </div>

                        {{-- Row 2 --}}
                        <div class="mt-4">
                            <p class="text-xs uppercase text-electric-cyan">Current Stock</p>
                            <p class="text-2xl font-bold text-neon-green">{{ number_format($product->current_stock) }} {{ $product->unit }}</p>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs uppercase text-electric-cyan">Minimum Stock</p>
                            <p class="text-xl font-bold text-yellow-300">{{ number_format($product->min_stock) }} {{ $product->unit }}</p>
                            @if ($product->current_stock <= $product->min_stock)
                                <span class="text-neon-red text-sm font-semibold"> (LOW ALERT!)</span>
                            @endif
                        </div>
                        
                        {{-- Row 3 --}}
                        <div class="mt-4">
                            <p class="text-xs uppercase text-electric-cyan">Rack Location</p>
                            <p class="text-xl font-bold text-white">{{ $product->rack_location ?: 'N/A' }}</p>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs uppercase text-electric-cyan">Date Created</p>
                            {{-- Karena kita sudah casting di Model, kita bisa langsung format --}}
                            <p class="text-xl font-bold text-white">{{ $product->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-xs uppercase text-electric-cyan">Description</p>
                        <p class="text-white mt-1">{{ $product->description ?: 'No description provided.' }}</p>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <a href="{{ route(auth()->user()->role . '.products.index') }}" class="bg-electric-cyan hover:bg-cyan-600 text-dark-charcoal font-bold py-2 px-4 rounded transition duration-300">
                            Back to Product List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>