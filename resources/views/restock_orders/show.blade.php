<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <span class="inline-block overflow-hidden whitespace-nowrap border-r-4 border-electric-cyan animate-type-and-blink">
                {{ __('PURCHASE ORDER DETAIL: ') . $restock_order->po_number }}
            </span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8" data-aos="fade-up">

            {{-- ORDER INFORMATION --}}
            <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3">Order Information</h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-gray-300 mb-8">
                <div>
                    <p class="text-xs uppercase text-electric-cyan">PO Number</p>
                    <p class="text-xl font-bold text-white">{{ $restock_order->po_number }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-electric-cyan">Supplier</p>
                    <p class="text-xl font-bold text-white">{{ $restock_order->supplier->name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-electric-cyan">Order Date</p>
                    <p class="text-xl font-bold text-white">{{ \Carbon\Carbon::parse($restock_order->order_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-electric-cyan">Delivery Target</p>
                    <p class="text-xl font-bold text-white">{{ $restock_order->expected_delivery_date ? \Carbon\Carbon::parse($restock_order->expected_delivery_date)->format('d M Y') : 'N/A' }}</p>
                </div>
            </div>
            
            <div class="mb-6">
                <p class="text-xs uppercase text-electric-cyan">Current Status</p>
                @php
                    $statusClass = match ($restock_order->status) {
                        'Pending' => 'bg-yellow-900/50 text-yellow-300 border-yellow-300',
                        'Confirmed by Supplier' => 'bg-blue-900/50 text-blue-300 border-blue-300',
                        'In Transit' => 'bg-indigo-900/50 text-indigo-300 border-indigo-300',
                        'Received' => 'bg-neon-green/20 text-neon-green border-neon-green',
                        default => 'bg-gray-500/50 text-gray-300 border-gray-300',
                    };
                @endphp
                <span class="px-3 py-1 text-sm font-medium rounded-full border {{ $statusClass }} inline-block mt-1">
                    {{ strtoupper($restock_order->status) }}
                </span>
            </div>

            <div class="mb-6">
                <p class="text-xs uppercase text-electric-cyan">Order Notes</p>
                <p class="text-lg italic text-gray-400">{{ $restock_order->notes ?: 'N/A' }}</p>
            </div>

            {{-- ORDER ITEMS TABLE --}}
            <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3 mt-8">Items to Restock</h3>
            
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-gray-900 text-electric-cyan">
                        <tr>
                            <th scope="col" class="py-3 px-6">Product (SKU)</th>
                            <th scope="col" class="py-3 px-6">Quantity Ordered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($restock_order->items as $item)
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
                <a href="{{ route(auth()->user()->role . '.restock_orders.index') }}" class="bg-electric-cyan hover:bg-cyan-600 text-dark-charcoal font-bold py-2 px-4 rounded transition duration-300">
                    Back to PO List
                </a>
            </div>
        </div>
    </div>
</x-app-layout>