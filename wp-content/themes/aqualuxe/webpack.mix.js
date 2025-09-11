const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your AquaLuxe theme. By default, we are compiling the CSS and JS
 | files for the theme, as well as bundling up all the dependencies.
 |
 */

// Set public path for proper asset URLs
mix.setPublicPath('./assets/dist');

// JavaScript files
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/woocommerce.js', 'js')
   .js('assets/src/js/dark-mode.js', 'js')
   .js('assets/src/js/navigation.js', 'js')
   .js('assets/src/js/quick-view.js', 'js')
   .js('assets/src/js/wishlist.js', 'js')
   .js('assets/src/js/slider.js', 'js');

// CSS files with PostCSS processing
mix.postCss('assets/src/css/main.css', 'css', [
       require('postcss-import'),
       require('tailwindcss'),
       require('autoprefixer'),
   ])
   .postCss('assets/src/css/admin.css', 'css', [
       require('postcss-import'),
       require('tailwindcss'),
       require('autoprefixer'),
   ])
   .postCss('assets/src/css/woocommerce.css', 'css', [
       require('postcss-import'),
       require('tailwindcss'),
       require('autoprefixer'),
   ])
   .postCss('assets/src/css/editor-style.css', 'css', [
       require('postcss-import'),
       require('tailwindcss'),
       require('autoprefixer'),
   ]);

// Copy static assets
mix.copyDirectory('assets/src/images', 'assets/dist/images')
   .copyDirectory('assets/src/fonts', 'assets/dist/fonts')
   .copyDirectory('assets/src/icons', 'assets/dist/icons');

// Production optimizations
if (mix.inProduction()) {
    mix.version()
       .options({
           terser: {
               terserOptions: {
                   compress: {
                       drop_console: true,
                   },
               },
           },
           autoprefixer: {
               options: {
                   browsers: [
                       'last 6 versions',
                   ]
               }
           }
       });
} else {
    // Development settings
    mix.sourceMaps(true, 'source-map')
       .options({
           processCssUrls: false,
       });
}

// Extract vendor libraries to separate file
mix.extract(['alpinejs', 'aos', 'swiper']);

// Options
mix.options({
    processCssUrls: false, // Let Tailwind handle URL processing
    postCss: [
        require('autoprefixer'),
    ],
});

// Webpack configuration
mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve('assets/src'),
            'components': path.resolve('assets/src/js/components'),
            'utils': path.resolve('assets/src/js/utils'),
        }
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            }
        ]
    }
});

// Disable Mix's success notification
mix.disableSuccessNotifications();