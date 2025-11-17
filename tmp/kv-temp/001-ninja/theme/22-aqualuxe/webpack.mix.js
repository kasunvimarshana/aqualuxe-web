const mix = require('laravel-mix');

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

mix.js('src/js/main.js', 'assets/js')
   .js('src/js/navigation.js', 'assets/js')
   .js('src/js/skip-link-focus-fix.js', 'assets/js')
   .js('src/js/dark-mode.js', 'assets/js')
   .js('src/js/woocommerce.js', 'assets/js')
   .js('src/js/quick-view.js', 'assets/js')
   .postCss('src/css/main.css', 'assets/css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .postCss('src/css/editor-style.css', 'assets/css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .postCss('src/css/woocommerce.css', 'assets/css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .postCss('src/css/rtl.css', 'assets/css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ]);

// Production settings
if (mix.inProduction()) {
  mix.version();
}

// Disable success notifications
mix.disableSuccessNotifications();

// Set public path
mix.setPublicPath('./');