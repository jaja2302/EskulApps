import defaultTheme from 'tailwindcss/defaultTheme';
import preset from './vendor/filament/support/tailwind.config.preset'
 



/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    presets: [preset],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                coral: {
                    50: '#fff5f2',
                    100: '#ffe6e1',
                    200: '#ffc9bc',
                    300: '#ffa28e',
                    400: '#ff7a5c',
                    500: '#ff4d2e',
                    600: '#ed3015',
                    700: '#c5250f',
                    800: '#9e2211',
                    900: '#802012',
                },
            },
        },
    },
    plugins: [],
};
