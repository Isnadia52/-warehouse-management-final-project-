<div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-900 text-electric-cyan">
            <tr>
                <th scope="col" class="py-3 px-6">Number</th>
                <th scope="col" class="py-3 px-6">Type</th>
                <th scope="col" class="py-3 px-6">Date</th>
                <th scope="col" class="py-3 px-6">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700 transition duration-150">
                    <th scope="row" class="py-4 px-6 font-medium whitespace-nowrap text-white">
                        <a href="{{ route(auth()->user()->role . '.transactions.show', $transaction) }}" class="text-electric-cyan hover:underline">{{ $transaction->transaction_number }}</a>
                    </th>
                    <td class="py-4 px-6 {{ $transaction->type === 'Incoming' ? 'text-neon-green' : 'text-neon-red' }}">{{ $transaction->type }}</td>
                    <td class="py-4 px-6">{{ $transaction->transaction_date->format('d M Y') }}</td>
                    <td class="py-4 px-6">
                        <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $transaction->status === 'Approved' ? 'bg-neon-green/20 text-neon-green' : ($transaction->status === 'Pending' ? 'bg-yellow-900/50 text-yellow-300' : 'bg-neon-red/20 text-neon-red') }}">
                            {{ strtoupper($transaction->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-4 px-6 text-center text-gray-500">No recent transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>