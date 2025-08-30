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
   .js('assets/src/js/customizer-preview.js', 'js')
   .js('assets/src/js/customizer-controls.js', 'js');

// Process WooCommerce JavaScript if needed
if (require('fs').existsSync('assets/src/js/woocommerce.js')) {
    mix.js('assets/src/js/woocommerce.js', 'js');
}

// Process SASS files
mix.sass('assets/src/sass/main.scss', 'css')
   .sass('assets/src/sass/admin.scss', 'css')
   .sass('assets/src/sass/customizer-controls.scss', 'css')
   .options({
       processCssUrls: false,
       postCss: [
           require('postcss-import'),
           require('postcss-nested'),
           tailwindcss('./tailwind.config.js'),
           require('autoprefixer'),
       ],
   });

// Process WooCommerce SASS if needed
if (require('fs').existsSync('assets/src/sass/woocommerce.scss')) {
    mix.sass('assets/src/sass/woocommerce.scss', 'css')
       .options({
           processCssUrls: false,
           postCss: [
               require('postcss-import'),
               require('postcss-nested'),
               tailwindcss('./tailwind.config.js'),
               require('autoprefixer'),
           ],
       });
}

// Copy images and fonts
mix.copyDirectory('assets/src/images', 'assets/dist/images');
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// Enable BrowserSync for development
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