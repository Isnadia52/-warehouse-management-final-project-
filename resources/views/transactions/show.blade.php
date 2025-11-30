<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <span class="inline-block overflow-hidden whitespace-nowrap border-r-4 border-electric-cyan animate-type-and-blink">
                {{ __('TRANSACTION DETAIL: ') . $transaction->transaction_number }}
            </span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8" data-aos="fade-up">

            {{-- HEADER DATA --}}
            <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3">Header Data</h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-gray-300 mb-8">
                <div>
                    <p class="text-xs uppercase text-electric-cyan">Transaction Type</p>
                    <p class="text-xl font-bold {{ $transaction->type === 'Incoming' ? 'text-neon-green' : 'text-neon-red' }}">{{ $transaction->type }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-electric-cyan">Date</p>
                    <p class="text-xl font-bold">{{ $transaction->transaction_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-electric-cyan">Status</p>
                    @php
                        $statusClass = $transaction->status === 'Approved' ? 'text-neon-green' : ($transaction->status === 'Pending' ? 'text-yellow-300' : 'text-neon-red');
                    @endphp
                    <p class="text-xl font-bold {{ $statusClass }}">{{ $transaction->status }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-electric-cyan">Filed By</p>
                    <p class="text-xl font-bold">{{ $transaction->staff->name }}</p>
                </div>
            </div>
            
            <div class="mb-6">
                <p class="text-xs uppercase text-electric-cyan">Notes</p>
                <p class="text-lg italic text-gray-400">{{ $transaction->notes ?: 'N/A' }}</p>
            </div>

            {{-- MANAGER APPROVAL STATUS --}}
            @if ($transaction->manager)
                <div class="p-4 rounded-lg border {{ $transaction->status === 'Approved' ? 'border-neon-green bg-neon-green/10' : 'border-neon-red bg-neon-red/10' }}">
                    <p class="text-xs uppercase font-semibold text-electric-cyan">Manager Approval Details</p>
                    <p class="text-white mt-1">Approved by: {{ $transaction->manager->name }}</p>
                    <p class="text-sm text-gray-400">Approved on: {{ $transaction->approved_at->format('d M Y H:i') }}</p>
                </div>
            @endif

            {{-- TRANSACTION ITEMS TABLE --}}
            <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3 mt-8">Transaction Items</h3>
            
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-gray-900 text-electric-cyan">
                        <tr>
                            <th scope="col" class="py-3 px-6">Product (SKU)</th>
                            <th scope="col" class="py-3 px-6">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->items as $item)
                            <tr class="bg-gray-800 border-b border-gray-700">
                                <th scope="row" class="py-4 px-6 font-medium whitespace-nowrap text-white">
                                    {{ $item->product->name }} ({{ $item->product->sku }})
                                </th>
                                <td class="py-4 px-6 font-bold text-lg">
                                    {{ number_format($item->quantity) }} {{ $item->product->unit }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route(auth()->user()->role . '.transactions.index') }}" class="bg-electric-cyan hover:bg-cyan-600 text-dark-charcoal font-bold py-2 px-4 rounded transition duration-300">
                    Back to Logs
                </a>
            </div>
        </div>
    </div>
</x-app-layout>