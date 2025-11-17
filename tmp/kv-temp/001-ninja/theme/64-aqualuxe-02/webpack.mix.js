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
   .js('assets/src/js/customizer-controls.js', 'js')
   .js('assets/src/js/customizer-preview.js', 'js')
   .js('assets/src/js/dark-mode.js', 'js')
   .js('assets/src/js/multilingual.js', 'js')
   .js('assets/src/js/demo-importer.js', 'js')
   .js('assets/src/js/woocommerce.js', 'js');

// Process SCSS files
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sass('assets/src/scss/customizer.scss', 'css')
   .sass('assets/src/scss/demo-importer.scss', 'css')
   .sass('assets/src/scss/woocommerce.scss', 'css')
   .options({
      processCssUrls: false,
      postCss: [
         tailwindcss('./tailwind.config.js'),
         require('autoprefixer')
      ],
   });

// Copy images and fonts
mix.copyDirectory('assets/src/images', 'assets/dist/images');
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable versioning in production
if (mix.inProduction()) {
   mix.version();
}

// BrowserSync for local development
mix.browserSync({
   proxy: 'localhost',
   files: [
      'assets/dist/**/*',
      '**/*.php',
   ],
   open: false
});

// Disable success notifications
mix.disableSuccessNotifications();