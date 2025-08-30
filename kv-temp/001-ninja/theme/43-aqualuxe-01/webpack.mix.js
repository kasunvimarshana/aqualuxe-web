const mix = require('laravel-mix');
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

// Process JavaScript files
mix.js('assets/src/js/main.js', 'js')
    .js('assets/src/js/admin.js', 'js')
    .js('assets/src/js/editor.js', 'js')
    .js('assets/src/js/gutenberg.js', 'js')
    .js('assets/src/js/woocommerce.js', 'js');

// Process SCSS files
mix.sass('assets/src/scss/main.scss', 'css')
    .sass('assets/src/scss/admin.scss', 'css')
    .sass('assets/src/scss/editor.scss', 'css')
    .sass('assets/src/scss/gutenberg.scss', 'css')
    .sass('assets/src/scss/woocommerce.scss', 'css')
    .sass('assets/src/scss/login.scss', 'css')
    .options({
        postCss: [
            tailwindcss('./tailwind.config.js'),
            require('autoprefixer'),
        ],
    });

// Generate source maps in development mode
if (!mix.inProduction()) {
    mix.sourceMaps();
}

// Version files in production mode
if (mix.inProduction()) {
    mix.version();
}

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Copy images and optimize them
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Browser sync for local development
if (!mix.inProduction()) {
    mix.browserSync({
        proxy: 'localhost',
        files: [
            'assets/dist/**/*',
            '**/*.php',
        ],
    });
}