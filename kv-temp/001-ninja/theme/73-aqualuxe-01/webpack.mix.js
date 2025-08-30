const mix = require('laravel-mix');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 | AquaLuxe Theme Asset Pipeline
 | Compiles Sass, JavaScript, optimizes images and fonts
 | Implements tree-shaking, minification, and cache-busting
 */

// Set public path for assets
mix.setPublicPath('assets/dist');

// Configure options
mix.options({
  processCssUrls: false,
  postCss: [
    require('tailwindcss'),
    require('autoprefixer'),
  ],
});

// JavaScript compilation with tree-shaking
mix.js('assets/src/js/app.js', 'assets/dist/js')
   .js('assets/src/js/admin.js', 'assets/dist/js')
   .js('assets/src/js/customizer.js', 'assets/dist/js')
   .js('assets/src/js/woocommerce.js', 'assets/dist/js');

// Sass compilation with Tailwind CSS
mix.sass('assets/src/sass/app.scss', 'assets/dist/css')
   .sass('assets/src/sass/admin.scss', 'assets/dist/css')
   .sass('assets/src/sass/customizer.scss', 'assets/dist/css')
   .sass('assets/src/sass/woocommerce.scss', 'assets/dist/css')
   .sass('assets/src/sass/dark-mode.scss', 'assets/dist/css');

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Extract vendor libraries for better caching
mix.extract(['alpinejs', 'glide-js', 'lazysizes', 'photoswipe']);

// Version files for cache busting in production
if (mix.inProduction()) {
  mix.version();
  
  // Additional production optimizations
  mix.webpackConfig({
    plugins: [
      new ImageminPlugin({
        test: /\.(jpe?g|png|gif|svg)$/i,
        plugins: [
          imageminMozjpeg({ quality: 85 }),
          imageminPngquant({ quality: [0.65, 0.8] })
        ],
        pngquant: {
          quality: [0.65, 0.8]
        },
        optipng: {
          optimizationLevel: 5
        },
        mozjpeg: {
          progressive: true,
          quality: 85
        }
      })
    ]
  });
} else {
  // Development source maps
  mix.sourceMaps();
}

// Webpack configuration
mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve('assets/src'),
      '@js': path.resolve('assets/src/js'),
      '@sass': path.resolve('assets/src/sass'),
      '@images': path.resolve('assets/src/images'),
      '@fonts': path.resolve('assets/src/fonts'),
    }
  },
  module: {
    rules: [
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'fonts/',
              publicPath: '../fonts/',
            },
          },
        ],
      },
      {
        test: /\.(png|jpe?g|gif|svg)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'images/',
              publicPath: '../images/',
            },
          },
        ],
      },
    ],
  },
});

// Disable mix notifications
mix.disableNotifications();
