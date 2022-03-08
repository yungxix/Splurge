const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                splurge: {
                    50: "#feff3d",
                    100: "#f4fb33",
                    200: "#eaf129",
                    300: "#e0e71f",
                    400: "#d6dd15",
                    500: "#ccd30b",
                    600: "#c2c901",
                    700: "#b8bf00",
                    800: "#aeb500",
                    900: "#a4ab00"
                },
                splarge: {
                    50: "#955086",
                    100: "#8b467c",
                    200: "#813c72",
                    300: "#773268",
                    400: "#6d285e",
                    500: "#631e54",
                    600: "#59144a",
                    700: "#4f0a40",
                    800: "#450036",
                    900: "#3b002c"
                },
            },
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            container: {
                padding: {
                    DEFAULT: '1rem',
                    sm: '2rem',
                    lg: '4rem',
                    xl: '6rem',
                    '2xl': '8rem',
                },
            },
        },
        
    },

    

    plugins: [require('@tailwindcss/forms')],
};
