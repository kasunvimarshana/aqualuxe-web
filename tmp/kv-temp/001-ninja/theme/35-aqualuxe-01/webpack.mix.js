const mix = require('laravel-mix');
const path = require('path');

// Set public path
mix.setPublicPath('./assets/dist');

// BrowserSync configuration
mix.browserSync({
    proxy: 'localhost', // Change this to your local development URL
    files: [
        './assets/dist/**/*',
        './**/*.php'
    ],
    open: false
});

// Process JavaScript
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/woocommerce.js', 'js')
   .sourceMaps();

// Process SASS
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/editor-style.scss', 'css')
   .sass('assets/src/scss/woocommerce.scss', 'css')
   .options({
       processCssUrls: false,
       postCss: [
           require('postcss-import'),
           require('tailwindcss'),
           require('autoprefixer')
       ]
   })
   .sourceMaps();

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// Disable OS notifications
mix.disableNotifications();