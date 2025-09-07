const mix = require('laravel-mix');

mix.setPublicPath('assets/dist')
   .js('assets/src/js/main.js', 'js')
   .sass('assets/src/sass/main.scss', 'css')
   .postCss('assets/src/css/custom.css', 'css', [
       require('tailwindcss'),
   ])
   .js('modules/ui_ux/js/ui-ux.js', 'js')
   .js('modules/dark_mode/js/dark-mode.js', 'js')
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
