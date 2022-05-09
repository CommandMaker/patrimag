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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/css/app.scss', mix.inProduction() ? 'public/css/app.min.css' : 'public/css', {
        sassOptions : { outputStyle: mix.inProduction() ? 'compressed' : 'expanded' }
    })
    .sass('node_modules/bulma-scss/bulma.scss', mix.inProduction() ? 'public/css/bulma.min.css' : 'public/css', {
        sassOptions : { outputStyle: mix.inProduction() ? 'compressed' : 'expanded' }
    })
    .sourceMaps(false)
    .version();
