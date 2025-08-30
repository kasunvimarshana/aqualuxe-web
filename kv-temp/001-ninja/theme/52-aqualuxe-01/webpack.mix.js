const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

// Set public path for assets
mix.setPublicPath('assets/dist');

// JavaScript
mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js');

// Sass compilation
mix.sass('assets/src/sass/style.scss', 'css')
   .sass('assets/src/sass/admin.scss', 'css')
   .sass('assets/src/sass/editor-style.scss', 'css')
   .sass('assets/src/sass/woocommerce.scss', 'css')
   .options({
      processCssUrls: false,
      postCss: [
         require('postcss-import'),
         tailwindcss('./tailwind.config.js'),
         require('autoprefixer'),
      ],
   });

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable versioning in production
if (mix.inProduction()) {
   mix.version();
}

// BrowserSync for local development
mix.browserSync({
   proxy: 'localhost',
   open: false,
   files: [
      'assets/dist/**/*',
      '**/*.php',
   ]
});

// Disable success notifications during watch
mix.disableSuccessNotifications();