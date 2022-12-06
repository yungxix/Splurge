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

mix.ts('resources/js/app.ts', 'public/js')
.react()
.extract(['react', 'react-dom', 'lodash', 'axios'])
.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.DefinePlugin({
                "PRODUCTION": JSON.stringify(mix.inProduction())
            })
        ]
    }
}).ts('resources/js/v2/components/admin/customer_events/index.tsx', 'public/js/customer_events.js')
.react()
.extract(['react', 'react-dom', 'lodash', 'axios'])
.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.DefinePlugin({
                "PRODUCTION": JSON.stringify(mix.inProduction())
            })
        ]
    }
})
.postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss/nesting'),
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]).sourceMaps()
;



if (mix.inProduction()) {
    mix.version();
}
