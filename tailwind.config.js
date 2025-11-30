
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', 
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'dark-charcoal': '#121212', 
                'electric-cyan': '#00FFFF', 
                'neon-red': '#FF3131',      
                'neon-green': '#39FF14',    
            },
            keyframes: {
                'typing-cursor': { 
                    '0%, 100%': { borderRightColor: 'transparent' },
                    '50%': { borderRightColor: '#00FFFF' }, 
                },
                'text-reveal': { 
                    '0%': { width: '0' },
                    '100%': { width: '100%' },
                }
            },
            animation: {

                'type-and-blink': 'text-reveal 1.5s steps(30, end) forwards, typing-cursor 0.5s step-end infinite alternate',
            },
        },
    },

    plugins: [forms],
};