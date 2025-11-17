const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

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

// Set public path
mix.setPublicPath('assets/dist');

// Configure webpack
mix.webpackConfig({
  stats: {
    children: true,
  },
});

// Options for optimization
mix.options({
  processCssUrls: false,
  postCss: [tailwindcss('./tailwind.config.js')],
});

// JavaScript files
mix.js('assets/src/js/main.js', 'assets/dist/js')
   .js('assets/src/js/admin.js', 'assets/dist/js')
   .js('assets/src/js/customizer.js', 'assets/dist/js');

// Sass files
mix.sass('assets/src/scss/main.scss', 'assets/dist/css')
   .sass('assets/src/scss/admin.scss', 'assets/dist/css')
   .sass('assets/src/scss/customizer.scss', 'assets/dist/css');

// Copy images and fonts
mix.copyDirectory('assets/src/images', 'assets/dist/images')
   .copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable versioning for cache busting in production
if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps();
}

// Enable BrowserSync for local development
mix.browserSync({
  proxy: 'localhost:8080', // Adjust to your local development URL
  files: [
    '**/*.php',
    'assets/dist/**/*',
    'templates/**/*.php',
    'woocommerce/**/*.php'
  ]
});

// Auto-reload on file changes
mix.disableNotifications();
