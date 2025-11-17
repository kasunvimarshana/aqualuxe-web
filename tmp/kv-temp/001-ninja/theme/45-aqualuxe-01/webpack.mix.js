const mix = require('laravel-mix');
const path = require('path');

// Set public path
mix.setPublicPath('assets/dist');

// Configure options
mix.options({
    processCssUrls: false,
    postCss: [
        require('postcss-import'),
        require('tailwindcss'),
        require('postcss-nested'),
        require('autoprefixer'),
    ],
});

// Compile CSS
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sass('assets/src/scss/editor-style.scss', 'css');

// Compile JavaScript
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js');

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable source maps in development
if (!mix.inProduction()) {
    mix.sourceMaps();
}

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// Configure BrowserSync
mix.browserSync({
    proxy: 'localhost',
    open: false,
    files: [
        'assets/dist/**/*',
        '**/*.php',
    ],
});