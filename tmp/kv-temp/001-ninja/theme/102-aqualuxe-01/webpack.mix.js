const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your AquaLuxe theme. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Set public path for compiled assets
mix.setPublicPath('assets/dist');

// Disable mix-manifest.json versioning in development
if (!mix.inProduction()) {
    mix.options({
        manifest: false
    });
}

// Compile JavaScript
mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/demo-importer.js', 'js');

// Compile Sass with Tailwind CSS
mix.sass('assets/src/scss/app.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sass('assets/src/scss/editor.scss', 'css')
   .options({
       processCssUrls: false,
       postCss: [
           require('tailwindcss'),
           require('autoprefixer'),
       ],
   });

// Copy static assets
mix.copyDirectory('assets/src/images', 'assets/dist/images');
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Module-specific assets
mix.js('assets/src/js/modules/dark-mode.js', 'js/modules')
   .js('assets/src/js/modules/multilingual.js', 'js/modules')
   .js('assets/src/js/modules/woocommerce.js', 'js/modules')
   .js('assets/src/js/modules/demo-importer.js', 'js/modules');

// Enable source maps in development
if (!mix.inProduction()) {
    mix.sourceMaps();
} else {
    // Versioning for production cache busting
    mix.version();
    
    // Additional production optimizations
    mix.minify('assets/dist/css/app.css')
       .minify('assets/dist/js/app.js');
}

// Browser sync for development (optional)
if (process.env.BROWSERSYNC_PROXY) {
    mix.browserSync({
        proxy: process.env.BROWSERSYNC_PROXY,
        files: [
            '**/*.php',
            'assets/dist/**/*'
        ],
        watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir']
    });
}

// Disable notifications
mix.disableNotifications();

// Extract vendor libraries for better caching
mix.extract(['jquery'], 'js/vendor.js');

// Configure webpack for better performance
const path = require('path');

mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('assets/src'),
            '~': path.resolve('node_modules')
        }
    },
    optimization: {
        splitChunks: {
            chunks: 'all',
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendor',
                    chunks: 'all',
                },
            },
        },
    },
});