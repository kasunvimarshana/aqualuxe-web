const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const path = require('path');

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

// Configure public path
mix.setPublicPath('assets/dist');

// Configure source map for development
if (!mix.inProduction()) {
  mix.sourceMaps();
}

// Configure versioning for production
if (mix.inProduction()) {
  mix.version();
}

// JavaScript compilation
mix.js('assets/src/js/app.js', 'assets/dist/js')
   .js('assets/src/js/admin.js', 'assets/dist/js')
   .js('assets/src/js/customizer.js', 'assets/dist/js')
   .js('assets/src/js/customizer-preview.js', 'assets/dist/js')
   .js('assets/src/js/dark-mode.js', 'assets/dist/js')
   .js('assets/src/js/multilingual.js', 'assets/dist/js')
   .js('assets/src/js/woocommerce.js', 'assets/dist/js');

// CSS compilation with Tailwind
mix.sass('assets/src/scss/app.scss', 'assets/dist/css')
   .sass('assets/src/scss/admin.scss', 'assets/dist/css')
   .sass('assets/src/scss/customizer.scss', 'assets/dist/css')
   .sass('assets/src/scss/dark-mode.scss', 'assets/dist/css')
   .sass('assets/src/scss/login.scss', 'assets/dist/css')
   .sass('assets/src/scss/rtl.scss', 'assets/dist/css')
   .sass('assets/src/scss/woocommerce.scss', 'assets/dist/css')
   .options({
     processCssUrls: false,
     postCss: [
       tailwindcss('./tailwind.config.js'),
       require('autoprefixer')
     ]
   });

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Extract vendor libraries for better caching
mix.extract(['alpinejs', 'aos', 'gsap', 'lazysizes', 'swiper']);

// Configure webpack
mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve('assets/src')
    }
  },
  module: {
    rules: [
      {
        test: /\.(png|jpe?g|gif|svg)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[hash].[ext]',
              outputPath: 'images/',
              publicPath: '../images/'
            }
          },
          {
            loader: 'imagemin-loader',
            options: {
              mozjpeg: {
                progressive: true,
                quality: 85
              },
              pngquant: {
                quality: [0.8, 0.9],
                speed: 4
              },
              svgo: {
                plugins: [
                  {
                    removeViewBox: false
                  }
                ]
              }
            }
          }
        ]
      },
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[hash].[ext]',
              outputPath: 'fonts/',
              publicPath: '../fonts/'
            }
          }
        ]
      }
    ]
  },
  optimization: {
    splitChunks: {
      chunks: 'all',
      cacheGroups: {
        vendor: {
          test: /[\\/]node_modules[\\/]/,
          name: 'vendors',
          chunks: 'all'
        }
      }
    }
  }
});

// Enable hot module replacement for development
if (process.env.NODE_ENV === 'development') {
  mix.options({
    hmrOptions: {
      host: 'localhost',
      port: 8080
    }
  });
}

// Configure browser sync for local development
mix.browserSync({
  proxy: 'localhost:8000', // Update with your local development URL
  files: [
    '**/*.php',
    'assets/dist/**/*'
  ],
  watchOptions: {
    usePolling: true,
    interval: 1000
  }
});

// Disable OS notifications
mix.disableNotifications();
