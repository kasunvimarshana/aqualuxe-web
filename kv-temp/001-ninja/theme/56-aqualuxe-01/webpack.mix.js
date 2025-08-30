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

// Process JavaScript files
mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js');

// Process SASS files
mix.sass('assets/src/sass/main.scss', 'css')
   .sass('assets/src/sass/admin.scss', 'css')
   .sass('assets/src/sass/editor.scss', 'css')
   .sass('assets/src/sass/woocommerce.scss', 'css');

// Process Tailwind CSS
mix.postCss('assets/src/css/tailwind.css', 'css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');
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

// Disable OS notifications
mix.disableNotifications();