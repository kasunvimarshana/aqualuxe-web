const mix = require('laravel-mix');

mix.setPublicPath('assets/dist');
mix.setResourceRoot('/');

mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .sass('assets/src/scss/app.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
  .options({ processCssUrls: false, postCss: [require('postcss-import'), require('tailwindcss'), require('autoprefixer')] })
  .version();

if (!mix.inProduction()) {
  mix.sourceMaps();
}
