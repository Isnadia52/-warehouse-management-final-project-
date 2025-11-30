<!DOCTYPE html>
{{-- Memaksa Dark Mode --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark"> 
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'QuantumStock') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .quantum-bg {
                background-color: #121212;
                background-image: radial-gradient(#00FFFF33 1px, transparent 1px);
                background-size: 20px 20px;
            }

            .quantum-card-login {
                background-color: rgba(26, 26, 26, 0.8); 
                backdrop-filter: blur(8px);
                border: 1px solid #00FFFF; 
                box-shadow: 0 0 15px rgba(0, 255, 255, 0.4);
            }
            
            .dark input[type="text"],
            .dark input[type="email"],
            .dark input[type="password"],
            .dark select,
            .dark textarea {
                background-color: #1a1a1a !important;
                border-color: #00FFFF66 !important; 
                color: #ffffff !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased quantum-bg">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            {{-- Logo dan Judul Aplikasi --}}
            <div class="mb-6">
                <span class="text-3xl font-bold text-electric-cyan">
                    Quantum<span class="text-neon-green">Stock</span>
                </span>
            </div>

            {{-- Formulir Login / Register (Menerapkan Quantum Card) --}}
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 quantum-card-login shadow-lg overflow-hidden sm:rounded-lg">
                <p class="text-xl text-center text-neon-green font-bold mb-6 border-b border-gray-700 pb-3">
                    ACCESS TERMINAL
                </p>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>