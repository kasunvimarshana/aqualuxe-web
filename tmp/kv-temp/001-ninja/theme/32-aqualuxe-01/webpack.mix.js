/**
 * AquaLuxe Theme - Enhanced Laravel Mix Configuration
 *
 * This file configures Laravel Mix (webpack wrapper) for the AquaLuxe theme
 * with comprehensive asset optimization, minification, and bundling.
 */

const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs');
const glob = require('glob');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyPlugin = require('copy-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const WebpackAssetsManifest = require('webpack-assets-manifest');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const FontminPlugin = require('fontmin-webpack');
const WorkboxPlugin = require('workbox-webpack-plugin');

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

// Cache busting hash format
const hashFormat = {
  'length': 8,
  'delimiter': '.'
};

// Clean output directory before build
mix.webpackConfig({
  plugins: [
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: [
        `${distPath}/css/**/*`,
        `${distPath}/js/**/*`,
        `${distPath}/fonts/**/*`,
        `${distPath}/images/**/*`,
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
    `${srcPath}/js/custom.js`
  ],
  'woocommerce': [
    `${srcPath}/js/checkout-steps.js`,
    `${srcPath}/js/country-selector.js`,
    `${srcPath}/js/currency-switcher.js`,
    `${srcPath}/js/filter.js`,
    `${srcPath}/js/wishlist.js`,
    `${srcPath}/js/woocommerce.js`
  ],
  'customizer': [
    `${srcPath}/js/customizer/customizer-controls.js`
  ],
  'customizer-preview': [
    `${srcPath}/js/customizer/customizer-preview.js`
  ],
  'lazy-loading': [
    `${srcPath}/js/lazy-loading.js`
  ],
  'service-worker-register': [
    `${srcPath}/js/service-worker-register.js`
  ]
};

// Process JavaScript bundles with enhanced optimization
Object.entries(jsBundles).forEach(([bundleName, files]) => {
  mix.js(files, `${distPath}/js/${bundleName}.js`)
     .babel(`${distPath}/js/${bundleName}.js`, `${distPath}/js/${bundleName}.js`);
});

// Process individual JavaScript files
glob.sync(`${srcPath}/js/components/*.js`).forEach(file => {
  const filename = path.basename(file);
  mix.js(file, `${distPath}/js/components/${filename}`)
     .babel(`${distPath}/js/components/${filename}`, `${distPath}/js/components/${filename}`);
});

// Process SCSS files with enhanced optimization
mix.sass(`${srcPath}/scss/main.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/dark-mode.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/woocommerce.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/admin.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/editor.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/print.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/customizer-controls.scss`, `${distPath}/css`)
   .options({
     processCssUrls: true, // Process and optimize URLs in CSS
     postCss: [
       require('postcss-import'),
       require('tailwindcss')('./tailwind.config.js'),
       require('autoprefixer'),
       require('postcss-preset-env')({
         stage: 2,
         features: {
           'nesting-rules': true
         }
       }),
       ...(isProduction ? [
         require('cssnano')({
           preset: ['advanced', {
             discardComments: {
               removeAll: true
             },
             reduceIdents: false,
             zindex: false
           }]
         })
       ] : [])
     ]
   });

// Generate critical CSS for key templates
if (isProduction) {
  const criticalCss = require('critical');
  const templates = [
    { name: 'home', url: '/' },
    { name: 'blog', url: '/blog/' },
    { name: 'shop', url: '/shop/' },
    { name: 'product', url: '/product/sample-product/' }
  ];

  mix.then(() => {
    templates.forEach(template => {
      criticalCss.generate({
        src: `http://localhost/${template.url}`,
        target: `${distPath}/css/critical/${template.name}.css`,
        width: 1400,
        height: 900,
        minify: true,
        extract: false,
        ignore: ['@font-face', /url\(/]
      });
    });
  });
}

// Font processing and optimization using alternative packages
mix.webpackConfig({
  plugins: [
    new FontminPlugin({
      autodetect: true, // Automatically detect fonts in CSS
      glyphs: ['\uf001-\uf999', '\ue001-\ue999'], // Include icon font ranges
      allowedFilesRegex: /\.(ttf|otf|eot|woff|woff2)$/i,
      fontmin: {
        use: [
          require('fontmin-webpack/lib/plugin/woff2')(),
          require('fontmin-webpack/lib/plugin/ttf')(),
          require('fontmin-webpack/lib/plugin/woff')()
        ]
      }
    })
  ]
});

// Copy and optimize static assets
mix.copyDirectory(`${srcPath}/fonts`, `${distPath}/fonts`);

// Image optimization with WebP conversion
mix.webpackConfig({
  plugins: [
    new ImageminPlugin({
      test: /\.(jpe?g|png|gif|svg)$/i,
      pngquant: {
        quality: '65-80'
      },
      optipng: {
        optimizationLevel: 3
      },
      jpegtran: {
        progressive: true
      },
      gifsicle: {
        interlaced: true,
        optimizationLevel: 3
      },
      svgo: {
        plugins: [
          { removeViewBox: false },
          { cleanupIDs: false },
          { removeDimensions: true },
          { removeUselessStrokeAndFill: true }
        ]
      },
      plugins: [
        // Convert to WebP
        require('imagemin-webp')({
          quality: 75
        })
      ],
      // Also create WebP versions
      webp: {
        quality: 75
      }
    })
  ]
});

// SVG sprite generation with optimization
mix.webpackConfig({
  plugins: [
    new SVGSpritemapPlugin(`${srcPath}/images/icons/**/*.svg`, {
      output: {
        filename: `${distPath}/images/sprite.svg`,
        svg4everybody: true,
        svgo: {
          plugins: [
            { removeViewBox: false },
            { cleanupIDs: true },
            { removeDimensions: true }
          ]
        }
      },
      sprite: {
        prefix: 'icon-',
        generate: {
          title: false
        }
      }
    })
  ]
});

// Copy vendor assets with optimization
mix.copyDirectory(`${srcPath}/vendor`, `${distPath}/vendor`);

// Service worker generation
if (isProduction) {
  mix.webpackConfig({
    plugins: [
      new WorkboxPlugin.GenerateSW({
        clientsClaim: true,
        skipWaiting: true,
        directoryIndex: 'index.php',
        offlineGoogleAnalytics: true,
        runtimeCaching: [
          {
            urlPattern: /\.(?:png|jpg|jpeg|svg|gif|webp)$/,
            handler: 'CacheFirst',
            options: {
              cacheName: 'images',
              expiration: {
                maxEntries: 60,
                maxAgeSeconds: 30 * 24 * 60 * 60 // 30 days
              }
            }
          },
          {
            urlPattern: /\.(?:js|css)$/,
            handler: 'StaleWhileRevalidate',
            options: {
              cacheName: 'static-resources',
              expiration: {
                maxEntries: 60,
                maxAgeSeconds: 7 * 24 * 60 * 60 // 7 days
              }
            }
          },
          {
            urlPattern: /\.(?:woff|woff2|ttf|otf|eot)$/,
            handler: 'CacheFirst',
            options: {
              cacheName: 'fonts',
              expiration: {
                maxEntries: 30,
                maxAgeSeconds: 60 * 24 * 60 * 60 // 60 days
              }
            }
          }
        ]
      })
    ]
  });
}

// Production-specific configurations
if (isProduction) {
  // Add versioning with hash format
  mix.version();
  
  // Generate source maps
  mix.sourceMaps(false, 'source-map');
  
  // Extract vendor libraries
  mix.extract([
    'lodash', 
    'jquery', 
    'intersection-observer',
    'focus-visible',
    'whatwg-fetch'
  ]);
  
  // Advanced optimization
  mix.webpackConfig({
    optimization: {
      minimizer: [
        new TerserPlugin({
          terserOptions: {
            compress: {
              drop_console: true,
              drop_debugger: true,
              pure_funcs: ['console.log', 'console.info', 'console.debug']
            },
            output: {
              comments: false,
              beautify: false
            },
            mangle: true
          },
          extractComments: false,
          parallel: true
        }),
        new CssMinimizerPlugin({
          minimizerOptions: {
            preset: [
              'advanced',
              {
                discardComments: { removeAll: true },
                reduceIdents: false,
                zindex: false
              }
            ]
          }
        })
      ],
      splitChunks: {
        chunks: 'all',
        maxInitialRequests: Infinity,
        minSize: 0,
        cacheGroups: {
          defaultVendors: {
            test: /[\\/]node_modules[\\/]/,
            priority: -10,
            reuseExistingChunk: true,
            name: 'vendor'
          },
          common: {
            minChunks: 2,
            priority: -20,
            reuseExistingChunk: true,
            name: 'common'
          }
        }
      }
    },
    plugins: [
      // Generate assets manifest for cache busting
      new WebpackAssetsManifest({
        output: `${distPath}/assets-manifest.json`,
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
  // Browser Sync
  mix.webpackConfig({
    plugins: [
      new BrowserSyncPlugin({
        proxy: 'localhost',
        files: [
          'assets/css/**/*.css',
          'assets/js/**/*.js',
          '**/*.php'
        ],
        notify: false,
        open: false,
        reloadDelay: 500
      })
    ]
  });
}

// Disable notifications
mix.disableNotifications();

// Set public path
mix.setPublicPath(publicPath);

// Generate asset loading helper
mix.then(() => {
  if (isProduction) {
    // Create a PHP helper file for loading versioned assets
    const helperContent = `<?php
/**
 * Asset loading helper functions
 * 
 * Generated automatically by webpack.mix.js
 * Do not edit this file directly
 */

/**
 * Get the versioned asset URL
 *
 * @param string $path Asset path relative to theme root
 * @return string Versioned asset URL
 */
function aqualuxe_asset($path) {
    static $manifest = null;
    
    if (is_null($manifest)) {
        $manifestPath = get_template_directory() . '/assets/assets-manifest.json';
        
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        } else {
            $manifest = [];
        }
    }
    
    $path = ltrim($path, '/');
    
    if (isset($manifest[$path])) {
        return get_template_directory_uri() . '/' . $manifest[$path];
    }
    
    return get_template_directory_uri() . '/' . $path;
}

/**
 * Print the versioned asset URL
 *
 * @param string $path Asset path relative to theme root
 * @return void
 */
function aqualuxe_asset_url($path) {
    echo esc_url(aqualuxe_asset($path));
}

/**
 * Get critical CSS for a specific template
 *
 * @param string $template Template name (home, blog, shop, product)
 * @return string Critical CSS content
 */
function aqualuxe_get_critical_css($template) {
    $critical_css_path = get_template_directory() . '/assets/css/critical/' . $template . '.css';
    
    if (file_exists($critical_css_path)) {
        return file_get_contents($critical_css_path);
    }
    
    return '';
}

/**
 * Print critical CSS inline
 *
 * @param string $template Template name (home, blog, shop, product)
 * @return void
 */
function aqualuxe_critical_css($template) {
    $critical_css = aqualuxe_get_critical_css($template);
    
    if (!empty($critical_css)) {
        echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>';
    }
}`;
    
    fs.writeFileSync('inc/helpers/asset-loader.php', helperContent);
  }
});