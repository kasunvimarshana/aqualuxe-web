const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining Webpack build steps
 | for your AquaLuxe theme. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Set public path
mix.setPublicPath('assets/dist');

// Set source maps for development
mix.sourceMaps(false, 'eval-source-map');

// Configure options
mix.options({
    processCssUrls: false,
    postCss: [
        require('tailwindcss'),
        require('autoprefixer')
    ]
});

// JavaScript compilation
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/woocommerce.js', 'js')
   .js('assets/src/js/progressive.js', 'js');

// SCSS compilation
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sass('assets/src/scss/customizer.scss', 'css')
   .sass('assets/src/scss/editor.scss', 'css')
   .sass('assets/src/scss/dark-mode.scss', 'css')
   .sass('assets/src/scss/rtl.scss', 'css')
   .sass('assets/src/scss/print.scss', 'css')
   .sass('assets/src/scss/woocommerce.scss', 'css');

// Copy and optimize images
mix.copy('assets/src/images', 'assets/dist/images');

// Copy and optimize fonts
mix.copy('assets/src/fonts', 'assets/dist/fonts');

// Copy and process icons
mix.copy('assets/src/icons', 'assets/dist/icons');

// Webpack configuration
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('assets/src'),
            '@js': path.resolve('assets/src/js'),
            '@scss': path.resolve('assets/src/scss'),
            '@images': path.resolve('assets/src/images'),
            '@fonts': path.resolve('assets/src/fonts'),
            '@icons': path.resolve('assets/src/icons')
        }
    },
    module: {
        rules: [
            // Handle SVG files
            {
                test: /\.svg$/,
                use: [
                    {
                        loader: 'url-loader',
                        options: {
                            limit: 8192,
                            fallback: 'file-loader',
                            name: 'icons/[name].[hash:8].[ext]'
                        }
                    }
                ]
            },
            // Handle font files
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: 'fonts/[name].[hash:8].[ext]'
                        }
                    }
                ]
            },
            // Handle image files
            {
                test: /\.(png|jpe?g|gif|webp)$/i,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: 'images/[name].[hash:8].[ext]'
                        }
                    }
                ]
            }
        ]
    },
    externals: {
        jquery: 'jQuery'
    }
});

// Enable versioning for production
if (mix.inProduction()) {
    mix.version();
    
    // Additional production optimizations
    mix.options({
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true,
                    drop_debugger: true
                }
            }
        }
    });
}

// Disable OS notifications
mix.disableNotifications();

// BrowserSync for development
if (!mix.inProduction()) {
    mix.browserSync({
        proxy: 'aqualuxe.local', // Change this to your local development URL
        files: [
            '**/*.php',
            'assets/dist/**/*'
        ],
        open: false,
        notify: false
    });
}
