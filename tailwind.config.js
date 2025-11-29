
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Pastikan dark mode aktif
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans], // Menggunakan Inter atau Montserrat/Poppins jika Anda suka
            },
            // --- Palet Warna Quantum Stockroom ---
            colors: {
                // Warna Dasar Dark Mode
                'dark-charcoal': '#121212', // Latar Belakang Paling Gelap (Navy Blue/Dark Charcoal)
                'electric-cyan': '#00FFFF', // Aksen Primer (Neon Green/Cyan)
                'neon-red': '#FF3131',      // Aksen Peringatan (Stok Rendah)
                'neon-green': '#39FF14',    // Aksen Persetujuan/Success
            },
            // --- Animasi Custom ---
            keyframes: {
                'typing-cursor': { // Animasi Cursor Berkedip
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0' },
                },
                'text-reveal': { // Animasi Ketik (Typing)
                    '0%': { width: '0' },
                    '100%': { width: '100%' },
                }
            },
            animation: {
                'cursor-blink': 'typing-cursor 0.7s infinite',
                'text-type': 'text-reveal 2s steps(40, end) forwards', // Sesuaikan durasi dan steps
            },
        },
    },

    plugins: [forms],
};