const mix = require('laravel-mix');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const WebpackAssetsManifest = require('webpack-assets-manifest');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Enhanced configuration for comprehensive asset optimization, minification,
 | and bundling with proper cache busting and deployment-ready output.
 |
 */

// Environment detection
const isProduction = mix.inProduction();
const isDevelopment = !isProduction;

// Path configurations
const srcPath = 'assets/src';
const distPath = 'assets';
const publicPath = './';

// Clean output directory before build
mix.webpackConfig({
  plugins: [
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: [
        `${distPath}/css/**/*`,
        `${distPath}/js/**/*`,
        `${distPath}/fonts/**/*`,
        `${distPath}/images/dist/**/*`,
        '!assets/images/uploads/**/*' // Preserve user uploads
      ]
    })
  ]
});

// JavaScript bundles configuration
const jsBundles = {
  'app': [
    `${srcPath}/js/navigation.js`,
    `${srcPath}/js/dark-mode.js`,
    `${srcPath}/js/lazy-loading.js`,
    `${srcPath}/js/accessibility.js`,
    `${srcPath}/js/mobile-menu.js`,
    `${srcPath}/js/search.js`,
    `${srcPath}/js/main.js`
  ],
  'woocommerce': [
    `${srcPath}/js/woocommerce/checkout-steps.js`,
    `${srcPath}/js/woocommerce/country-selector.js`,
    `${srcPath}/js/woocommerce/currency-switcher.js`,
    `${srcPath}/js/woocommerce/product-filters.js`,
    `${srcPath}/js/woocommerce/wishlist.js`,
    `${srcPath}/js/woocommerce/quick-view.js`,
    `${srcPath}/js/woocommerce/main.js`
  ],
  'customizer': [
    `${srcPath}/js/customizer/customizer-controls.js`
  ],
  'customizer-preview': [
    `${srcPath}/js/customizer/customizer-preview.js`
  ]
};

// Process JavaScript bundles with enhanced optimization
Object.entries(jsBundles).forEach(([bundleName, files]) => {
  mix.js(files, `${distPath}/js/${bundleName}.js`)
     .options({
       processCssUrls: false,
       terser: {
         extractComments: false,
         terserOptions: {
           compress: {
             drop_console: isProduction
           }
         }
       }
     });
});

// CSS/Sass processing
mix.sass(`${srcPath}/sass/main.scss`, `${distPath}/css/app.css`)
   .sass(`${srcPath}/sass/woocommerce.scss`, `${distPath}/css/woocommerce.css`)
   .sass(`${srcPath}/sass/admin.scss`, `${distPath}/css/admin.css`)
   .sass(`${srcPath}/sass/editor.scss`, `${distPath}/css/editor-style.css`)
   .options({
     processCssUrls: false,
     postCss: [
       require('tailwindcss'),
       require('autoprefixer')
     ]
   });

// Copy and optimize images
mix.copyDirectory(`${srcPath}/images`, `${distPath}/images/dist`);

// Copy fonts
mix.copyDirectory(`${srcPath}/fonts`, `${distPath}/fonts`);

// Webpack configuration
mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(`${srcPath}/js`),
      '@css': path.resolve(`${srcPath}/sass`),
      '@images': path.resolve(`${srcPath}/images`)
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
              name: '[name].[hash:8].[ext]',
              outputPath: 'images/dist/',
              publicPath: '../images/dist/'
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
              name: '[name].[hash:8].[ext]',
              outputPath: 'fonts/',
              publicPath: '../fonts/'
            }
          }
        ]
      }
    ]
  }
});

// Production optimizations
if (isProduction) {
  mix.version();
  
  mix.webpackConfig({
    plugins: [
      // Image optimization
      new ImageminPlugin({
        test: /\.(jpe?g|png|gif|svg)$/i,
        pngquant: {
          quality: [0.6, 0.8]
        },
        mozjpeg: {
          quality: 80
        },
        gifsicle: {
          optimizationLevel: 3
        },
        svgo: {
          plugins: [
            { removeViewBox: false },
            { cleanupIDs: false }
          ]
        }
      }),
      
      // Generate assets manifest for cache busting
      new WebpackAssetsManifest({
        output: `${distPath}/mix-manifest.json`,
        writeToDisk: true,
        customize: (key, value) => {
          // Only include CSS and JS files in the manifest
          if (key.endsWith('.map') || !(/\.(css|js)$/.test(key))) {
            return false;
          }
          return { key, value };
        }
      })
    ]
  });
}

// Development-specific configurations
if (isDevelopment) {
  mix.sourceMaps();
}

// Disable notifications
mix.disableNotifications();

// Set public path
mix.setPublicPath(publicPath);

// Options
mix.options({
  hmrOptions: {
    host: 'localhost',
    port: 8080
  }
});

// Extract vendor libraries (optional)
if (isProduction) {
  mix.extract(['jquery', 'swiper']);
}