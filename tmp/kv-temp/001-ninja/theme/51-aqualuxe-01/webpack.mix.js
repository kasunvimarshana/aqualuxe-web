const mix = require('laravel-mix');
const path = require('path');

// Set public path
mix.setPublicPath('assets/dist');

// Configure options
mix.options({
    processCssUrls: false,
    terser: {
        extractComments: false,
    }
});

// Process JavaScript files
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/dark-mode.js', 'js')
   .js('assets/src/js/woocommerce.js', 'js');

// Process SCSS files
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/dark-mode.scss', 'css')
   .sass('assets/src/scss/woocommerce.scss', 'css')
   .sass('assets/src/scss/editor-style.scss', 'css');

// Process Tailwind CSS
mix.postCss('assets/src/css/tailwind.css', 'css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Copy fonts and images
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// Configure BrowserSync for development
if (!mix.inProduction()) {
    mix.browserSync({
        proxy: 'http://localhost:8000', // Change this to your local development URL
        files: [
            'assets/dist/css/**/*.css',
            'assets/dist/js/**/*.js',
            '**/*.php'
        ],
        notify: false
    });
}