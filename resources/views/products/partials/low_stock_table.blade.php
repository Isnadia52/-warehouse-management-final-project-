<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-900 text-electric-cyan">
            <tr>
                <th scope="col" class="py-3 px-6">SKU</th>
                <th scope="col" class="py-3 px-6">Product Name</th>
                <th scope="col" class="py-3 px-6">Current Stock</th>
                <th scope="col" class="py-3 px-6">Min. Stock</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700 transition duration-150">
                    <td class="py-4 px-6 text-white">{{ $product->sku }}</td>
                    <td class="py-4 px-6 text-white">{{ $product->name }}</td>
                    <td class="py-4 px-6 text-neon-red font-bold">{{ number_format($product->current_stock) }} {{ $product->unit }}</td>
                    <td class="py-4 px-6">{{ number_format($product->min_stock) }} {{ $product->unit }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-4 px-6 text-center text-gray-500">All products are within safe stock limits.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>