import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import flowbitePlugin from 'flowbite/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                shimmer: 'shimmer 2.5s linear infinite',
            },
            keyframes: {
                shimmer: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(100%)' },
                },
            },
            colors: {
                coffee: {
                    50: '#fbf7f2',
                    100: '#f6ede0',
                    200: '#ead5bd',
                    300: '#ddb892',
                    400: '#c89763',
                    500: '#a87444',
                    600: '#8a5c36',
                    700: '#6f472c',
                    800: '#573824',
                    900: '#3f291c',
                },
                cream: {
                    50: '#fffdf8',
                    100: '#fff7e8',
                    200: '#ffe8c2',
                    300: '#ffd28b',
                    400: '#ffb84d',
                    500: '#ff9f1a',
                    600: '#e68300',
                    700: '#b36300',
                    800: '#804600',
                    900: '#4d2900',
                },
            },
        },
    },

    plugins: [forms, flowbitePlugin],
};
