<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark"> <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
       /* CSS Kustom untuk Glassmorphism & Neon */
        .quantum-card {
            /* 1. Warna Card: Dark Gray Semi-Transparan (#1A1A1A / 0.7) */
            background-color: rgba(26, 26, 26, 0.7); 
            backdrop-filter: blur(10px); /* Tingkatkan blur sedikit untuk kesan kaca */
            
            /* 2. Border & Glow Electric Cyan */
            border: 1px solid #00FFFF; /* Electric Cyan Border */
            
            /* Box Shadow (Aura luar) + Inset Shadow (Aura dalam) */
            box-shadow: 
                0 0 12px rgba(0, 255, 255, 0.6), /* Outer Glow */
                inset 0 0 5px rgba(0, 255, 255, 0.3); /* Inner Glow */
        }

        /* Efek Hover (Opsional, untuk sentuhan Parallax/Kedalaman) */
        .quantum-card:hover {
            transform: perspective(1000px) rotateX(1deg) rotateY(1deg); /* Parallax Effect minimal */
            box-shadow: 
                0 0 20px rgba(0, 255, 255, 0.8), /* Intensitas Glow meningkat saat hover */
                inset 0 0 8px rgba(0, 255, 255, 0.5); 
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body class="font-sans antialiased bg-dark-charcoal text-gray-100">
    <div class="min-h-screen">
        @include('layouts.navigation')

        @if (isset($header))
            <header class="bg-gray-900 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
      AOS.init({
          duration: 800, // Durasi Animasi
          once: true,    // Hanya animasikan saat pertama kali muncul
      });
    </script>
</body>
</html>