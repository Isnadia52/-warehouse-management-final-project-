<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <div id="supplier-header-target" 
                 class="typing-target" 
                 data-text="{{ __('SUPPLIER PORTAL') }}" 
                 style="min-height: 25px;">
            </div>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Statistik Card Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Card 1: Pending PO --}}
            <div class="quantum-card p-6 rounded-lg text-center border-neon-red" data-aos="fade-right">
                <h3 class="text-3xl font-bold text-neon-red">{{ number_format($data['po_pending']) }}</h3>
                <p class="text-gray-400 mt-2">Purchase Orders Awaiting Confirmation</p>
            </div>
            
            {{-- Card 2: In Transit PO --}}
            <div class="quantum-card p-6 rounded-lg text-center border-electric-cyan" data-aos="fade-left">
                <h3 class="text-3xl font-bold text-electric-cyan">{{ number_format($data['po_in_transit']) }}</h3>
                <p class="text-gray-400 mt-2">Orders Currently In Transit</p>
            </div>
        </div>

        {{-- Section: Recent PO Activity --}}
        <div class="quantum-card p-6 mt-8 shadow-xl sm:rounded-lg" data-aos="fade-up" data-aos-delay="100">
             <h4 class="text-xl font-semibold text-neon-green mb-4 border-b border-gray-700 pb-2">Recent Restock Orders (Last 5)</h4>
             
             @include('restock_orders.partials.recent_table', ['orders' => $data['recent_po']])
        </div>
    </div>
</x-app-layout>