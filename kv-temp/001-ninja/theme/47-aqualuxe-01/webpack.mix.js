const mix = require('laravel-mix');
const path = require('path');
const tailwindcss = require('tailwindcss');

// Set public path for assets
mix.setPublicPath('assets/dist');

// Process JavaScript files
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/woocommerce.js', 'js');

// Process SCSS files
mix.sass('assets/src/css/main.scss', 'css')
   .sass('assets/src/css/woocommerce.scss', 'css')
   .sass('assets/src/css/editor-style.scss', 'css')
   .options({
      processCssUrls: false,
      postCss: [
         require('postcss-import'),
         tailwindcss('./tailwind.config.js'),
         require('autoprefixer')
      ]
   });

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable versioning in production
if (mix.inProduction()) {
   mix.version();
}

// Configure BrowserSync for development
mix.browserSync({
   proxy: 'localhost', // Change this to your local development URL
   files: [
      'assets/dist/**/*',
      '**/*.php'
   ],
   open: false
});

// Disable OS notifications
mix.disableNotifications();