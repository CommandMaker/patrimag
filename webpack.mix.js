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

mix.disableNotifications();

mix.js('resources/js/app.js', mix.inProduction() ? 'public/js/app.min.js' : 'public/js')
    .react()
    .sass('resources/css/app.scss', mix.inProduction() ? 'public/css/app.min.css' : 'public/css', {
        sassOptions : { outputStyle: mix.inProduction() ? 'compressed' : 'expanded' }
    })
    .sourceMaps(!mix.inProduction())
    .version();
