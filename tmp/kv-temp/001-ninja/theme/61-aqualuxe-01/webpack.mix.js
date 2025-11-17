const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const path = require('path');

// Set public path for assets
mix.setPublicPath('assets/dist');

// Configure source and destination paths
const srcPath = 'assets/src';
const distPath = 'assets/dist';

// Process JavaScript files
mix.js(`${srcPath}/js/main.js`, `${distPath}/js`)
   .js(`${srcPath}/js/admin.js`, `${distPath}/js`)
   .js(`${srcPath}/js/customizer.js`, `${distPath}/js`);

// Process module JavaScript files
mix.js(`${srcPath}/js/modules/dark-mode.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/multilingual.js`, `${distPath}/js/modules`)
   .js(`${srcPath}/js/modules/woocommerce.js`, `${distPath}/js/modules`);

// Process SCSS files
mix.sass(`${srcPath}/scss/main.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/admin.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/editor-style.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/woocommerce.scss`, `${distPath}/css`)
   .options({
     processCssUrls: false,
     postCss: [
       tailwindcss('./tailwind.config.js'),
       require('autoprefixer'),
     ],
   });

// Process module SCSS files
mix.sass(`${srcPath}/scss/modules/dark-mode.scss`, `${distPath}/css/modules`)
   .sass(`${srcPath}/scss/modules/multilingual.scss`, `${distPath}/css/modules`)
   .sass(`${srcPath}/scss/modules/woocommerce.scss`, `${distPath}/css/modules`);

// Copy images and fonts
mix.copyDirectory(`${srcPath}/images`, `${distPath}/images`);
mix.copyDirectory(`${srcPath}/fonts`, `${distPath}/fonts`);

// Enable source maps for development
if (!mix.inProduction()) {
  mix.sourceMaps();
}

// Version files in production
if (mix.inProduction()) {
  mix.version();
}

// Configure Webpack
mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve('assets/src'),
    },
  },
  optimization: {
    splitChunks: {
      chunks: 'all',
    },
  },
});

// Browser sync for local development
if (!mix.inProduction()) {
  mix.browserSync({
    proxy: 'localhost',
    files: [
      'assets/dist/**/*',
      '**/*.php',
    ],
    notify: false,
  });
}