const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');
mix.setResourceRoot('/wp-content/themes/aqualuxe/assets/dist/');

mix.js('assets/src/js/app.js', 'js')
  .postCss('assets/src/css/app.css', 'css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer')
  ])
  .options({ processCssUrls: false })
  .version();

if (!mix.inProduction()) {
  mix.sourceMaps();
}
