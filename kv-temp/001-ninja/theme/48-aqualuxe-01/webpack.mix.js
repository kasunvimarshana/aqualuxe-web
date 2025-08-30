/**
 * Laravel Mix configuration file for AquaLuxe theme
 *
 * @link https://laravel-mix.com/
 */

const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

// Set public path
mix.setPublicPath('assets/dist');

// Disable success notifications
mix.disableSuccessNotifications();

// Process JavaScript
mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .sourceMaps();

// Process CSS
mix.postCss('assets/src/css/main.css', 'css', [
    require('postcss-import'),
    tailwindcss('./tailwind.config.js'),
    require('autoprefixer'),
])
.postCss('assets/src/css/woocommerce.css', 'css', [
    require('postcss-import'),
    tailwindcss('./tailwind.config.js'),
    require('autoprefixer'),
])
.sourceMaps();

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Copy images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

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

// BrowserSync for development
if (!mix.inProduction()) {
    mix.browserSync({
        proxy: 'localhost',
        files: [
            'assets/dist/**/*',
            '**/*.php',
        ],
        notify: false,
    });
}