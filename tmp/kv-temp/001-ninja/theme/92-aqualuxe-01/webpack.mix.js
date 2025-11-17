const mix = require('laravel-mix');

mix.setPublicPath('assets/dist');

mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/mega-menu.js', 'js')
   .js('assets/src/js/slide-in-panel.js', 'js')
   .js('assets/src/js/advanced-search.js', 'js')
   .sass('assets/src/sass/app.scss', 'css')
   .postCss('assets/src/css/app.css', 'css', [
       require('tailwindcss'),
   ])
   .postCss('assets/src/css/woocommerce.css', 'css', [
      require('tailwindcss'),
   ])
   .postCss('assets/src/css/mega-menu.css', 'css', [
      require('tailwindcss'),
   ])
   .postCss('assets/src/css/slide-in-panel.css', 'css', [
      require('tailwindcss'),
   ])
   .postCss('assets/src/css/advanced-search.css', 'css', [
      require('tailwindcss'),
   ])
   .postCss('assets/src/css/social-media.css', 'css', [
      require('tailwindcss'),
   ])
   .options({
       processCssUrls: false,
   })
   .version();

mix.browserSync({
    proxy: 'localhost:8000', // Your WordPress local dev URL
    files: [
        '**/*.php',
        'assets/dist/js/**/*.js',
        'assets/dist/css/**/*.css'
    ],
    injectChanges: true,
    open: false
});
