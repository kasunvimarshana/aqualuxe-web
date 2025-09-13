const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your AquaLuxe WordPress theme. This configuration follows best practices
 | for performance, security, and maintainability.
 |
 */

// Set public path for compiled assets
mix.setPublicPath('assets/dist');

// Main application assets
mix.js('assets/src/js/app.js', 'assets/dist/js')
   .sass('assets/src/scss/app.scss', 'assets/dist/css')
   .options({
       processCssUrls: false,
       postCss: [
           tailwindcss('./tailwind.config.js'),
           require('autoprefixer'),
       ],
   });

// Admin-specific assets
mix.js('assets/src/js/admin.js', 'assets/dist/js')
   .sass('assets/src/scss/admin.scss', 'assets/dist/css');

// Customizer assets
mix.js('assets/src/js/customizer.js', 'assets/dist/js')
   .js('assets/src/js/customizer-preview.js', 'assets/dist/js');

// Module JavaScript files (modular loading)
mix.js('assets/src/js/modules/dark-mode.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/multilingual.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/services.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/bookings.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/events.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/animations.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/navigation.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/utils.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/wholesale.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/franchise.js', 'assets/dist/js/modules');

// Feature-specific scripts
mix.js('assets/src/js/woocommerce.js', 'assets/dist/js')
   .js('assets/src/js/contact.js', 'assets/dist/js')
   .js('assets/src/js/search.js', 'assets/dist/js')
   .js('assets/src/js/product-gallery.js', 'assets/dist/js');

// Admin tools
mix.js('assets/src/js/admin/demo-importer.js', 'assets/dist/js/admin')
   .js('assets/src/js/admin/event-admin.js', 'assets/dist/js/admin')
   .js('assets/src/js/service-worker.js', 'assets/dist/js');

// Additional CSS files
mix.sass('assets/src/scss/editor.scss', 'assets/dist/css')
   .sass('assets/src/scss/login.scss', 'assets/dist/css')
   .sass('assets/src/scss/components/_language-switcher.scss', 'assets/dist/css/components/language-switcher.css');

// Copy static assets (only if directories exist)
const fs = require('fs');
if (fs.existsSync('assets/src/images')) {
    mix.copyDirectory('assets/src/images', 'assets/dist/images');
}
if (fs.existsSync('assets/src/fonts')) {
    mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');
}

// Configure webpack settings
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('assets/src/js'),
            '@css': path.resolve('assets/src/scss'),
            '@images': path.resolve('assets/src/images'),
            '@fonts': path.resolve('assets/src/fonts')
        }
    },
    module: {
        rules: [
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                type: 'asset/resource',
                generator: {
                    filename: 'fonts/[name][ext]'
                }
            },
            {
                test: /\.(png|jpe?g|gif|svg)$/,
                type: 'asset/resource',
                generator: {
                    filename: 'images/[name][ext]'
                }
            }
        ]
    }
});

// Production optimizations
if (mix.inProduction()) {
    mix.version(); // Enable versioning for cache busting
    
    // Additional production optimizations
    mix.options({
        terser: {
            extractComments: false,
            terserOptions: {
                compress: {
                    drop_console: true,
                    drop_debugger: true,
                    pure_funcs: ['console.log', 'console.info', 'console.debug']
                },
                mangle: true,
                format: {
                    comments: false
                }
            }
        }
    });
    
    // Tree shaking optimization
    mix.webpackConfig({
        optimization: {
            providedExports: true,
            usedExports: true,
            sideEffects: false
        }
    });
}

// Development enhancements
if (!mix.inProduction()) {
    mix.sourceMaps(true, 'source-map');
    
    // BrowserSync for local development
    mix.browserSync({
        proxy: false,
        server: false,
        files: [
            '**/*.php',
            'assets/dist/**/*.js',
            'assets/dist/**/*.css'
        ],
        watchOptions: {
            usePolling: true,
            interval: 100,
        }
    });
}

// Disable mix notifications
mix.disableNotifications();

// Configure webpack to reduce warnings
mix.webpackConfig({
    stats: {
        children: false,
        warnings: false,
        entrypoints: false
    }
});