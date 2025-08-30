const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const path = require('path');

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

// Set public path
mix.setPublicPath('assets/dist');

// Process JavaScript files
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/dark-mode.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/admin.js', 'js');

// Process WooCommerce JavaScript files if they exist
if (mix.inProduction()) {
    mix.js('assets/src/js/woocommerce.js', 'js')
       .js('assets/src/js/woocommerce-fallback.js', 'js');
}

// Process CSS files
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/woocommerce.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sass('assets/src/scss/editor-style.scss', 'css')
   .options({
       processCssUrls: false,
       postCss: [
           tailwindcss('./tailwind.config.js'),
           require('autoprefixer'),
       ],
   });

// Copy images and fonts
mix.copyDirectory('assets/src/images', 'assets/dist/images')
   .copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable source maps in development
if (!mix.inProduction()) {
    mix.sourceMaps();
}

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// Configure Webpack
mix.webpackConfig({
    externals: {
        jquery: 'jQuery'
    },
    resolve: {
        alias: {
            '@': path.resolve('assets/src/js'),
        }
    }
});

// Disable OS notifications
mix.disableNotifications();