const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

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
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/editor.js', 'js')
   .js('assets/src/js/dark-mode.js', 'js');

// Process WooCommerce JavaScript if needed
if (require('fs').existsSync('assets/src/js/woocommerce.js')) {
    mix.js('assets/src/js/woocommerce.js', 'js');
}

// Process SCSS files
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sass('assets/src/scss/editor.scss', 'css')
   .sass('assets/src/scss/dark-mode.scss', 'css')
   .options({
       processCssUrls: false,
       postCss: [
           tailwindcss('./tailwind.config.js'),
           require('autoprefixer'),
       ],
   });

// Process WooCommerce SCSS if needed
if (require('fs').existsSync('assets/src/scss/woocommerce.scss')) {
    mix.sass('assets/src/scss/woocommerce.scss', 'css')
       .options({
           processCssUrls: false,
           postCss: [
               tailwindcss('./tailwind.config.js'),
               require('autoprefixer'),
           ],
       });
}

// Copy images and fonts
mix.copyDirectory('assets/src/images', 'assets/dist/images');
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable source maps in development
if (!mix.inProduction()) {
    mix.sourceMaps();
}

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// Configure BrowserSync for development
if (!mix.inProduction()) {
    mix.browserSync({
        proxy: 'localhost',
        files: [
            'assets/dist/**/*',
            '**/*.php',
        ],
        notify: false,
    });
}