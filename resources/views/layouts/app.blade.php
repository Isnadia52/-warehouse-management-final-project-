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
        .quantum-card {
            background-color: rgba(26, 26, 26, 0.7); 
            backdrop-filter: blur(10px);
            
            border: 1px solid #00FFFF; 
            
            box-shadow: 
                0 0 12px rgba(0, 255, 255, 0.6),
                inset 0 0 5px rgba(0, 255, 255, 0.3);
        }

        .quantum-card:hover {
            transform: perspective(1000px) rotateX(1deg) rotateY(1deg);
            box-shadow: 
                0 0 20px rgba(0, 255, 255, 0.8), 
                inset 0 0 8px rgba(0, 255, 255, 0.5); 
            transition: all 0.3s ease-in-out;
        }

        @keyframes blink {
            0%, 100% { border-right-color: transparent; }
            50% { border-right-color: #00FFFF; }
        }
        .typing-header {
            /* Mengatur text agar tidak terbungkus */
            display: inline-block;
            /* Cursor Style */
            border-right: 4px solid #00FFFF;
            animation: blink 0.7s step-end infinite;
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
          duration: 800,
          once: true,   
      });
    </script>
    <script>
        function initTypingEffect() {
            // Cari semua elemen dengan class 'typing-target'
            const elements = document.querySelectorAll('.typing-target');
            
            elements.forEach(el => {
                const fullText = el.getAttribute('data-text');
                if (!fullText) return; 

                let i = 0;
                el.textContent = ''; // Kosongkan teks awal
                el.classList.add('typing-header'); // Tambahkan cursor

                function type() {
                    if (i < fullText.length) {
                        el.textContent += fullText.charAt(i);
                        i++;
                        setTimeout(type, 50); // Kecepatan ketik (50ms)
                    } else {
                        el.classList.remove('typing-header'); // Hapus cursor setelah selesai ketik
                        el.classList.add('typing-header-done'); // Tambahkan class untuk cursor berkedip di tempat
                    }
                }

                // Tambahkan delay kecil sebelum mulai mengetik
                setTimeout(type, 300);
            });
        }
        
        // Jalankan fungsi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', initTypingEffect);
    </script>
</body>
</html>