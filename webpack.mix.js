const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Set public path
mix.setPublicPath('assets/dist');

// Configure options
mix.options({
  processCssUrls: false,
  postCss: [tailwindcss('./tailwind.config.js')],
});

// JavaScript
mix.js('assets/src/js/app.js', 'assets/dist/js')
   .js('assets/src/js/admin.js', 'assets/dist/js');

// CSS/SCSS
mix.sass('assets/src/scss/app.scss', 'assets/dist/css')
   .sass('assets/src/scss/admin.scss', 'assets/dist/css')
   .sass('assets/src/scss/woocommerce.scss', 'assets/dist/css');

// Copy assets
mix.copyDirectory('assets/src/images', 'assets/dist/images')
   .copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Versioning and source maps
if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps();
}

// BrowserSync
if (!mix.inProduction()) {
  mix.browserSync({
    proxy: 'localhost:8080', // Adjust for your local development URL
    files: [
      '**/*.php',
      'assets/dist/**/*'
    ],
    watchOptions: {
      ignored: /node_modules/
    }
  });
}

// Extract vendor libraries
mix.extract(['alpinejs', 'swiper']);

// Additional webpack configuration
mix.webpackConfig({
  stats: {
    children: true,
  },
  resolve: {
    alias: {
      '@': path.resolve('assets/src'),
    },
  },
});

// Disable Mix's default notifications
mix.disableNotifications();