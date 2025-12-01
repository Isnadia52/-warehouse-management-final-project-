<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <div id="manager-header-target" 
                 class="typing-target" 
                 data-text="{{ __('MANAGER OPERATIONS HUB') }}" 
                 style="min-height: 25px;">
            </div>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Statistik Card Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Card 1: Total Produk --}}
            <div class="quantum-card p-6 rounded-lg text-center" data-aos="fade-right">
                <h3 class="text-3xl font-bold text-electric-cyan">{{ number_format($data['total_products']) }}</h3>
                <p class="text-gray-400 mt-2">Total Products Tracked</p>
            </div>
            
            {{-- Card 2: Low Stock Alert --}}
            <div class="quantum-card p-6 rounded-lg text-center border-neon-red" data-aos="fade-up">
                <h3 class="text-3xl font-bold text-neon-red">{{ number_format($data['low_stock_count']) }}</h3>
                <p class="text-gray-400 mt-2">Low Stock Alerts</p>
            </div>

            {{-- Card 3: Pending Transactions --}}
            <div class="quantum-card p-6 rounded-lg text-center" data-aos="fade-left">
                <h3 class="text-3xl font-bold text-yellow-300">{{ number_format($data['pending_approval']) }}</h3>
                <p class="text-gray-400 mt-2">Transactions Awaiting Approval</p>
            </div>
            
            {{-- Card 4: Pending Restock Order --}}
            <div class="quantum-card p-6 rounded-lg text-center col-span-1 md:col-span-3" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-3xl font-bold text-neon-green">{{ number_format($data['total_restock_pending']) }}</h3>
                <p class="text-gray-400 mt-2">Pending Restock Orders (Action Needed)</p>
            </div>
        </div>

        {{-- Section: Low Stock Products --}}
        <div class="quantum-card p-6 mt-8 shadow-xl sm:rounded-lg" data-aos="fade-up" data-aos-delay="200">
             <h4 class="text-xl font-semibold text-neon-red mb-4 border-b border-gray-700 pb-2">Critical Low Stock Products (Top 5)</h4>
             
             @include('products.partials.low_stock_table', ['products' => $data['low_stock_products']])
        </div>
    </div>
</x-app-layout>