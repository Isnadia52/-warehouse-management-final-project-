<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <span class="inline-block overflow-hidden whitespace-nowrap border-r-4 border-electric-cyan animate-type-and-blink">
                {{ __('CATEGORY DETAIL: ') . $category->name }}
            </span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8 relative" data-aos="fade-up">

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="bg-neon-green/20 border border-neon-green text-white p-4 rounded mb-4" data-aos="fade-down">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                {{-- Notifikasi ERROR/RESTRICT ditampilkan di sini --}}
                <div class="bg-neon-red/20 border border-neon-red text-white p-4 rounded mb-4" data-aos="fade-down">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                {{-- ICON SECTION --}}
                <div class="md:col-span-1 text-center">
                    <h3 class="text-xl font-bold text-neon-green mb-4 border-b border-gray-700 pb-2">Category Icon</h3>
                    @if ($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="Category Icon" class="w-32 h-32 object-cover rounded-lg border border-electric-cyan mx-auto" />
                    @else
                        <div class="w-32 h-32 flex items-center justify-center bg-gray-900 border border-gray-700 rounded-lg mx-auto">
                            <i class="fas fa-box text-electric-cyan text-5xl"></i>
                        </div>
                    @endif
                </div>

                {{-- DETAIL SECTION --}}
                <div class="md:col-span-3">
                    <h3 class="text-xl font-bold text-neon-green mb-4 border-b border-gray-700 pb-2">Category Information</h3>

                    <div class="grid grid-cols-2 gap-4 text-gray-300 mb-6">
                        <div>
                            <p class="text-xs uppercase text-electric-cyan">Total Products</p>
                            {{-- Menggunakan $category->products_count yang dijamin sudah di-refresh di @php atas --}}
                            <p class="text-3xl font-bold text-neon-green">{{ number_format($category->products_count) }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase text-electric-cyan">Date Encoded</p>
                            <p class="text-xl font-bold text-white">{{ $category->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-xs uppercase text-electric-cyan">Category Description (Full)</p>
                        <p class="text-white mt-1 p-4 border border-gray-700 rounded-lg bg-gray-900/50 min-h-32">{{ $category->description ?: 'No description provided.' }}</p>
                    </div>

                    <div class="mt-8 flex justify-end gap-4"> 
                        
                        {{-- Tombol Delete --}}
                        <form method="POST" action="{{ route(auth()->user()->role . '.categories.destroy', $category) }}"
                            onsubmit="return confirm('WARNING: Deleting this category will remove all associated products links. Continue?');">
                            @csrf
                            @method('DELETE')
                            <button class="flex items-center gap-2 bg-red-600/40 border border-red-500 text-red-300
                                           hover:bg-red-600 hover:text-white hover:border-red-400
                                           font-bold py-2 px-4 rounded-full transition duration-300 shadow-lg">
                                <i class="fas fa-trash-alt"></i>
                                Delete Category
                            </button>
                        </form>
                        
                        {{-- Tombol Back --}}
                        <a href="{{ route(auth()->user()->role . '.categories.index') }}"
                           class="bg-electric-cyan hover:bg-cyan-600 text-dark-charcoal font-bold py-2 px-4 rounded transition duration-300 shadow-lg">
                            Back to Category List
                        </a>
                    </div>
                </div>
            </div>

            <hr class="my-8 border-gray-700">

            {{-- PRODUCTS LIST UNDER THIS CATEGORY --}}
            <h3 class="text-xl font-bold text-neon-green mb-4 border-b border-gray-700 pb-2 mt-8">Products in This Category</h3>

            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-gray-900 text-electric-cyan">
                        <tr>
                            <th scope="col" class="py-3 px-6">SKU</th>
                            <th scope="col" class="py-3 px-6">Product Name</th>
                            <th scope="col" class="py-3 px-6 text-center">Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700 transition duration-150">
                                <th scope="row" class="py-4 px-6 font-medium whitespace-nowrap text-white">
                                    <a href="{{ route(auth()->user()->role . '.products.show', $product) }}" class="text-electric-cyan hover:underline">{{ $product->sku }}</a>
                                </th>
                                <td class="py-4 px-6">{{ $product->name }}</td>
                                <td class="py-4 px-6 text-center">{{ number_format($product->current_stock) }} {{ $product->unit }}</td>
                            </tr>
                        @empty
                            <tr class="bg-gray-800">
                                <td colspan="3" class="py-4 px-6 text-center text-gray-500">
                                    No products found in this category.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>