const mix = require('laravel-mix');

mix.setPublicPath('assets/dist')
   .js('assets/src/js/app.js', 'js')
   .sass('assets/src/sass/app.scss', 'css')
   .options({
       processCssUrls: false,
       postCss: [
           require('tailwindcss'),
       ],
   })
   .version();

if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}
