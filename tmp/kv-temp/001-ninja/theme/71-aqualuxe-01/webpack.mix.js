const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | AquaLuxe Theme Asset Build Configuration
 |--------------------------------------------------------------------------
 |
 | Laravel Mix provides a clean, fluent API for defining basic webpack
 | build steps for your theme assets. Mix supports several common CSS
 | and JavaScript pre-processors out of the box.
 |
 */

// Set public path for assets
mix.setPublicPath('assets/dist');

// Configure source and destination paths
const srcPath = 'assets/src';
const distPath = 'assets/dist';

// Configure webpack
mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.jsx', '.ts', '.tsx', '.vue'],
        alias: {
            '@': path.resolve(__dirname, srcPath),
            '@js': path.resolve(__dirname, `${srcPath}/js`),
            '@scss': path.resolve(__dirname, `${srcPath}/sass`),
            '@images': path.resolve(__dirname, `${srcPath}/images`),
            '@fonts': path.resolve(__dirname, `${srcPath}/fonts`),
        }
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                options: {
                    presets: [
                        ['@babel/preset-env', { targets: { browsers: ['> 1%', 'last 2 versions'] } }],
                        '@babel/preset-typescript'
                    ]
                }
            }
        ]
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
                common: {
                    name: 'common',
                    minChunks: 2,
                    chunks: 'all',
                    enforce: true,
                }
            }
        }
    }
});

// Process JavaScript files
mix.js(`${srcPath}/js/app.js`, `${distPath}/js`)
   .js(`${srcPath}/js/admin.js`, `${distPath}/js`)
   .js(`${srcPath}/js/customizer.js`, `${distPath}/js`)
   .js(`${srcPath}/js/woocommerce.js`, `${distPath}/js`)
   .js(`${srcPath}/js/modules/dark-mode.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/multilingual.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/wishlist.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/quick-view.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/search.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/cart.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/checkout.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/gallery.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/forms.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/animations.js`, `${distPath}/js/modules`);

// Process SCSS files
mix.sass(`${srcPath}/sass/app.scss`, `${distPath}/css`)
   .sass(`${srcPath}/sass/admin.scss`, `${distPath}/css`)
   .sass(`${srcPath}/sass/customizer.scss`, `${distPath}/css`)
   .sass(`${srcPath}/sass/woocommerce.scss`, `${distPath}/css`)
   .sass(`${srcPath}/sass/modules/dark-mode.scss`, `${distPath}/css/modules`)
   .sass(`${srcPath}/sass/modules/components.scss`, `${distPath}/css/modules`)
   .sass(`${srcPath}/sass/modules/utilities.scss`, `${distPath}/css/modules`);

// Process images and optimize
mix.copyDirectory(`${srcPath}/images`, `${distPath}/images`);

// Process fonts
mix.copyDirectory(`${srcPath}/fonts`, `${distPath}/fonts`);

// Configure PostCSS options
mix.options({
    postCss: [
        require('tailwindcss'),
        require('autoprefixer'),
    ],
    processCssUrls: false,
    clearConsole: true
});

// Generate versioned files for cache busting
if (mix.inProduction()) {
    mix.version();
    
    // Additional production optimizations
    mix.options({
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true,
                    drop_debugger: true
                },
                mangle: true,
                output: {
                    comments: false
                }
            }
        }
    });
} else {
    // Development settings
    mix.sourceMaps(true, 'eval-source-map');
    mix.options({
        hmrOptions: {
            host: 'localhost',
            port: 8080
        }
    });
}

// Configure BrowserSync for live reloading (optional)
if (process.env.MIX_BROWSERSYNC === 'true') {
    mix.browserSync({
        proxy: process.env.MIX_BROWSERSYNC_PROXY || 'localhost',
        files: [
            '**/*.php',
            `${distPath}/**/*`
        ],
        watchOptions: {
            usePolling: true,
            interval: 1000
        }
    });
}

// Extract vendor libraries
mix.extract(['jquery', 'alpinejs', 'gsap', 'swiper', 'axios']);

// Disable mix-manifest in development for faster builds
if (!mix.inProduction()) {
    mix.options({ manifest: false });
}
