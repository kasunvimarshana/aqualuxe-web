const mix = require('laravel-mix');
const path = require('path');
const tailwindcss = require('tailwindcss');

// Set public path
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

// Process module scripts
mix.js('assets/src/js/modules/dark-mode.js', 'js/modules')
    .js('assets/src/js/modules/multilingual.js', 'js/modules')
    .js('assets/src/js/modules/quick-view.js', 'js/modules')
    .js('assets/src/js/modules/wishlist.js', 'js/modules')
    .js('assets/src/js/modules/advanced-filter.js', 'js/modules');

// Process SASS/CSS
mix.sass('assets/src/sass/style.scss', 'css')
    .sass('assets/src/sass/admin.scss', 'css')
    .sass('assets/src/sass/editor.scss', 'css')
    .sass('assets/src/sass/woocommerce.scss', 'css')
    .options({
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

// BrowserSync for development
if (!mix.inProduction()) {
    mix.browserSync({
        proxy: 'localhost',
        open: false,
        files: [
            'assets/dist/**/*',
            '**/*.php',
        ],
    });
}