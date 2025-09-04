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

// Admin importer assets
mix.js('assets/src/admin/importer.js', 'js')
  .postCss('assets/src/admin/importer.css', 'css', [
    require('postcss-import'),
    require('autoprefixer')
  ])
  .options({ processCssUrls: false })
  .version();

// Copy static assets (images/fonts) from src to dist
mix.copyDirectory('assets/src/img', 'assets/dist/img');
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

if (!mix.inProduction()) {
  mix.sourceMaps();
}
