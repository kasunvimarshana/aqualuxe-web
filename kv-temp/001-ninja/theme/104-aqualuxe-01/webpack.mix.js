const mix = require('laravel-mix');
const path = require('path');

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

// Set public path for assets
mix.setPublicPath('assets/dist');

// Configure source and destination paths
const srcPath = 'assets/src';
const distPath = 'assets/dist';

// JavaScript compilation - only include actively used modules
mix.js(`${srcPath}/js/app.js`, `${distPath}/js`)
   .js(`${srcPath}/js/admin.js`, `${distPath}/js`)
   .js(`${srcPath}/js/customizer.js`, `${distPath}/js`)
   .js(`${srcPath}/js/modules/dark-mode.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/woocommerce.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/demo-importer.js`, `${distPath}/js/modules`);

// SCSS/CSS compilation with Tailwind
mix.sass(`${srcPath}/scss/app.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/admin.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/customizer.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/woocommerce.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/fontawesome.scss`, `${distPath}/css`)
   .options({
     postCss: [require('tailwindcss'), require('autoprefixer')]
   });

// Copy and optimize images and fonts
mix.copyDirectory(`${srcPath}/images`, `${distPath}/images`)
   .copyDirectory(`${srcPath}/fonts`, `${distPath}/fonts`)
   .copy('node_modules/@fortawesome/fontawesome-free/webfonts', `${distPath}/webfonts`);

// Image optimization
mix.options({
  processCssUrls: true,
  imgLoaderOptions: {
    enabled: mix.inProduction(),
    gifsicle: { optimizationLevel: 7 },
    mozjpeg: { quality: 85 },
    pngquant: { quality: [0.6, 0.8] },
    svgo: {
      plugins: [
        { removeViewBox: false },
        { removeDimensions: true }
      ]
    }
  }
});

// File versioning for cache busting
if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps();
}

// Webpack configuration for optimized builds
mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, `${srcPath}/js`),
      '@scss': path.resolve(__dirname, `${srcPath}/scss`),
      '@images': path.resolve(__dirname, `${srcPath}/images`)
    }
  }
});

// Extract vendor libraries
mix.extract(['alpinejs', 'swiper']);

// Disable OS notifications
mix.disableNotifications();