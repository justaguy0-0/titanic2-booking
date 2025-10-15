import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            'custom-amber': {
                300: '#fcd34d',
                400: '#fbbf24',
                500: '#f59e0b',
                700: '#a6801f',
            },
            'custom-slate': {
                800: '#1e293b',
                700: '#334155',
            }
        },
    },

    plugins: [forms],
};
