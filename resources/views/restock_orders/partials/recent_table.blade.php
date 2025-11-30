<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-900 text-electric-cyan">
            <tr>
                <th scope="col" class="py-3 px-6">PO Number</th>
                <th scope="col" class="py-3 px-6">Order Date</th>
                <th scope="col" class="py-3 px-6">Status</th>
                <th scope="col" class="py-3 px-6 text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700 transition duration-150">
                    <th scope="row" class="py-4 px-6 font-medium whitespace-nowrap text-white">
                        <a href="{{ route(auth()->user()->role . '.restock_orders.show', $order) }}" class="text-electric-cyan hover:underline">{{ $order->po_number }}</a>
                    </th>
                    <td class="py-4 px-6">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
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
                     <td class="py-4 px-6 text-center">
                        @if ($order->status === 'Pending' && auth()->user()->role === 'supplier')
                           <a href="{{ route('supplier.restock_orders.index') }}" class="font-medium text-neon-green hover:underline">Confirm</a>
                        @else
                            <span class="text-gray-500">N/A</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-4 px-6 text-center text-gray-500">No recent restock orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>