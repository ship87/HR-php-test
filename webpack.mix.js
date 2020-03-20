let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sourceMaps()
    .scripts([
        'node_modules/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js',
    ], 'public/js/vendor.js')
    .js(['resources/assets/js/app.js'],    'public/js').version()
    .styles(['resources/assets/css/style.css'], 'public/css/style.css').version()
    .sass('resources/assets/sass/app.scss', 'public/css')
    .babel(['resources/js/*.js'], 'public/js/script.js');
