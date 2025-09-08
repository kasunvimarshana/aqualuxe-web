const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');

mix.sass('assets/src/scss/app.scss', 'css')
  .options({
    processCssUrls: false,
    postCss: [require('tailwindcss'), require('autoprefixer')]
  });

mix.js('assets/src/js/app.js', 'js');

if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps();
}

mix.webpackConfig({
  output: { chunkFilename: 'js/[name].js' },
  resolve: { alias: { '@': path.resolve(__dirname, 'assets/src/js') } }
});
