const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | AquaLuxe Theme - Laravel Mix Configuration
 |--------------------------------------------------------------------------
 |
 | Modern asset compilation pipeline for the AquaLuxe WordPress theme.
 | Supports ES6+, Tailwind CSS, SCSS preprocessing, image optimization,
 | and production-ready asset bundling with versioning and minification.
 |
 | @package AquaLuxe
 | @version 2.0.0
 |
 */

/*
 |--------------------------------------------------------------------------
 | Configuration
 |--------------------------------------------------------------------------
 */

// Set public path for assets
mix.setPublicPath('assets/dist');
mix.setResourceRoot('../'); // Needed for url() in CSS

// Configure source and destination paths
const paths = {
  src: 'assets/src',
  dist: 'assets/dist',
  node: 'node_modules',
};

/*
 |--------------------------------------------------------------------------
 | JavaScript Compilation
 |--------------------------------------------------------------------------
 */

// Main application JavaScript
mix.js(`${paths.src}/js/app.js`, 'js/app.js');

// Admin JavaScript (conditionally compiled)
if (mix.inProduction() || process.env.NODE_ENV === 'development') {
  mix.js(`${paths.src}/js/admin.js`, 'js/admin.js');
  mix.js(`${paths.src}/js/customizer.js`, 'js/customizer.js');
  mix.js(`${paths.src}/js/editor.js`, 'js/editor.js');
  mix.js(`${paths.src}/js/navigation.js`, 'js/navigation.js');
  mix.js(`${paths.src}/js/dark-mode.js`, 'js/dark-mode.js');
}

/*
 |--------------------------------------------------------------------------
 | CSS/SCSS Compilation
 |--------------------------------------------------------------------------
 */

// Main application styles with Tailwind CSS
mix
  .postCss(`${paths.src}/css/app.css`, 'css/app.css', [
    require('tailwindcss'),
    require('autoprefixer'),
  ])
  .sass(`${paths.src}/scss/app.scss`, 'css')
  .sass(`${paths.src}/scss/woocommerce.scss`, 'css');

// Additional stylesheets (conditionally compiled)
if (mix.inProduction() || process.env.NODE_ENV === 'development') {
  mix.sass(`${paths.src}/scss/admin.scss`, 'css/admin.css');
  mix.sass(`${paths.src}/scss/customizer.scss`, 'css/customizer.css');
  mix.sass(`${paths.src}/scss/editor.scss`, 'css/editor.css');
  mix.sass(`${paths.src}/scss/print.scss`, 'css/print.css');
  mix.sass(`${paths.src}/scss/dark-mode.scss`, 'css/dark-mode.css');
}

/*
 |--------------------------------------------------------------------------
 | Asset Copying & Optimization
 |--------------------------------------------------------------------------
 */

// Copy and optimize images
mix.copyDirectory(`${paths.src}/images`, `${paths.dist}/images`);

// Copy and optimize fonts
mix.copyDirectory(`${paths.src}/fonts`, `${paths.dist}/fonts`);

/*
 |--------------------------------------------------------------------------
 | Options & Optimization
 |--------------------------------------------------------------------------
 */

mix.options({
  // Process CSS URLs for proper asset handling
  processCssUrls: true,

  // Enable PostCSS plugins
  postCss: [require('tailwindcss'), require('autoprefixer')],

  // Clear console on rebuild
  clearConsole: true,
});

/*
 |--------------------------------------------------------------------------
 | Webpack Configuration
 |--------------------------------------------------------------------------
 */

mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, `${paths.src}/js`),
      '@css': path.resolve(__dirname, `${paths.src}/css`),
      '@scss': path.resolve(__dirname, `${paths.src}/scss`),
      '@images': path.resolve(__dirname, `${paths.src}/images`),
      '@fonts': path.resolve(__dirname, `${paths.src}/fonts`),
    },
  },

  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
            plugins: [
              '@babel/plugin-proposal-class-properties',
              '@babel/plugin-syntax-dynamic-import',
            ],
          },
        },
      },
    ],
  },

  stats: {
    children: true,
  },
});

/*
 |--------------------------------------------------------------------------
 | Production Optimizations
 |--------------------------------------------------------------------------
 */

if (mix.inProduction()) {
  // Enable versioning for cache busting
  mix.version();

  // Disable OS notifications in production
  mix.disableNotifications();

  // Generate sourcemaps for debugging
  mix.sourceMaps(false, 'source-map');
} else {
  // Development mode options
  mix.sourceMaps(true, 'inline-source-map');
}

/*
 |--------------------------------------------------------------------------
 | Browser Sync Configuration
 |--------------------------------------------------------------------------
 */

// Browser sync for development
if (!mix.inProduction()) {
  mix.browserSync({
    proxy: 'localhost:8000', // Your local WordPress URL
    files: ['assets/dist/js/**/*.js', 'assets/dist/css/**/*.css', '**/*.php', '*.php'],
    injectChanges: true,
    open: false,
    notify: false,
    ghostMode: false,
  });
}

/*
 |--------------------------------------------------------------------------
 | Custom Tasks & Hooks
 |--------------------------------------------------------------------------
 */

// Theme-specific tasks
if (mix.inProduction()) {
  mix.then(() => {
    console.log('✅ AquaLuxe theme assets compiled successfully!');
    console.log('📦 Ready for production deployment.');
  });
} else {
  mix.then(() => {
    console.log('🚀 AquaLuxe development assets compiled!');
    console.log('👀 Watching for changes...');
  });
}
