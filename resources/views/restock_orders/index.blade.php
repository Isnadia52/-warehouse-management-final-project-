<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            {{ __('PURCHASE ORDER MANAGEMENT') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-6">
            
            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="bg-neon-green/20 border border-neon-green text-white p-4 rounded mb-4" data-aos="fade-down">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6" data-aos="fade-down">
                <h3 class="text-xl font-bold text-gray-200">
                    @if (auth()->user()->role === 'supplier')
                        Incoming Restock Orders
                    @else
                        All Purchase Orders
                    @endif
                </h3>
                
                {{-- Tombol Create hanya terlihat untuk Admin/Manager --}}
                @if (in_array(auth()->user()->role, ['admin', 'manager']))
                    <a href="{{ route(auth()->user()->role . '.restock_orders.create') }}" class="bg-neon-green hover:bg-green-600 text-dark-charcoal font-bold py-2 px-4 rounded transition duration-300">
                        <i class="fas fa-plus mr-2"></i> Create New PO
                    </a>
                @endif
            </div>

            {{-- PO Table (Quantum Style) --}}
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-gray-900 text-electric-cyan">
                        <tr>
                            <th scope="col" class="py-3 px-6">PO Number</th>
                            <th scope="col" class="py-3 px-6">Supplier</th>
                            <th scope="col" class="py-3 px-6">Order Date</th>
                            <th scope="col" class="py-3 px-6">Delivery Target</th>
                            <th scope="col" class="py-3 px-6">Status</th>
                            <th scope="col" class="py-3 px-6 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700 transition duration-150" data-aos="fade-up" data-aos-delay="50">
                                <th scope="row" class="py-4 px-6 font-medium whitespace-nowrap text-white">
                                    {{ $order->po_number }}
                                </th>
                                <td class="py-4 px-6">{{ $order->supplier->name }}</td>
                                <td class="py-4 px-6">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                                <td class="py-4 px-6">{{ $order->expected_delivery_date ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('d M Y') : 'N/A' }}</td>
                                <td class="py-4 px-6">
                                    @php
                                        $statusClass = match ($order->status) {
                                            'Pending' => 'bg-yellow-900/50 text-yellow-300 border-yellow-300',
                                            'Confirmed by Supplier' => 'bg-blue-900/50 text-blue-300 border-blue-300',
                                            'In Transit' => 'bg-indigo-900/50 text-indigo-300 border-indigo-300',
                                            'Received' => 'bg-neon-green/20 text-neon-green border-neon-green',
                                            default => 'bg-gray-500/50 text-gray-300 border-gray-300',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $statusClass }}">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center space-x-2 flex justify-center">
                                    <a href="#" class="font-medium text-electric-cyan hover:underline">View Detail</a>
                                    
                                    {{-- Tombol Aksi Supplier --}}
                                    @if ($order->status === 'Pending' && auth()->user()->role === 'supplier')
                                        <form method="POST" action="{{ route('supplier.restock_orders.update', $order) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="confirm">
                                            <button type="submit" class="font-medium text-neon-green hover:underline" onclick="return confirm('Confirm receipt of this Purchase Order?')">Confirm</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 px-6 text-center text-gray-500">No Restock Orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>