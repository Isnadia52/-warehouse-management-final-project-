<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            {{ __('CATEGORY CONTROL CENTER') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            @if (session('success'))
                <div class="bg-neon-green/20 border border-neon-green text-white p-4 rounded mb-4" data-aos="fade-down">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-neon-red/20 border border-neon-red text-white p-4 rounded mb-4" data-aos="fade-down">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6" data-aos="fade-down">
                <h3 class="text-xl font-bold text-gray-200">Product Categories</h3>
                
                <a href="{{ route(auth()->user()->role . '.categories.create') }}" class="bg-neon-green hover:bg-green-600 text-dark-charcoal font-bold py-2 px-4 rounded transition duration-300">
                    <i class="fas fa-plus mr-2"></i> Add New Category
                </a>
            </div>

            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-gray-900 text-electric-cyan">
                        <tr>
                            <th scope="col" class="py-3 px-6">Name</th>
                            <th scope="col" class="py-3 px-6">Description</th>
                            <th scope="col" class="py-3 px-6 text-center">Total Products</th>
                            <th scope="col" class="py-3 px-6 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700 transition duration-150" data-aos="fade-up" data-aos-delay="50">
                                <th scope="row" class="py-4 px-6 font-medium whitespace-nowrap text-white">
                                    {{ $category->name }}
                                </th>
                                <td class="py-4 px-6">{{ Str::limit($category->description, 50) }}</td>
                                <td class="py-4 px-6 text-center text-electric-cyan">{{ $category->products_count }}</td>
                                <td class="py-4 px-6 text-center">
                                    <form method="POST" action="{{ route(auth()->user()->role . '.categories.destroy', $category) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-neon-red hover:underline" onclick="return confirm('WARNING: Are you sure you want to delete this category?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 px-6 text-center text-gray-500">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>