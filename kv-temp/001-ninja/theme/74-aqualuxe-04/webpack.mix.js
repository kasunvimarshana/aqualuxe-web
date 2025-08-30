const mix = require('laravel-mix');
require('laravel-mix-versionhash');
const path = require('path');

mix.setPublicPath('assets/dist');

mix.webpackConfig({
  mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
  output: {
    chunkFilename: 'js/[name].[chunkhash].js',
    clean: true,
  },
  resolve: {
    alias: {
      '@js': path.resolve(__dirname, 'assets/src/js'),
      '@scss': path.resolve(__dirname, 'assets/src/scss'),
      '@img': path.resolve(__dirname, 'assets/src/img'),
    },
  },
});

mix.options({
  processCssUrls: false,
  terser: { extractComments: false },
  postCss: [require('postcss-import'), require('tailwindcss'), require('autoprefixer')],
});

mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/woo.js', 'js')
   .sass('assets/src/scss/app.scss', 'css')
   .sourceMaps(false, 'source-map')
   .versionHash();

mix.copyDirectory('assets/src/img', 'assets/dist/img');
