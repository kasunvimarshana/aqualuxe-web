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

mix.js('assets/src/js/navigation.js', 'assets/js')
   .js('assets/src/js/dark-mode.js', 'assets/js')
   .js('assets/src/js/custom.js', 'assets/js')
   .postCss('assets/src/css/tailwind.css', 'assets/css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .postCss('assets/src/css/custom.css', 'assets/css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .postCss('assets/src/css/dark-mode.css', 'assets/css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .options({
     processCssUrls: false
   });

// Production settings
if (mix.inProduction()) {
  mix.version();
}