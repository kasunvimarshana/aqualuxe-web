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

// Legacy module support (existing files)
mix.js('modules/ui_ux/js/ui-ux.js', 'js/ui-ux.js')
   .js('modules/dark_mode/js/dark-mode.js', 'js/dark-mode.js');

// Styles compilation
mix.sass(`${config.sourcePath}/sass/main-simple.scss`, 'css/main.css')
   .sass(`${config.sourcePath}/sass/app.scss`, 'css/app.css')
   .sass(`${config.sourcePath}/sass/admin.scss`, 'css/admin.css')
   .sass(`${config.sourcePath}/sass/customizer.scss`, 'css/customizer.css')
   .sass(`${config.sourcePath}/sass/editor.scss`, 'css/editor.css')
   .sass(`${config.sourcePath}/sass/woocommerce.scss`, 'css/woocommerce.css');

// PostCSS compilation
mix.postCss(`${config.sourcePath}/css/custom.css`, 'css/custom.css', [
    require('tailwindcss'),
    require('autoprefixer')
])
.postCss(`${config.sourcePath}/css/components.css`, 'css/components.css', [
    require('autoprefixer')
]);

// Copy and optimize images
mix.copyDirectory(`${config.sourcePath}/images`, `${config.publicPath}/images`);

// Copy fonts
mix.copyDirectory(`${config.sourcePath}/fonts`, `${config.publicPath}/fonts`);

// Options and optimization
mix.options({
    processCssUrls: false,
    postCss: [
        require('tailwindcss'),
        require('autoprefixer')
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
    }
});

// Production optimizations
if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}
