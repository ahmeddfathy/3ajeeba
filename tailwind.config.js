import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Tajawal', 'Figtree', ...defaultTheme.fontFamily.sans],
                display: ['"Noto Naskh Arabic"', 'Tajawal', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                store: {
                    page: '#F8F1EA',
                    surface: '#FEFCFB',
                    soft: '#F7F3EF',
                    hero: '#F0E4DA',
                    accent: '#A36F50',
                    dark: '#76503C',
                    text: '#3F332C',
                    muted: '#776B64',
                    border: '#E9DED5',
                },
            },
        },
    },

    plugins: [forms, typography],
};
