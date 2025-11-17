const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | AquaLuxe Theme Asset Configuration
 |--------------------------------------------------------------------------
 |
 | Laravel Mix configuration for the AquaLuxe WordPress theme
 | Handles compilation of CSS, JavaScript, and other assets
 |
 */

// Configure Mix
mix.setPublicPath('assets/dist')
   .setResourceRoot('../')
   .options({
       processCssUrls: false,
       postCss: [
           require('tailwindcss'),
           require('autoprefixer'),
       ],
   });

// JavaScript compilation
mix.js('assets/src/js/main.js', 'assets/dist/js')
   .js('assets/src/js/admin.js', 'assets/dist/js')
   .js('assets/src/js/customizer.js', 'assets/dist/js');

// WooCommerce specific JavaScript
if (mix.inProduction()) {
    mix.js('assets/src/js/woocommerce.js', 'assets/dist/js');
} else {
    mix.js('assets/src/js/woocommerce.js', 'assets/dist/js');
}

// CSS compilation
mix.sass('assets/src/css/main.scss', 'assets/dist/css')
   .sass('assets/src/css/admin.scss', 'assets/dist/css')
   .sass('assets/src/css/woocommerce.scss', 'assets/dist/css')
   .sass('assets/src/css/editor.scss', 'assets/dist/css');

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Extract vendor libraries to separate file
mix.extract(['jquery', 'lodash', 'gsap']);

// Enable versioning in production
if (mix.inProduction()) {
    mix.version();
    
    // Additional production optimizations
    mix.options({
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true,
                },
            },
        },
    });
}

// Development tools
if (!mix.inProduction()) {
    mix.sourceMaps();
    
    // BrowserSync for live reloading
    mix.browserSync({
        proxy: 'localhost:8080', // Adjust for your local development URL
        files: [
            '**/*.php',
            'assets/dist/**/*',
        ],
        injectChanges: true,
    });
}

// Configure webpack for better module resolution
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('assets/src'),
            '@css': path.resolve('assets/src/css'),
            '@js': path.resolve('assets/src/js'),
            '@images': path.resolve('assets/src/images'),
        },
    },
    module: {
        rules: [
            {
                test: /\.(png|jpe?g|gif|svg)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: 'images/[name].[hash:8].[ext]',
                            publicPath: '../',
                        },
                    },
                ],
            },
            {
                test: /\.(woff2?|eot|ttf|otf)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: 'fonts/[name].[hash:8].[ext]',
                            publicPath: '../',
                        },
                    },
                ],
            },
        ],
    },
});

// Bundle analyzer for production builds
if (process.env.ANALYZE) {
    const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
    
    mix.webpackConfig({
        plugins: [
            new BundleAnalyzerPlugin({
                analyzerMode: 'static',
                openAnalyzer: false,
            }),
        ],
    });
}