const mix = require('laravel-mix');
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
mix.setPublicPath('assets');

// Compile Tailwind CSS
mix.postCss('src/css/main.css', 'css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Compile WooCommerce CSS
mix.postCss('src/css/woocommerce.css', 'css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Compile Admin CSS
mix.postCss('src/css/admin.css', 'css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Compile JavaScript
mix.js('src/js/main.js', 'js')
   .js('src/js/customizer.js', 'js')
   .js('src/js/admin.js', 'js');

// Add version string to assets in production
if (mix.inProduction()) {
    mix.version();
}

// BrowserSync for local development
mix.browserSync({
    proxy: 'localhost',
    files: [
        'assets/css/**/*.css',
        'assets/js/**/*.js',
        '**/*.php'
    ],
    notify: false
});

// Disable success notifications
mix.disableSuccessNotifications();