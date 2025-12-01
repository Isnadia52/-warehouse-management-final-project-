<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <div id="restock_orders-target" 
                 class="typing-target" 
                 data-text="{{ __('SUBMIT SUPPLIER RATING') }}" 
                 style="min-height: 25px;">
            </div>
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8" data-aos="fade-up">
            
            <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3">Rate Supplier Performance for PO: {{ $restock_order->po_number }}</h3>
            
            <form method="POST" action="{{ route('manager.restock_orders.update', $restock_order) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="submit_rating">

                {{-- Rating Field --}}
                <div class="mb-6">
                    <x-input-label for="rating" :value="__('Rating (1-5, 5=Excellent)')" class="text-electric-cyan" />
                    <x-text-input id="rating" name="rating" type="number" min="1" max="5" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('rating')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('rating')" />
                </div>

                {{-- Feedback Field --}}
                <div class="mb-6">
                    <x-input-label for="feedback" :value="__('Feedback Notes (Optional)')" class="text-electric-cyan" />
                    <textarea id="feedback" name="feedback" rows="3" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm">{{ old('feedback') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('feedback')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route('manager.restock_orders.show', $restock_order) }}" class="text-gray-400 hover:text-white mr-4 transition duration-300">
                        Cancel
                    </a>

                    <x-primary-button class="bg-neon-green hover:bg-green-600 text-dark-charcoal font-bold py-2 px-4">
                        {{ __('Submit Quantum Rating') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>