const mix = require('laravel-mix');
const path = require('path');

// Set public path for assets
mix.setPublicPath('assets/dist');

// Configure options
mix.options({
    processCssUrls: false,
    terser: {
        extractComments: false,
        terserOptions: {
            compress: {
                drop_console: true,
            },
        },
    },
});

// Process JavaScript files
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js');

// Process module JavaScript files
// These will be conditionally loaded based on module activation
mix.js('assets/src/js/modules/dark-mode.js', 'js/modules')
   .js('assets/src/js/modules/multilingual.js', 'js/modules')
   .js('assets/src/js/modules/subscriptions.js', 'js/modules')
   .js('assets/src/js/modules/bookings.js', 'js/modules')
   .js('assets/src/js/modules/events.js', 'js/modules')
   .js('assets/src/js/modules/wholesale.js', 'js/modules')
   .js('assets/src/js/modules/auctions.js', 'js/modules');

// Process WooCommerce specific JavaScript
mix.js('assets/src/js/woocommerce.js', 'js');

// Process SCSS files
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sass('assets/src/scss/editor-style.scss', 'css')
   .sass('assets/src/scss/woocommerce.scss', 'css');

// Process module SCSS files
mix.sass('assets/src/scss/modules/dark-mode.scss', 'css/modules')
   .sass('assets/src/scss/modules/multilingual.scss', 'css/modules')
   .sass('assets/src/scss/modules/subscriptions.scss', 'css/modules')
   .sass('assets/src/scss/modules/bookings.scss', 'css/modules')
   .sass('assets/src/scss/modules/events.scss', 'css/modules')
   .sass('assets/src/scss/modules/wholesale.scss', 'css/modules')
   .sass('assets/src/scss/modules/auctions.scss', 'css/modules');

// Process Tailwind CSS
mix.postCss('assets/src/scss/tailwind.css', 'css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// BrowserSync for local development
if (!mix.inProduction()) {
    mix.browserSync({
        proxy: 'localhost',
        files: [
            'assets/dist/**/*',
            '**/*.php',
        ],
        notify: false
    });
}