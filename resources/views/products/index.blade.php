<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <span class="inline-block overflow-hidden whitespace-nowrap border-r-4 border-electric-cyan animate-text-type">
                {{ __('PRODUCT MANAGEMENT INTERFACE') }}
            </span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            <div class="flex justify-between items-center mb-6" data-aos="fade-down">
                <h3 class="text-xl font-bold text-gray-200">Inventory Status</h3>
                
                {{-- Tombol Create hanya terlihat jika Policy mengizinkan --}}
                @can('create', App\Models\Product::class)
                    <a href="{{ route(auth()->user()->role . '.products.create') }}" class="bg-neon-green hover:bg-green-600 text-dark-charcoal font-bold py-2 px-4 rounded transition duration-300">
                        <i class="fas fa-plus mr-2"></i> Add New Product
                    </a>
                @endcan
            </div>

            {{-- Product Table (Quantum Style) --}}
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-gray-900 text-electric-cyan">
                        <tr>
                            <th scope="col" class="py-3 px-6">SKU</th>
                            <th scope="col" class="py-3 px-6">Product Name</th>
                            <th scope="col" class="py-3 px-6">Category</th>
                            <th scope="col" class="py-3 px-6 text-center">Current Stock</th>
                            <th scope="col" class="py-3 px-6 text-center">Min Stock</th>
                            <th scope="col" class="py-3 px-6 text-center">Location</th>
                            <th scope="col" class="py-3 px-6 text-center">Status</th>
                            <th scope="col" class="py-3 px-6 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700 transition duration-150" data-aos="fade-up" data-aos-delay="50">
                                <th scope="row" class="py-4 px-6 font-medium whitespace-nowrap text-white">
                                    {{ $product->sku }}
                                </th>
                                <td class="py-4 px-6">{{ $product->name }}</td>
                                <td class="py-4 px-6">{{ $product->category->name }}</td>
                                <td class="py-4 px-6 text-center">{{ $product->current_stock }} {{ $product->unit }}</td>
                                <td class="py-4 px-6 text-center">{{ $product->min_stock }} {{ $product->unit }}</td>
                                <td class="py-4 px-6 text-center text-electric-cyan">{{ $product->rack_location }}</td>
                                <td class="py-4 px-6 text-center">
                                    @if ($product->is_low_stock)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full text-white bg-neon-red shadow-lg shadow-neon-red/50">LOW ALERT</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full text-gray-900 bg-neon-green shadow-lg shadow-neon-green/50">NORMAL</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 flex justify-center space-x-2">
    
                                    {{-- Aksi EDIT dan DELETE (Hanya untuk Admin/Manager) --}}
                                    @if (in_array(auth()->user()->role, ['admin', 'manager']))
                                        @can('update', $product)
                                            <a href="{{ route(auth()->user()->role . '.products.edit', $product) }}" class="font-medium text-electric-cyan hover:underline">Edit</a>
                                        @endcan
                                        @can('delete', $product)
                                            <form method="POST" action="{{ route(auth()->user()->role . '.products.destroy', $product) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-neon-red hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        @endcan
                                    @endif
                                    
                                    {{-- Tombol VIEW (Untuk semua role, tapi Staff Wajib) --}}
                                    @can('view', $product)
                                        @if (in_array(auth()->user()->role, ['admin', 'manager']))
                                            <a href="{{ route(auth()->user()->role . '.products.show', $product) }}" class="font-medium text-yellow-300 hover:underline">Detail</a>
                                        @elseif (auth()->user()->role === 'staff')
                                            <a href="{{ route(auth()->user()->role . '.products.show', $product) }}" class="font-medium text-gray-400 hover:underline">View</a>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-4 px-6 text-center text-gray-500">No products found in the database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>