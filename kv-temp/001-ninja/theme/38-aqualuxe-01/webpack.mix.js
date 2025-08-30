const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// Source and destination paths
const srcPath = 'assets/src';
const distPath = 'assets/dist';

// Check if the dist directory exists, if not create it
if (!fs.existsSync(path.resolve(__dirname, distPath))) {
  fs.mkdirSync(path.resolve(__dirname, distPath), { recursive: true });
}

// Set public path
mix.setPublicPath(distPath);

// Process CSS with Tailwind
mix.postCss(`${srcPath}/css/tailwind.css`, `${distPath}/css/app.css`, [
  require('postcss-import'),
  require('tailwindcss'),
  require('postcss-nested'),
  require('autoprefixer'),
]);

// Process SCSS files
mix.sass(`${srcPath}/scss/overrides.scss`, `${distPath}/css/overrides.css`);

// Process WooCommerce styles if needed
if (fs.existsSync(path.resolve(__dirname, `${srcPath}/scss/woocommerce.scss`))) {
  mix.sass(`${srcPath}/scss/woocommerce.scss`, `${distPath}/css/woocommerce.css`);
}

// Process admin styles
mix.sass(`${srcPath}/scss/admin.scss`, `${distPath}/css/admin.css`);

// Process editor styles
mix.sass(`${srcPath}/scss/editor-style.scss`, `${distPath}/css/editor-style.css`);

// Process JavaScript files
mix.js(`${srcPath}/js/app.js`, `${distPath}/js`)
   .js(`${srcPath}/js/customizer.js`, `${distPath}/js`)
   .js(`${srcPath}/js/editor.js`, `${distPath}/js`)
   .js(`${srcPath}/js/admin.js`, `${distPath}/js`);

// Process WooCommerce scripts if needed
if (fs.existsSync(path.resolve(__dirname, `${srcPath}/js/woocommerce.js`))) {
  mix.js(`${srcPath}/js/woocommerce.js`, `${distPath}/js`);
}

// Copy and optimize images
mix.copyDirectory(`${srcPath}/images`, `${distPath}/images`);

// Copy fonts
if (fs.existsSync(path.resolve(__dirname, `${srcPath}/fonts`))) {
  mix.copyDirectory(`${srcPath}/fonts`, `${distPath}/fonts`);
}

// Handle font files with file-loader
mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.(woff2?|ttf|eot|svg|otf)$/,
        loader: 'file-loader',
        options: {
          name: '[name].[ext]',
          outputPath: 'fonts/',
          publicPath: '../fonts/',
        },
      },
    ],
  },
});

// Version files in production
if (mix.inProduction()) {
  mix.version();
}

// Enable source maps in development
if (!mix.inProduction()) {
  mix.sourceMaps();
}

// Configure BrowserSync for development
if (!mix.inProduction()) {
  mix.browserSync({
    proxy: 'localhost',
    files: [
      `${distPath}/**/*`,
      '**/*.php',
    ],
    notify: false,
  });
}