module.exports = {
    darkMode: ["selector", '[class*="app-dark"]'],
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.{vue,js,ts,jsx,tsx}",
    ],
    plugins: [require("tailwindcss-primeui")],
    theme: {
        screens: {
            sm: "576px",
            md: "768px",
            lg: "992px",
            xl: "1200px",
            "2xl": "1920px",
        },
    },
};

//import defaultTheme from 'tailwindcss/defaultTheme';
//import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
//export default {
//    content: [
//        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
//        './storage/framework/views/*.php',
//        './resources/views/**/*.blade.php',
//        './resources/js/**/*.vue',
//    ],

//    theme: {
//        extend: {
//            fontFamily: {
//                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
//            },
//        },
//    },

//    plugins: [forms],
//};
