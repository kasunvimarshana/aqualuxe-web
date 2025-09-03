const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');

mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/vendor.js', 'js')
   .js('assets/src/js/hero.js', 'js')
   .sass('assets/src/scss/style.scss', 'css')
   .sass('assets/src/scss/editor.scss', 'css')
   .sass('assets/src/scss/skin-default.scss', 'css')
   .sass('assets/src/scss/skin-dark.scss', 'css')
   .options({
     processCssUrls: false,
     postCss: [require('tailwindcss'), require('autoprefixer')]
   })
   .webpackConfig({
     output: { chunkFilename: 'js/[name].[chunkhash:8].js' },
     resolve: { alias: { '@': path.resolve(__dirname, 'assets/src/js') } }
   })
   .version();

// Copy static assets if present
mix.copyDirectory('assets/src/images', 'assets/dist/images');
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');
