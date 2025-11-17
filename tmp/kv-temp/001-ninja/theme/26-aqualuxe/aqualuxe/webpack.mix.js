const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining Webpack build steps for
 | your AquaLuxe WordPress theme. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('src/js/app.js', 'assets/js')
   .postCss('src/css/main.css', 'assets/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
   ])
   .postCss('src/css/editor.css', 'assets/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
   ])
   .postCss('src/css/admin.css', 'assets/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
   ])
   .options({
        processCssUrls: false
   })
   .sourceMaps(false, 'source-map')
   .version();

// Copy images from src to assets
mix.copyDirectory('src/images', 'assets/images');

// Production specific configurations
if (mix.inProduction()) {
    mix.version();
}