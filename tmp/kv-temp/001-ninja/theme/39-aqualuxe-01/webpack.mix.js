const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('assets/dist')
   .setResourceRoot('../')
   
   // Styles
   .sass('assets/src/scss/main.scss', 'assets/dist/css')
   .sass('assets/src/scss/admin.scss', 'assets/dist/css')
   .sass('assets/src/scss/woocommerce.scss', 'assets/dist/css')
   
   // Scripts
   .js('assets/src/js/main.js', 'assets/dist/js')
   .js('assets/src/js/admin.js', 'assets/dist/js')
   .js('assets/src/js/woocommerce.js', 'assets/dist/js')
   
   // Copy images and fonts
   .copyDirectory('assets/src/images', 'assets/dist/images')
   .copyDirectory('assets/src/fonts', 'assets/dist/fonts')
   
   // Options
   .options({
     processCssUrls: false,
     postCss: [
       require('tailwindcss'),
       require('autoprefixer'),
     ]
   })
   
   // Versioning
   .version()
   
   // Source maps for development
   .sourceMaps(!mix.inProduction());

// BrowserSync for development
if (!mix.inProduction()) {
  mix.browserSync({
    proxy: 'aqualuxe.local', // Change to your local dev URL
    files: [
      '**/*.php',
      'assets/dist/**/*'
    ]
  });
}