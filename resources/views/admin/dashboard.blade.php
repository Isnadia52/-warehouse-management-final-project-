<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <span class="inline-block overflow-hidden whitespace-nowrap border-r-4 border-electric-cyan animate-type-and-blink">
                {{ __('ADMIN COMMAND CONSOLE') }}
            </span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Statistik Card Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
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
            <div class="quantum-card p-6 rounded-lg text-center" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-3xl font-bold text-yellow-300">{{ number_format($data['pending_transactions']) }}</h3>
                <p class="text-gray-400 mt-2">Pending Transactions</p>
            </div>

            {{-- Card 4: Total User --}}
            <div class="quantum-card p-6 rounded-lg text-center" data-aos="fade-left">
                <h3 class="text-3xl font-bold text-neon-green">{{ number_format($data['total_users']) }}</h3>
                <p class="text-gray-400 mt-2">Total Users in System</p>
            </div>
            
             {{-- Card 5: Pending Supplier Approval --}}
            <div class="quantum-card p-6 rounded-lg text-center col-span-1 md:col-span-4" data-aos="fade-up">
                <h3 class="text-3xl font-bold text-electric-cyan">{{ number_format($data['pending_supplier']) }}</h3>
                <p class="text-gray-400 mt-2">Pending Supplier Approvals</p>
            </div>
        </div>

        {{-- Section: Recent Activity Table --}}
        <div class="quantum-card p-6 mt-8 shadow-xl sm:rounded-lg" data-aos="fade-up" data-aos-delay="200">
             <h4 class="text-xl font-semibold text-electric-cyan mb-4 border-b border-gray-700 pb-2">Recent Transaction Activity (Last 5)</h4>
             
             @include('transactions.partials.recent_table', ['transactions' => $data['recent_transactions']])
        </div>
    </div>
</x-app-layout>