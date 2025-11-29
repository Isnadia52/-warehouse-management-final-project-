<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <span class="inline-block overflow-hidden whitespace-nowrap border-r-4 border-electric-cyan animate-text-type">
                {{ __('ADMIN DASHBOARD') }}
            </span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg" data-aos="fade-up">
            <div class="p-6">
                <p class="text-xl text-neon-green mb-4 border-l-4 border-neon-green pl-3">
                    <i class="fa-solid fa-satellite-dish mr-2"></i> Real-time Data Stream Activated.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="quantum-card p-6 rounded-lg text-center" data-aos="fade-right">
                        <h3 class="text-3xl font-bold text-electric-cyan">15</h3>
                        <p class="text-gray-400 mt-2">Pending Transactions</p>
                    </div>

                    <div class="quantum-card p-6 rounded-lg text-center border-neon-red" data-aos="fade-up">
                        <h3 class="text-3xl font-bold text-neon-red">12</h3>
                        <p class="text-gray-400 mt-2">Low Stock Alerts</p>
                    </div>

                    <div class="quantum-card p-6 rounded-lg text-center" data-aos="fade-left">
                        <h3 class="text-3xl font-bold text-electric-cyan">6,281</h3>
                        <p class="text-gray-400 mt-2">Total Items Tracked</p>
                    </div>
                </div>

                <div class="mt-8 p-6 bg-gray-900 border border-electric-cyan rounded-lg" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-lg font-semibold text-electric-cyan mb-3">Welcome, {{ Auth::user()->name }}!</h4>
                    <p class="text-gray-300">Anda memiliki akses penuh ke Quantum Stockroom Management System. Semua modul aktif dan siap diakses.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>