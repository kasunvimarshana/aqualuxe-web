const mix = require('laravel-mix');
require('laravel-mix-tailwind');

mix.setPublicPath('assets/dist')
   .js('assets/src/js/main.js', 'js')
   .js('assets/src/js/darkmode.js', 'js')
   .sass('assets/src/css/main.scss', 'css')
   .sass('assets/src/css/darkmode.scss', 'css')
   .tailwind()
   .copyDirectory('assets/src/images', 'assets/dist/images')
   .copyDirectory('assets/src/fonts', 'assets/dist/fonts')
   .options({
      processCssUrls: false,
      postCss: [require('autoprefixer')],
   })
   .version();
