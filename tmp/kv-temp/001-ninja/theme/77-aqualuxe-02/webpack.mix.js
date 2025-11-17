const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');

mix.js('assets/src/js/app.js', 'js')
  .postCss('assets/src/css/app.css', 'css', [
      require('postcss-import'),
      require('tailwindcss'),
      require('autoprefixer'),
   ])
  .postCss('assets/src/css/editor.css', 'css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
  ])
   .options({
      processCssUrls: false,
   })
   .sourceMaps(false, 'source-map')
   .version();

mix.webpackConfig({
  output: {
    chunkFilename: 'js/[name].[chunkhash].js',
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'assets/src/js'),
    }
  }
});

// Copy static assets
mix.copyDirectory('assets/src/images', 'assets/dist/images');
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');
