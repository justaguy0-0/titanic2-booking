import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // ← ПЕРЕНОСИМ свои цвета в extend, а не перезаписываем всё!
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
                },
            },
        },
        // ← УДАЛЯЕМ полную перезапись colors, оставляем только extend выше
    },

    safelist: [
        // Динамические алерты
        {
            pattern: /bg-(red|yellow|green|blue|purple|orange|gray)-(50|100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /text-(red|yellow|green|blue|purple|orange|gray)-(50|100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /border-(red|yellow|green|blue|purple|orange)-(400|500)/,
        },

        // Ховеры для кнопок
        {
            pattern: /hover:bg-(blue|green|purple|orange|red|yellow|gray)-(500|600|700)/,
        },

        // Градиенты в карточках
        'bg-gradient-to-br',
        {
            pattern: /from-(blue|green|purple|orange)-(500)/,
        },
        {
            pattern: /to-(blue|green|purple|orange)-(600)/,
        },

        // Часто используемые в админке
        'bg-gray-50',
        'bg-white',
        'shadow-lg',
        'shadow-md',
        'rounded-lg',
        'transition',
    ],

    plugins: [forms],
};
