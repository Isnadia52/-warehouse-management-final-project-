<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <div id="admin-header-target" 
                 class="typing-target" 
                 data-text="{{ __('STAFF WORKSTATION') }}" 
                 style="min-height: 25px;">
            </div>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Statistik Card Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Card 1: Total Products --}}
            <div class="quantum-card p-6 rounded-lg text-center" data-aos="fade-right">
                <h3 class="text-3xl font-bold text-electric-cyan">{{ number_format($data['total_products']) }}</h3>
                <p class="text-gray-400 mt-2">Total Products Tracked</p>
            </div>
            
            {{-- Card 2: Low Stock Alert --}}
            <div class="quantum-card p-6 rounded-lg text-center border-neon-red" data-aos="fade-up">
                <h3 class="text-3xl font-bold text-neon-red">{{ number_format($data['low_stock_count']) }}</h3>
                <p class="text-gray-400 mt-2">Products in Critical Stock</p>
            </div>

            {{-- Card 3: Transactions Today --}}
            <div class="quantum-card p-6 rounded-lg text-center" data-aos="fade-left">
                <h3 class="text-3xl font-bold text-neon-green">{{ number_format($data['transactions_today']) }}</h3>
                <p class="text-gray-400 mt-2">Transactions Filed Today</p>
            </div>
        </div>

        {{-- Section: Recent Activity Table --}}
        <div class="quantum-card p-6 mt-8 shadow-xl sm:rounded-lg" data-aos="fade-up" data-aos-delay="100">
             <h4 class="text-xl font-semibold text-electric-cyan mb-4 border-b border-gray-700 pb-2">Your Recent Activity (Last 5 Transactions)</h4>
             
             @include('transactions.partials.recent_table', ['transactions' => $data['recent_transactions']])
        </div>
    </div>
</x-app-layout>