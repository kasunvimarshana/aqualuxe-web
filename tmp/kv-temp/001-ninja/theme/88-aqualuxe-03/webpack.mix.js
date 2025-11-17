const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');
mix.webpackConfig({
  output: { chunkFilename: 'js/[name].[chunkhash].js' },
  resolve: { alias: { '@': path.resolve(__dirname, 'assets/src/js') } }
});

mix.js('assets/src/js/app.js', 'assets/dist/js')
  .js('assets/src/js/admin.js', 'assets/dist/js')
   .postCss('assets/src/css/app.css', 'assets/dist/css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer')
   ])
   .options({ processCssUrls: false })
   .sourceMaps(false, 'source-map');

if (mix.inProduction()) {
  mix.version();
}
const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');

mix.js('assets/src/js/app.js', 'js')
   .postCss('assets/src/css/app.css', 'css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer')
   ])
   .options({
     processCssUrls: false,
   })
   .webpackConfig({
     output: {
       chunkFilename: 'js/[name].js?id=[chunkhash]',
     },
     resolve: {
       alias: {
         '@': path.resolve(__dirname, 'assets/src/js'),
       }
     }
   })
   .version();
