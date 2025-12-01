<!-- File: resources/views/categories/create.blade.php (Koreksi Lengkap) -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <span class="inline-block overflow-hidden whitespace-nowrap border-r-4 border-electric-cyan animate-type-and-blink">
                {{ __('ENCODE NEW CATEGORY') }}
            </span>
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8" data-aos="fade-up">
            
            {{-- ERROR HANDLING GLOBAL --}}
            @if ($errors->any())
                <div class="bg-neon-red/20 border border-neon-red text-white p-4 rounded mb-4">
                    <h4 class="font-bold mb-2">Category Encoding Failed: Review Input Data</h4>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- PASTIKAN ADA enctype="multipart/form-data" --}}
            <form method="POST" action="{{ route(auth()->user()->role . '.categories.store') }}" enctype="multipart/form-data">
                @csrf

                <h3 class="text-2xl font-bold text-neon-green mb-6 border-b border-gray-700 pb-3">Category Data</h3>

                {{-- Name --}}
                <div class="mb-6">
                    <x-input-label for="name" :value="__('Category Name')" class="text-electric-cyan" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white" :value="old('name')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <x-input-label for="description" :value="__('Description')" class="text-electric-cyan" />
                    <textarea id="description" name="description" rows="3" class="mt-1 block w-full bg-gray-900 border-gray-700 text-white rounded-md shadow-sm">{{ old('description') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
                
                {{-- Image (Optional) --}}
                <div class="mb-6">
                    <x-input-label for="image" :value="__('Category Icon/Image (Optional, Max 2MB)')" class="text-electric-cyan" />
                    <input id="image" name="image" type="file" class="mt-1 block w-full text-white bg-gray-900 border-gray-700 rounded-md shadow-sm" />
                    <x-input-error class="mt-2" :messages="$errors->get('image')" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a href="{{ route(auth()->user()->role . '.categories.index') }}" class="text-gray-400 hover:text-white mr-4 transition duration-300">
                        Cancel
                    </a>

                    <x-primary-button class="bg-neon-green hover:bg-green-600 text-dark-charcoal font-bold py-2 px-4">
                        {{ __('Save Category Data') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>