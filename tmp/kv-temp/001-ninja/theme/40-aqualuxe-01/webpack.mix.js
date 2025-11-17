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

// Set the public path to the assets directory
mix.setPublicPath('assets/dist');

// Process JavaScript files
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/admin.js', 'js');

// Process WooCommerce JavaScript if needed
if (require('fs').existsSync('assets/src/js/woocommerce.js')) {
    mix.js('assets/src/js/woocommerce.js', 'js');
}

// Process CSS files with PostCSS and Tailwind
mix.sass('assets/src/css/main.scss', 'css')
   .sass('assets/src/css/editor.scss', 'css')
   .sass('assets/src/css/admin.scss', 'css')
   .options({
       processCssUrls: false,
       postCss: [
           tailwindcss('./tailwind.config.js'),
           require('autoprefixer'),
       ],
   });

// Process WooCommerce CSS if needed
if (require('fs').existsSync('assets/src/css/woocommerce.scss')) {
    mix.sass('assets/src/css/woocommerce.scss', 'css')
       .options({
           processCssUrls: false,
           postCss: [
               tailwindcss('./tailwind.config.js'),
               require('autoprefixer'),
           ],
       });
}

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable versioning in production
if (mix.inProduction()) {
    mix.version();
}

// Source maps
mix.sourceMaps();

// Browser sync for local development
if (!mix.inProduction()) {
    mix.browserSync({
        proxy: 'localhost',
        files: [
            'assets/dist/**/*',
            '**/*.php',
        ],
    });
}