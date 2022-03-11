const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
.vue()
.extract(['vue', 'lodash', 'axios', 'vue-flux', '@headlessui/vue', '@heroicons/vue'])
.postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss/nesting'),
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);



if (mix.inProduction()) {
    mix.version();
}
