<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            {{ __('PRODUCT DETAIL: ') }} {{ $product->name }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8" data-aos="fade-up">
            
            <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3">Quantum Data Stream</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-300">
                <div class="p-4 border border-gray-700 rounded-lg">
                    <p class="text-electric-cyan font-bold">SKU:</p>
                    <p class="text-lg">{{ $product->sku }}</p>
                </div>
                <div class="p-4 border border-gray-700 rounded-lg">
                    <p class="text-electric-cyan font-bold">Category:</p>
                    <p class="text-lg">{{ $product->category->name }}</p>
                </div>
                <div class="p-4 border border-gray-700 rounded-lg">
                    <p class="text-electric-cyan font-bold">Current Stock:</p>
                    <p class="text-lg {{ $product->is_low_stock ? 'text-neon-red' : 'text-neon-green' }}">{{ $product->current_stock }} {{ $product->unit }}</p>
                </div>
                <div class="p-4 border border-gray-700 rounded-lg">
                    <p class="text-electric-cyan font-bold">Rack Location:</p>
                    <p class="text-lg">{{ $product->rack_location }}</p>
                </div>
            </div>

            <div class="mt-6 p-4 border border-gray-700 rounded-lg text-gray-300">
                <p class="text-electric-cyan font-bold">Description:</p>
                <p>{{ $product->description }}</p>
            </div>

            <div class="flex items-center justify-end mt-8">
                <a href="{{ route(auth()->user()->role . '.products.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                    {{ __('Back to Inventory') }}
                </a>
            </div>

        </div>
    </div>
</x-app-layout>