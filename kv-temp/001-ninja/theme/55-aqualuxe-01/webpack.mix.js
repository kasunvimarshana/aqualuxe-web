const mix = require('laravel-mix');
const path = require('path');

// Set public path for assets
mix.setPublicPath('assets/dist');

// Configure options
mix.options({
    processCssUrls: false,
    terser: {
        extractComments: false,
        terserOptions: {
            compress: {
                drop_console: true,
            },
        },
    },
});

// Process JavaScript
mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js');

// Process CSS/SCSS
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sass('assets/src/scss/editor-style.scss', 'css')
   .sass('assets/src/scss/woocommerce.scss', 'css')
   .sass('assets/src/scss/dark-mode.scss', 'css');

// Process Tailwind CSS
mix.postCss('assets/src/scss/tailwind.css', 'css', [
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Version files in production
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
    ],
});

// Disable success notifications during development
mix.disableSuccessNotifications();