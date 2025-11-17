const mix = require('laravel-mix');
const path = require('path');

/*
|--------------------------------------------------------------------------
| Mix Asset Management
|--------------------------------------------------------------------------
| AquaLuxe Theme - Production-ready asset compilation
| Features: SCSS/CSS processing, JS bundling, image optimization,
| code splitting, tree-shaking, and cache busting
|--------------------------------------------------------------------------
*/

// Configuration
const config = {
    publicPath: 'assets/dist',
    sourcePath: 'assets/src',
    proxyUrl: 'http://localhost:8000', // Update with your local dev URL
    watchFiles: [
        '**/*.php',
        'templates/**/*.php',
        'template-parts/**/*.php',
        'woocommerce/**/*.php'
    ]
};

// Set public path
mix.setPublicPath(config.publicPath);

// Core application assets
mix.js(`${config.sourcePath}/js/app.js`, 'js/app.js')
   .js(`${config.sourcePath}/js/main.js`, 'js/main.js')
   .js(`${config.sourcePath}/js/admin.js`, 'js/admin.js')
   .js(`${config.sourcePath}/js/customizer.js`, 'js/customizer.js');

// WooCommerce specific assets
mix.js(`${config.sourcePath}/js/woocommerce.js`, 'js/woocommerce.js')
   .js(`${config.sourcePath}/js/shop.js`, 'js/shop.js');

// Module-specific JavaScript
mix.js(`${config.sourcePath}/js/modules/dark-mode.js`, 'js/dark-mode.js')
   .js(`${config.sourcePath}/js/modules/multilingual.js`, 'js/multilingual.js')
   .js(`${config.sourcePath}/js/modules/multicurrency.js`, 'js/multicurrency.js');

// Styles compilation
mix.sass(`${config.sourcePath}/scss/app.scss`, 'css/app.css')
   .sass(`${config.sourcePath}/scss/main.scss`, 'css/main.css')
   .sass(`${config.sourcePath}/scss/admin.scss`, 'css/admin.css')
   .sass(`${config.sourcePath}/scss/customizer.scss`, 'css/customizer.css')
   .sass(`${config.sourcePath}/scss/editor.scss`, 'css/editor.css')
   .sass(`${config.sourcePath}/scss/woocommerce.scss`, 'css/woocommerce.css');

// Copy and optimize images
mix.copyDirectory(`${config.sourcePath}/images`, `${config.publicPath}/images`);

// Copy fonts
mix.copyDirectory(`${config.sourcePath}/fonts`, `${config.publicPath}/fonts`);

// Options and optimization
mix.options({
    processCssUrls: false,
    postCss: [
        require('postcss-import'),
        require('tailwindcss')('./tailwind.config.js'),
        require('autoprefixer'),
        ...(process.env.NODE_ENV === 'production'
            ? [require('cssnano')({ preset: 'default' })]
            : [])
    ]
});

// Webpack configuration
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, `${config.sourcePath}/js`),
            '@scss': path.resolve(__dirname, `${config.sourcePath}/scss`),
            '@images': path.resolve(__dirname, `${config.sourcePath}/images`)
        }
    },
    optimization: {
        splitChunks: {
            chunks: 'all',
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendor',
                    chunks: 'all'
                }
            }
        }
    },
    output: {
        chunkFilename: 'js/[name].[contenthash].js'
    }
});

// Production optimizations
if (mix.inProduction()) {
    mix.version();
    mix.options({
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true,
                }
            }
        }
    });
} else {
    mix.sourceMaps(true, 'source-map');
    
    // Development server configuration
    if (config.proxyUrl) {
        mix.browserSync({
            proxy: config.proxyUrl,
            files: config.watchFiles,
            open: false,
            notify: false
        });
    }
}
