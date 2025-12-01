<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <div id="transactions-header-target" 
                 class="typing-target" 
                 data-text="{{ __('TRANSACTION LOG') }}" 
                 style="min-height: 25px;">
            </div>
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
                    @if (auth()->user()->role === 'manager')
                        Pending Transactions for Approval
                    @else
                        Full Transaction History
                    @endif
                </h3>
                
                {{-- Tombol Create hanya terlihat untuk Staff --}}
                @can('create', App\Models\Transaction::class)
                    <a href="{{ route('staff.transactions.create') }}" class="bg-neon-green hover:bg-green-600 text-dark-charcoal font-bold py-2 px-4 rounded transition duration-300">
                        <i class="fas fa-plus mr-2"></i> Record New Transaction
                    </a>
                @endcan
            </div>

            {{-- Transaction Table (Quantum Style) --}}
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-gray-900 text-electric-cyan">
                        <tr>
                            <th scope="col" class="py-3 px-6">Transaction ID</th>
                            <th scope="col" class="py-3 px-6">Date</th>
                            <th scope="col" class="py-3 px-6">Type</th>
                            <th scope="col" class="py-3 px-6">Staff</th>
                            <th scope="col" class="py-3 px-6">Status</th>
                            <th scope="col" class="py-3 px-6 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="bg-gray-800 border-b border-gray-700 hover:bg-gray-700 transition duration-150" data-aos="fade-up" data-aos-delay="50">
                                <th scope="row" class="py-4 px-6 font-medium whitespace-nowrap text-white">
                                    {{ $transaction->transaction_number }}
                                </th>
                                <td class="py-4 px-6">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $transaction->type === 'incoming' ? 'text-neon-green bg-green-900/50 border border-neon-green' : 'text-neon-red bg-red-900/50 border border-neon-red' }}">
                                        {{ strtoupper($transaction->type) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">{{ $transaction->staff->name }}</td>
                                <td class="py-4 px-6">
                                    @php
                                        $statusClass = match ($transaction->status) {
                                            'Pending' => 'bg-yellow-900/50 text-yellow-300 border-yellow-300',
                                            'Approved' => 'bg-neon-green/20 text-neon-green border-neon-green',
                                            'Rejected' => 'bg-neon-red/20 text-neon-red border-neon-red',
                                            default => 'bg-gray-500/50 text-gray-300 border-gray-300',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $statusClass }}">
                                        {{ strtoupper($transaction->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center space-x-2 flex justify-center">
                                    <a href="{{ route(auth()->user()->role . '.transactions.show', $transaction) }}" class="font-medium text-electric-cyan hover:underline">View Detail</a>
                                    
                                    {{-- Tombol Approval hanya untuk Manager/Admin dan status Pending --}}
                                    @if ($transaction->status === 'Pending' && in_array(auth()->user()->role, ['manager', 'admin']))
                                        <form method="POST" action="{{ route(auth()->user()->role . '.transactions.update', $transaction) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="approve">
                                            {{-- Ganti dengan Custom Modal Warning nanti --}}
                                            <button type="submit" class="font-medium text-neon-green hover:underline" onclick="return confirm('Secure Approval: Do you approve this transaction?')">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route(auth()->user()->role . '.transactions.update', $transaction) }}" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="action" value="reject">
                                            {{-- Ganti dengan Custom Modal Warning nanti --}}
                                            <button type="submit" class="font-medium text-neon-red hover:underline" onclick="return confirm('Secure Approval: Do you reject this transaction?')">Reject</button>
                                        </form>
                                    @endif
                                    
                                    {{-- Staff hanya bisa edit jika Pending --}}
                                    @if ($transaction->status === 'Pending' && $transaction->staff_id === auth()->id())
                                        {{-- Kita abaikan edit, fokus pada logic approval & delete --}}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 px-6 text-center text-gray-500">No transactions recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>