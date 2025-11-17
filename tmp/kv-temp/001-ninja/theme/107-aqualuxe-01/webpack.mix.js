const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Set public path for WordPress theme
mix.setPublicPath('assets/dist');

// Configure options
mix.options({
    processCssUrls: false,
    postCss: [tailwindcss('./tailwind.config.js')],
});

// JavaScript compilation
mix.js('assets/src/js/app.js', 'assets/dist/js')
   .js('assets/src/js/admin.js', 'assets/dist/js')
   .js('assets/src/js/customizer.js', 'assets/dist/js');

// SCSS compilation with Tailwind
mix.sass('assets/src/scss/app.scss', 'assets/dist/css')
   .sass('assets/src/scss/admin.scss', 'assets/dist/css')
   .sass('assets/src/scss/editor.scss', 'assets/dist/css')
   .sass('assets/src/scss/woocommerce.scss', 'assets/dist/css');

// Copy images and fonts
mix.copyDirectory('assets/src/images', 'assets/dist/images')
   .copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Generate versioned filenames for cache busting
if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps();
}

// Browser sync for development
mix.browserSync({
    proxy: 'localhost:8080',
    files: [
        'assets/dist/**/*',
        '**/*.php',
        '**/*.html'
    ],
    ignore: [
        'node_modules/**/*',
        'vendor/**/*'
    ]
});

// Webpack configuration
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('assets/src'),
            'images': path.resolve('assets/src/images'),
            'fonts': path.resolve('assets/src/fonts')
        }
    },
    stats: {
        children: true,
    }
});

// Custom webpack plugins
mix.extend('addLoader', new class {
    webpackRules() {
        return {
            test: /\.(woff|woff2|eot|ttf|svg)$/,
            loader: 'file-loader',
            options: {
                name: 'fonts/[name].[ext]',
                publicPath: '../'
            }
        };
    }
}());

// Extract vendor libraries for better caching
mix.extract([
    'alpinejs',
    'gsap',
    'swiper'
]);

// Disable mix-manifest in non-production for faster builds
if (!mix.inProduction()) {
    mix.options({
        manifest: false
    });
}