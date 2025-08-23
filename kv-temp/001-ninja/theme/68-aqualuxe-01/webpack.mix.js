const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// Set public path
mix.setPublicPath('assets/dist');

// Process JavaScript files
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/editor.js', 'js')
   .js('assets/src/js/customizer.js', 'js');

// Process WooCommerce JavaScript if needed
if (require('fs').existsSync('assets/src/js/woocommerce.js')) {
    mix.js('assets/src/js/woocommerce.js', 'js');
}

// Process SASS files
mix.sass('assets/src/sass/main.scss', 'css')
   .sass('assets/src/sass/admin.scss', 'css')
   .sass('assets/src/sass/editor.scss', 'css');

// Process WooCommerce SASS if needed
if (require('fs').existsSync('assets/src/sass/woocommerce.scss')) {
    mix.sass('assets/src/sass/woocommerce.scss', 'css');
}

// Add PostCSS plugins
mix.options({
    processCssUrls: false,
    postCss: [
        tailwindcss('./tailwind.config.js'),
        require('autoprefixer')
    ]
});

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Copy images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Enable versioning in production
if (mix.inProduction()) {
    mix.version();
}

// Enable BrowserSync for development
if (!mix.inProduction()) {
    mix.browserSync({
        proxy: 'localhost',
        files: [
            'assets/dist/**/*',
            '**/*.php'
        ]
    });
}