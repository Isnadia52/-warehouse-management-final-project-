@php
    $dashboardRoute = match (auth()->user()->role) {
        'admin' => 'admin.dashboard',
        'manager' => 'manager.dashboard',
        'staff' => 'staff.dashboard',
        'supplier' => 'supplier.dashboard',
        default => 'dashboard',
    };
@endphp

<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route($dashboardRoute) }}">
                        <span class="text-2xl font-bold text-electric-cyan hover:text-white transition duration-300">
                            Quantum<span class="text-neon-green">Stock</span>
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)">
                        {{-- Tampilkan nama dashboard sesuai role --}}
                        {{ strtoupper(auth()->user()->role) }} DASHBOARD
                    </x-nav-link>

                    {{-- Link Product Management (Admin, Manager, Staff) --}}
                    @can('viewAny', App\Models\Product::class)
                        <x-nav-link :href="route(auth()->user()->role . '.products.index')" :active="request()->routeIs(auth()->user()->role . '.products.index')">
                            {{ __('Product Management') }}
                        </x-nav-link>
                    @endcan
                    
                    {{-- Link Category Management (Admin, Manager) --}}
                    @if (in_array(auth()->user()->role, ['admin', 'manager']))
                        <x-nav-link :href="route(auth()->user()->role . '.categories.index')" :active="request()->routeIs(auth()->user()->role . '.categories.index')">
                            {{ __('Category Control') }}
                        </x-nav-link>
                    @endif
                    
                    {{-- Link Transaction Logs (Admin, Manager, Staff) --}}
                    @can('viewAny', App\Models\Transaction::class)
                        <x-nav-link :href="route(auth()->user()->role . '.transactions.index')" :active="request()->routeIs(auth()->user()->role . '.transactions.index')">
                            {{ __('Transaction Logs') }}
                        </x-nav-link>
                    @endcan

                    {{-- Link Restock Orders (Admin, Manager, Supplier) --}}
                    @if (in_array(auth()->user()->role, ['admin', 'manager', 'supplier']))
                        <x-nav-link :href="route(auth()->user()->role . '.restock_orders.index')" :active="request()->routeIs(auth()->user()->role . '.restock_orders.index')">
                            {{ __('Restock Orders') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-900 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div class="text-electric-cyan">{{ Auth::user()->name }} ({{ strtoupper(Auth::user()->role) }})</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route($dashboardRoute)" :active="request()->routeIs($dashboardRoute)">
                {{ strtoupper(auth()->user()->role) }} DASHBOARD
            </x-responsive-nav-link>

            @can('viewAny', App\Models\Product::class)
                <x-responsive-nav-link :href="route(auth()->user()->role . '.products.index')" :active="request()->routeIs(auth()->user()->role . '.products.index')">
                    {{ __('Product Management') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-electric-cyan">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>