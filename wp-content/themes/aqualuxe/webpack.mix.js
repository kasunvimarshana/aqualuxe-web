const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | AquaLuxe Theme Asset Pipeline
 |--------------------------------------------------------------------------
 |
 | Laravel Mix provides a clean, fluent API for defining Webpack build steps
 | for the AquaLuxe WordPress theme. We use Mix to compile Sass, bundle
 | JavaScript, optimize images, and manage asset versioning.
 |
 */

// Set public path to the theme's assets/dist directory
mix.setPublicPath('assets/dist');

// Configure source maps for development
if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'source-map'
    });
    mix.sourceMaps();
}

// Configure Tailwind CSS processing
mix.postCss('assets/src/css/app.css', 'assets/dist/css', [
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Compile Sass files
mix.sass('assets/src/scss/app.scss', 'assets/dist/css')
   .sass('assets/src/scss/admin.scss', 'assets/dist/css')
   .sass('assets/src/scss/editor.scss', 'assets/dist/css')
   .sass('assets/src/scss/woocommerce.scss', 'assets/dist/css');

// Compile JavaScript files
mix.js('assets/src/js/app.js', 'assets/dist/js')
   .js('assets/src/js/admin.js', 'assets/dist/js')
   .js('assets/src/js/modules/dark-mode.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/woocommerce.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/demo-importer.js', 'assets/dist/js/modules');

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Configure webpack for modern builds
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('assets/src'),
            '@js': path.resolve('assets/src/js'),
            '@scss': path.resolve('assets/src/scss'),
            '@images': path.resolve('assets/src/images'),
        }
    },
    module: {
        rules: [
            {
                test: /\.(png|jpg|gif|svg)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[hash].[ext]',
                            outputPath: 'images/',
                        }
                    }
                ]
            }
        ]
    }
});

// Enable versioning in production for cache busting
if (mix.inProduction()) {
    mix.version();
}

// Configure options
mix.options({
    processCssUrls: false,
    postCss: [
        require('tailwindcss'),
        require('autoprefixer'),
    ]
});

// Enable hot module replacement for development
if (process.env.NODE_ENV === 'development') {
    mix.options({
        hmrOptions: {
            host: 'localhost',
            port: 8080
        }
    });
}

// Disable OS notifications
mix.disableNotifications();