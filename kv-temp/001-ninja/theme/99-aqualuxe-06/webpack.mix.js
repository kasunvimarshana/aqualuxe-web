const mix = require('laravel-mix');
const path = require('path');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

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

// Set the public path for assets
mix.setPublicPath('assets/dist');

// Disable mix-manifest in public root 
mix.options({
  manifest: false
});

// Generate mix-manifest.json in assets/dist
mix.webpackConfig({
  plugins: [
    // Clean dist folder before build
    new CleanWebpackPlugin({
      cleanOnceBeforeBuildPatterns: ['**/*', '!.gitkeep']
    }),
    
    // Copy and optimize images
    new CopyWebpackPlugin({
      patterns: [
        {
          from: 'assets/src/images',
          to: 'images',
          noErrorOnMissing: true
        },
        {
          from: 'assets/src/fonts',
          to: 'fonts',
          noErrorOnMissing: true
        }
      ]
    }),
    
    // Optimize images
    new ImageminPlugin({
      test: /\.(jpe?g|png|gif|svg)$/i,
      pngquant: {
        quality: '65-90'
      },
      mozjpeg: {
        progressive: true,
        quality: 85
      },
      svgo: {
        plugins: [
          {
            name: 'removeViewBox',
            active: false
          }
        ]
      }
    })
  ],
  resolve: {
    alias: {
      '@': path.resolve('assets/src/js'),
      'scss': path.resolve('assets/src/scss'),
      'images': path.resolve('assets/src/images')
    }
  }
});

// Process stylesheets
mix.sass('assets/src/scss/main.scss', 'css/main.css')
   .sass('assets/src/scss/admin.scss', 'css/admin.css')
   .sass('assets/src/scss/woocommerce.scss', 'css/woocommerce.css')
   .sass('assets/src/scss/editor.scss', 'css/editor.css');

// Process JavaScript
mix.js('assets/src/js/main.js', 'js/main.js')
   .js('assets/src/js/admin.js', 'js/admin.js')
   .js('assets/src/js/customizer.js', 'js/customizer.js');

// Add Tailwind CSS processing
mix.options({
  postCss: [
    require('tailwindcss')('./tailwind.config.js'),
    require('autoprefixer')
  ]
});

// Enable source maps in development
if (!mix.inProduction()) {
  mix.sourceMaps();
  mix.webpackConfig({
    devtool: 'inline-source-map'
  });
}

// Optimize for production
if (mix.inProduction()) {
  mix.version();
  
  // Additional production optimizations
  mix.webpackConfig({
    optimization: {
      splitChunks: {
        chunks: 'all',
        cacheGroups: {
          vendor: {
            test: /[\\/]node_modules[\\/]/,
            name: 'vendors',
            chunks: 'all',
            priority: 20
          },
          common: {
            name: 'common',
            minChunks: 2,
            chunks: 'all',
            priority: 10,
            reuseExistingChunk: true,
            enforce: true
          }
        }
      }
    }
  });
}

// Browser Sync for development
if (!mix.inProduction()) {
  mix.browserSync({
    proxy: 'localhost', // Change this to your local development URL
    files: [
      '**/*.php',
      'assets/dist/**/*'
    ],
    watchOptions: {
      usePolling: true,
      interval: 1000
    }
  });
}

// Disable notifications
mix.disableNotifications();

// Create manifest file for cache busting
mix.webpackConfig({
  plugins: [
    {
      apply: (compiler) => {
        compiler.hooks.afterEmit.tap('ManifestPlugin', (compilation) => {
          const fs = require('fs');
          const path = require('path');
          
          const manifest = {};
          const assets = compilation.getAssets();
          
          assets.forEach(asset => {
            const name = asset.name;
            const originalName = name.replace(/\.\w{20}\./, '.');
            manifest[originalName] = name;
          });
          
          const manifestPath = path.join(compiler.options.output.path, 'mix-manifest.json');
          fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 2));
        });
      }
    }
  ]
});