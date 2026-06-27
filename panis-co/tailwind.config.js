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
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                panis: {
                    50: '#FAF6F0',
                    100: '#F5EDE0',
                    200: '#E8D5B5',
                    300: '#D4B896',
                    400: '#C9A227',
                    500: '#B8860B',
                    600: '#9A7209',
                    700: '#7A5A07',
                    800: '#5D4037',
                    900: '#3E2723',
                    950: '#2C1810',
                },
                gold: {
                    300: '#F0D78C',
                    400: '#D4AF37',
                    500: '#C9A227',
                    600: '#B8860B',
                },
            },
            backgroundImage: {
                'panis-gradient': 'linear-gradient(135deg, #3E2723 0%, #5D4037 50%, #7A5A07 100%)',
                'gold-shimmer': 'linear-gradient(90deg, #C9A227, #F0D78C, #C9A227)',
            },
        },
    },

    plugins: [forms],
};
