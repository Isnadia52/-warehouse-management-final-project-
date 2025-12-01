<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <div id="categories-header-target" 
                 class="typing-target" 
                 data-text="{{ __('CATEGORY CONTROL CENTER') }}" 
                 style="min-height: 25px;">
            </div>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            {{-- Notifikasi --}}
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

            {{-- GRID CARD VIEW --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($categories as $category)
                    {{-- Setiap kartu dibungkus oleh tautan <a> untuk menuju halaman detail --}}
                    <a href="{{ route(auth()->user()->role . '.categories.show', $category) }}" 
                        class="quantum-card p-4 rounded-lg flex flex-col justify-between hover:scale-105 transition duration-300 no-underline text-white relative group" 
                        data-aos="fade-up" data-aos-delay="50">
                        
                        {{-- Konten Kartu Utama --}}
                        <div>
                            <div class="flex items-start mb-4">
                                {{-- ICON / GAMBAR KATEGORI --}}
                                <div class="mr-4 mt-1">
                                    @if ($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="Category Icon" class="w-12 h-12 object-cover rounded-md border border-electric-cyan" />
                                    @else
                                        <div class="w-12 h-12 flex items-center justify-center bg-gray-900 border border-gray-700 rounded-md">
                                            <i class="fas fa-box text-electric-cyan text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- NAMA DAN DESKRIPSI --}}
                                <div>
                                    <h4 class="text-lg font-bold text-white group-hover:text-electric-cyan transition">{{ $category->name }}</h4>
                                    <p class="text-xs text-gray-400 mt-1">{{ Str::limit($category->description, 30) }}</p>
                                </div>
                            </div>

                            {{-- STATISTIK PRODUK --}}
                            <div class="border-t border-gray-700 pt-3">
                                <p class="text-sm uppercase text-electric-cyan">Total Products</p>
                                <p class="text-3xl font-extrabold text-neon-green mt-1">{{ $category->products_count }}</p>
                            </div>
                        </div>

                        {{-- TOMBOL DELETE (Diposisikan ABSOLUTE agar tidak termasuk dalam tautan utama) --}}
                        <div class="absolute top-4 right-4 z-100"> 
                            <form method="POST" action="{{ route(auth()->user()->role . '.categories.destroy', $category) }}" 
                                class="inline" 
                                onclick="event.stopPropagation();"> {{-- Mencegah klik tombol delete membuka halaman detail --}}
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-neon-red hover:text-red-400 text-lg p-1 rounded-full bg-gray-900/70" 
                                        onclick="return confirm('WARNING: Are you sure you want to delete this category?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </a>
                @empty
                    <div class="col-span-4 p-6 text-center text-gray-500">
                        No categories found. Click 'Add New Category' to start encoding data.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>