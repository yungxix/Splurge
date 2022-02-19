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
