let mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');

mix.js('assets/src/js/theme.js', 'js')
  .postCss('assets/src/scss/theme.pcss', 'css', [
     require('tailwindcss'),
     require('autoprefixer')
   ])
  .postCss('assets/src/scss/screen.css', 'css', [
     require('tailwindcss'),
     require('autoprefixer')
   ])
   .options({ processCssUrls: true })
   .webpackConfig({
     output: { chunkFilename: 'js/[name].[contenthash].js' },
     resolve: { alias: { '@': path.resolve(__dirname, 'assets/src/js') } },
   })
  .copyDirectory('assets/src/images', 'assets/dist/images')
  .copyDirectory('assets/src/audio', 'assets/dist/audio')
  .copyDirectory('assets/src/models', 'assets/dist/models')
  .copyDirectory('assets/src/fonts', 'assets/dist/fonts')
  .copyDirectory('assets/src/sprites', 'assets/dist/sprites')
   .version();

// Copy static assets (images, audio, models, fonts) without external CDNs
mix.copyDirectory('assets/src/images', 'assets/dist/images');
mix.copyDirectory('assets/src/audio', 'assets/dist/audio');
mix.copyDirectory('assets/src/models', 'assets/dist/models');
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');
