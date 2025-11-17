const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your AquaLuxe WordPress theme. By default, we are compiling the CSS
 | and JavaScript files for the theme, as well as bundling up all assets.
 |
 */

// Public path for assets
mix.setPublicPath('assets/dist');

// JavaScript compilation
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/modules/dark-mode.js', 'js/modules')
   .js('assets/src/js/modules/woocommerce.js', 'js/modules')
   .js('assets/src/js/modules/multilingual.js', 'js/modules')
   .js('assets/src/js/modules/demo-importer.js', 'js/modules');

// CSS compilation with Tailwind CSS
mix.postCss('assets/src/css/main.css', 'css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .postCss('assets/src/css/admin.css', 'css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .postCss('assets/src/css/woocommerce.css', 'css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .postCss('assets/src/css/dark-mode.css', 'css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ]);

// SCSS compilation for complex styles
mix.sass('assets/src/scss/editor-style.scss', 'css')
   .sass('assets/src/scss/rtl.scss', 'css');

// Copy static assets
mix.copyDirectory('assets/src/images', 'assets/dist/images')
   .copyDirectory('assets/src/fonts', 'assets/dist/fonts')
   .copyDirectory('assets/src/icons', 'assets/dist/icons');

// Options
mix.options({
     processCssUrls: false,
     postCss: [
       require('autoprefixer'),
     ]
   })
   .sourceMaps(false, 'source-map')
   .version()
   .disableNotifications();

// Browser Sync for development
if (!mix.inProduction()) {
  mix.browserSync({
    proxy: 'localhost:8080', // Adjust based on your local WordPress setup
    files: [
      'assets/dist/**/*',
      '**/*.php',
      '**/*.html'
    ],
    watchOptions: {
      usePolling: true,
      interval: 100
    }
  });
}

// Extract vendor libraries
mix.extract(['gsap', 'swiper']);

const path = require('path');

// Webpack configuration
mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve('assets/src'),
      '@js': path.resolve('assets/src/js'),
      '@css': path.resolve('assets/src/css'),
      '@scss': path.resolve('assets/src/scss'),
      '@images': path.resolve('assets/src/images')
    }
  }
});