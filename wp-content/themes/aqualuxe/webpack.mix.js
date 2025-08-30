const mix = require('laravel-mix');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');
const imageminSvgo = require('imagemin-svgo');

// Configure Mix
mix.setPublicPath('assets/dist')
   .setResourceRoot('../')
   .options({
     processCssUrls: false,
     postCss: [
       require('tailwindcss'),
       require('autoprefixer')
     ]
   });

// JavaScript compilation
mix.js('assets/src/js/main.js', 'assets/dist/js')
   .js('assets/src/js/admin.js', 'assets/dist/js')
   .js('assets/src/js/customizer-preview.js', 'assets/dist/js');

// WooCommerce specific JavaScript
mix.js('assets/src/js/woocommerce.js', 'assets/dist/js');

// Module-specific JavaScript
mix.js('assets/src/js/modules/dark-mode.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/multilingual.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/events.js', 'assets/dist/js/modules')
   .js('assets/src/js/modules/bookings.js', 'assets/dist/js/modules');

// SCSS compilation
mix.sass('assets/src/scss/main.scss', 'assets/dist/css')
   .sass('assets/src/scss/admin.scss', 'assets/dist/css')
   .sass('assets/src/scss/editor.scss', 'assets/dist/css')
   .sass('assets/src/scss/critical.scss', 'assets/dist/css');

// WooCommerce specific styles
mix.sass('assets/src/scss/woocommerce.scss', 'assets/dist/css');

// Module-specific styles
mix.sass('assets/src/scss/modules/dark-mode.scss', 'assets/dist/css/modules')
   .sass('assets/src/scss/modules/events.scss', 'assets/dist/css/modules')
   .sass('assets/src/scss/modules/bookings.scss', 'assets/dist/css/modules');

// Copy and optimize images
mix.copy('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copy('assets/src/fonts', 'assets/dist/fonts');

// Enable versioning in production
if (mix.inProduction()) {
  mix.version();
}

// Enable source maps in development
if (!mix.inProduction()) {
  mix.sourceMaps();
}

// BrowserSync for development
mix.browserSync({
  proxy: 'localhost:8000', // Adjust to your local development URL
  files: [
    '**/*.php',
    'assets/dist/**/*.css',
    'assets/dist/**/*.js'
  ],
  watchOptions: {
    usePolling: true,
    interval: 1000
  }
});

// Webpack configuration
mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve('assets/src'),
      '@js': path.resolve('assets/src/js'),
      '@scss': path.resolve('assets/src/scss'),
      '@images': path.resolve('assets/src/images')
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
  },
  plugins: [
    new ImageminPlugin({
      test: /\.(jpe?g|png|gif|svg)$/i,
      pngquant: {
        quality: '65-90'
      },
      mozjpeg: {
        quality: 85,
        progressive: true
      },
      svgo: {
        plugins: [
          { removeViewBox: false },
          { cleanupIDs: false }
        ]
      }
    })
  ],
  externals: {
    jquery: 'jQuery',
    wp: 'wp'
  }
});

// Disable Mix's default notifications
mix.disableNotifications();

// Enable hot reloading for development
if (!mix.inProduction()) {
  mix.options({
    hmrOptions: {
      host: 'localhost',
      port: 8080
    }
  });
}

// Optimize for production
if (mix.inProduction()) {
  mix.options({
    terser: {
      terserOptions: {
        compress: {
          drop_console: true
        }
      }
    }
  });
}
