const mix = require('laravel-mix');
const path = require('path');

// Set public path
mix.setPublicPath('assets/dist');

// Configure BrowserSync
mix.browserSync({
    proxy: 'http://localhost',
    files: [
        'assets/dist/**/*',
        '**/*.php',
    ],
    open: false,
    notify: false
});

// Process SASS files
mix.sass('assets/src/sass/main.scss', 'css')
   .sass('assets/src/sass/admin.scss', 'css')
   .sass('assets/src/sass/editor.scss', 'css')
   .sass('assets/src/sass/woocommerce.scss', 'css')
   .sass('assets/src/sass/dark-mode.scss', 'css')
   .options({
       processCssUrls: false,
       postCss: [
           require('postcss-import'),
           require('tailwindcss'),
           require('autoprefixer'),
       ],
   });

// Process JavaScript files
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/editor.js', 'js')
   .js('assets/src/js/woocommerce.js', 'js')
   .js('assets/src/js/dark-mode.js', 'js');

// Copy fonts and images
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Version files in production
if (mix.inProduction()) {
    mix.version();
}