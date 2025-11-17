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
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/woocommerce.js', 'js');

// Process SCSS files
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sass('assets/src/scss/woocommerce.scss', 'css')
   .options({
      processCssUrls: false,
      postCss: [
         require('postcss-import'),
         require('postcss-nested'),
         tailwindcss('./tailwind.config.js'),
         require('autoprefixer'),
      ],
   });

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Version files in production
if (mix.inProduction()) {
   mix.version();
}

// Add source maps in development
if (!mix.inProduction()) {
   mix.sourceMaps();
}

// Configure BrowserSync
mix.browserSync({
   proxy: 'localhost',
   files: [
      'assets/dist/**/*',
      '**/*.php',
   ],
   open: false,
});

// Disable OS notifications
mix.disableNotifications();