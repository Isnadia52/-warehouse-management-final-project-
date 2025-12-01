<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-electric-cyan leading-tight">
            <div id="supplier-pending-header" 
                 class="typing-target" 
                 data-text="{{ __('ACCESS PENDING: WAITING FOR ADMIN APPROVAL') }}" 
                 style="min-height: 25px;">
            </div>
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        {{-- Menggunakan Alpine.js untuk mengelola status konfirmasi penghapusan --}}
        <div class="quantum-card overflow-hidden shadow-xl sm:rounded-lg p-8 text-center" 
             data-aos="fade-up"
             x-data="{ confirmingUserDeletion: false }">
            
            <i class="fas fa-exclamation-triangle text-neon-red text-6xl mb-4"></i>
            
            <h3 class="text-3xl font-bold text-neon-red mb-3">ACCOUNT UNDER REVIEW</h3>
            
            <p class="text-gray-300 mb-6">
                Akses Anda sebagai **Supplier** saat ini ditangguhkan.
                Akun Anda sedang ditinjau oleh Administrator sistem.
            </p>
            
            <p class="text-electric-cyan font-semibold">
                Silakan coba login kembali dalam waktu 24 jam.
            </p>
            
            <div class="mt-8 pt-4 border-t border-gray-700">
                
                {{-- Tombol utama untuk memicu tampilan konfirmasi --}}
                <button x-show="!confirmingUserDeletion" 
                        x-on:click="confirmingUserDeletion = true" 
                        class="text-sm text-gray-500 hover:text-neon-red transition duration-300 underline">
                    [ Delete Account ]
                </button>

                {{-- FORMULIR KONFIRMASI PASSWORD (Tampil hanya ketika confirmingUserDeletion = true) --}}
                <div x-show="confirmingUserDeletion" class="mt-4 p-4 border border-neon-red/50 bg-gray-900/50 rounded-lg">
                    <p class="text-sm text-neon-red mb-3 font-bold">
                        PERINGATAN: Masukkan Kata Sandi Anda untuk mengonfirmasi penghapusan akun.
                    </p>
                    <form method="POST" action="{{ route('profile.destroy') }}" class="flex flex-col items-center space-y-3">
                        @csrf
                        @method('delete')

                        <input id="password" 
                               name="password" 
                               type="password" 
                               class="w-full max-w-xs p-2 text-sm text-white bg-dark-charcoal border border-gray-600 rounded-md focus:border-electric-cyan focus:ring-electric-cyan" 
                               placeholder="Kata Sandi Anda"
                               required 
                               autocomplete="current-password">

                        <div class="flex space-x-3">
                            <button type="button" 
                                    x-on:click="confirmingUserDeletion = false" 
                                    class="py-2 px-4 text-sm font-semibold rounded-md bg-gray-600 hover:bg-gray-500 transition duration-300">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="py-2 px-4 text-sm font-semibold rounded-md bg-neon-red hover:bg-red-700 transition duration-300">
                                Hapus Permanen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>