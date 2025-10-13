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
                maroon: {
                    50: '#fdf2f2',
                    100: '#fce7e7',
                    200: '#f9d2d2',
                    300: '#f4b1b1',
                    400: '#ec8484',
                    500: '#e05c5c',
                    600: '#cc3f3f',
                    700: '#a83232',
                    800: '#8b2e2e',
                    900: '#722c2c',
                    950: '#3e1414',
                },
                medical: {
                    primary: '#722c2c',
                    secondary: '#8b2e2e',
                    accent: '#a83232',
                    light: '#f9d2d2',
                    dark: '#3e1414',
                }
            }
        },
    },

    plugins: [forms],
};
