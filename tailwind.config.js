import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                midnight: '#191970',
                lightblue: '#ADD8E6',
                combo: '#274c77',
                /* USTP Official Colors */
                ustpGold: '#FFB703',
                ustpBlue: '#023047',
                ustpBlack: '#000000',
                ustpGray: '#F5F5F5'
            },
        },
    },

    plugins: [forms],
};
