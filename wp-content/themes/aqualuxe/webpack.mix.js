const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');

mix.js('assets/src/js/app.js', 'js')
   .sass('assets/src/scss/main.scss', 'css')
   .options({
     processCssUrls: false,
     postCss: [require('tailwindcss'), require('autoprefixer')]
   })
   .webpackConfig({
     output: { chunkFilename: 'js/[name].[chunkhash].js' },
     resolve: { alias: { '@': path.resolve(__dirname, 'assets/src/js') } },
   })
   .copyDirectory('assets/src/images', 'assets/dist/images')
   .copyDirectory('assets/src/fonts', 'assets/dist/fonts')
   .sourceMaps(false, 'source-map')
   .version();

if (!mix.inProduction()) {
  mix.webpackConfig({ devtool: 'source-map' });
}
